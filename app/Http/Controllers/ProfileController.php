<?php

namespace App\Http\Controllers;

use App\Models\User;
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
                $ext = strtolower($file->getClientOriginalExtension() ?: 'png');
                $filename = 'u-' . $user->id . '.' . $ext;

                $storageDir = storage_path('app/public/profile-photos');
                if (!is_dir($storageDir)) {
                    @mkdir($storageDir, 0775, true);
                }
                $publicAvatarDir = public_path('uploads/avatars');
                if (!is_dir($publicAvatarDir)) {
                    @mkdir($publicAvatarDir, 0777, true);
                }

                foreach (glob(storage_path('app/public/profile-photos/u-' . $user->id . '.*')) ?: [] as $old) {
                    @unlink($old);
                }
                foreach (glob($publicAvatarDir . DIRECTORY_SEPARATOR . 'u-' . $user->id . '.*') ?: [] as $old) {
                    @unlink($old);
                }

                Storage::disk('public')->putFileAs('profile-photos', $file, $filename);
                $src = $storageDir . DIRECTORY_SEPARATOR . $filename;
                @copy($src, $publicAvatarDir . DIRECTORY_SEPARATOR . $filename);

                if ($hasPhoto) {
                    $user->profile_photo_path = 'uploads/avatars/' . $filename;
                }

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
    public function photo(Request $request, ?User $subject = null)
    {
        $user = $subject ?? $request->user();
        if (! $user) {
            return response($this->defaultAvatarSvg('U'), 200)->header('Content-Type', 'image/svg+xml');
        }

        $initial = strtoupper(substr((string) ($user->name ?? 'U'), 0, 1));
        $path = str_replace('\\', '/', (string) ($user->profile_photo_path ?? ''));

        if ($path !== '') {
            $candidates = [
                public_path(ltrim($path, '/')),
                storage_path('app/public/' . ltrim($path, '/')),
            ];
            foreach ($candidates as $candidate) {
                if (is_file($candidate)) {
                    $mime = function_exists('mime_content_type') ? (mime_content_type($candidate) ?: 'image/png') : 'image/png';
                    return response()->file($candidate, [
                        'Content-Type' => $mime,
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    ]);
                }
            }
        }

        // Fallback: cari file pola u-<id>.* di storage
        $fallbackLocal = glob(storage_path('app/public/profile-photos/u-' . $user->id . '.*')) ?: [];
        if (!empty($fallbackLocal)) {
            $abs = $fallbackLocal[0];
            $mime = function_exists('mime_content_type') ? (mime_content_type($abs) ?: 'image/png') : 'image/png';
            return response()->file($abs, [
                'Content-Type' => $mime,
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]);
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
