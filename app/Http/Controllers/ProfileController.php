<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('auth.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        try {
            $user = $request->user();
            $hasUsername = Schema::hasColumn('users', 'username');
            $hasPhoto = Schema::hasColumn('users', 'profile_photo_path');

            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'password' => ['nullable', 'string', 'min:6', 'confirmed'],
                'photo' => ['nullable', 'image', 'max:4096'],
            ];
            if ($hasUsername) {
                $rules['username'] = ['nullable', 'string', 'max:255', 'unique:users,username,' . $user->id];
            } else {
                $rules['username'] = ['nullable', 'string', 'max:255'];
            }

            $validated = $request->validate($rules);

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                if (!Storage::exists('public/profile-photos')) {
                    Storage::makeDirectory('public/profile-photos');
                }
                if ($hasPhoto) {
                    $stored = $file->store('public/profile-photos');
                    $user->profile_photo_path = str_replace('public/', '', (string) $stored);
                } else {
                    $ext = strtolower($file->getClientOriginalExtension() ?: 'png');
                    // Hapus versi lama
                    foreach (Storage::files('public/profile-photos') as $f) {
                        if (preg_match('/profile-photos\/u-' . preg_quote((string) $user->id, '/') . '\.[a-z0-9]+$/i', $f)) {
                            Storage::delete($f);
                        }
                    }
                    $file->storeAs('public/profile-photos', 'u-' . $user->id . '.' . $ext);
                }

                // Duplikasi ke public/uploads/avatars agar bisa diakses statis (tanpa symlink)
                $publicAvatarDir = public_path('uploads/avatars');
                if (!is_dir($publicAvatarDir)) {
                    @mkdir($publicAvatarDir, 0777, true);
                }
                // Hapus versi lama di folder publik
                foreach (glob($publicAvatarDir . DIRECTORY_SEPARATOR . 'u-' . $user->id . '.*') ?: [] as $old) {
                    @unlink($old);
                }
                $ext2 = strtolower($file->getClientOriginalExtension() ?: 'png');
                $src = null;
                if (!empty($user->profile_photo_path)) {
                    $cand = storage_path('app/public/' . ltrim((string) $user->profile_photo_path, '/'));
                    if (is_file($cand)) {
                        $src = $cand;
                    }
                }
                if (!$src) {
                    $cands = glob(storage_path('app/public/profile-photos/u-' . $user->id . '.*')) ?: [];
                    if (!empty($cands)) {
                        $src = $cands[0];
                        $ext2 = strtolower(pathinfo($src, PATHINFO_EXTENSION) ?: $ext2);
                    }
                }
                if ($src && is_file($src)) {
                    $dest = $publicAvatarDir . DIRECTORY_SEPARATOR . 'u-' . $user->id . '.' . $ext2;
                    @copy($src, $dest);
                }

                // Sentuh timestamp agar avatar di navbar refresh
                $user->setUpdatedAt(now());
            }

            $user->name = $validated['name'];
            if ($hasUsername) {
                $user->username = $validated['username'] ?? null;
            }
            $user->email = $validated['email'];
            if (!empty($validated['password'])) {
                $user->password = $validated['password']; // di-hash oleh cast
            }
            $user->save();

            return redirect()->route('profile.edit')->with('status', 'Profil diperbarui.');
        } catch (\Throwable $e) {
            return back()->withErrors(['photo' => 'Gagal memperbarui profil: ' . $e->getMessage()]);
        }
    }

    /**
     * Tampilkan foto profil user yang sedang login.
     * Fallback ke SVG in-memory jika belum ada foto atau file tidak ditemukan.
     */
    public function photo(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response($this->defaultAvatarSvg('U'), 200)->header('Content-Type', 'image/svg+xml');
        }

        $initial = strtoupper(substr((string) ($user->name ?? 'U'), 0, 1));
        $path = (string) ($user->profile_photo_path ?? '');

        if ($path !== '') {
            $storagePath = 'public/' . ltrim($path, '/');
            if (Storage::exists($storagePath)) {
                $abs = storage_path('app/' . $storagePath);
                $mime = function_exists('mime_content_type') ? (mime_content_type($abs) ?: 'image/png') : 'image/png';
                return response()->file($abs, [
                    'Content-Type' => $mime,
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                ]);
            }

            $publicPath = public_path(ltrim($path, '/'));
            if (is_file($publicPath)) {
                $mime = function_exists('mime_content_type') ? (mime_content_type($publicPath) ?: 'image/png') : 'image/png';
                return response()->file($publicPath, [
                    'Content-Type' => $mime,
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                ]);
            }
        }

        // Fallback: jika kolom tidak ada/ kosong, cari file dengan pola u-<id>.*
        foreach (Storage::files('public/profile-photos') as $f) {
            $normalized = str_replace('\\', '/', $f);
            $base = basename($normalized);
            if (preg_match('/^u-' . preg_quote((string) $user->id, '/') . '\.[a-zA-Z0-9]+$/', $base)) {
                $abs = storage_path('app/' . $f);
                $mime = function_exists('mime_content_type') ? (mime_content_type($abs) ?: 'image/png') : 'image/png';
                return response()->file($abs, [
                    'Content-Type' => $mime,
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                ]);
            }
        }

        // Cek juga di public/uploads/avatars
        $publicAvatarDir = public_path('uploads/avatars');
        foreach (glob($publicAvatarDir . DIRECTORY_SEPARATOR . 'u-' . $user->id . '.*') ?: [] as $found) {
            $mime = function_exists('mime_content_type') ? (mime_content_type($found) ?: 'image/png') : 'image/png';
            return response()->file($found, [
                'Content-Type' => $mime,
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]);
        }

        return response($this->defaultAvatarSvg($initial), 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    private function defaultAvatarSvg(string $initial): string
    {
        $i = htmlspecialchars($initial, ENT_QUOTES, 'UTF-8');
        return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#111" />
      <stop offset="100%" stop-color="#333" />
    </linearGradient>
  </defs>
  <rect width="100%" height="100%" fill="url(#g)" />
  <text x="50%" y="55%" text-anchor="middle" dominant-baseline="middle" fill="#fff" font-family="Arial, sans-serif" font-size="96">$i</text>
</svg>
SVG;
    }
}
