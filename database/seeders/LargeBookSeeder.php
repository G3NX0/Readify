<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LargeBookSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Ensure base categories exist
        $categoryNames = ['Fiction','Science','Technology','History','Philosophy','Business'];
        foreach ($categoryNames as $name) {
            Category::firstOrCreate(['name' => $name]);
        }

        $blueprints = [
            'Fiction' => [
                'keywords' => ['love','war','memory','dreams','secret','midnight','ocean','forest','city','winter','summer','letters','ghost','river','island','garden','shadow','light','storm','destiny','journey','library','chronicle','echo','mirror','whisper','promise','silver','ember','maze'],
                'templates' => [
                    'A moving tale about :kw and the choices that define us.',
                    'A gripping story where :kw collides with fate in unexpected ways.',
                    'A heartfelt novel exploring :kw, family, and belonging.',
                    'An intimate portrait of loss and :kw, set against a changing world.',
                ],
            ],
            'Science' => [
                'keywords' => ['cosmology','evolution','quantum physics','neuroscience','genetics','climate','ecology','astronomy','chemistry','mathematics','geology','time','black holes','consciousness','complexity','information','energy','materials','systems','biophysics'],
                'templates' => [
                    'A lucid introduction to :kw for curious minds.',
                    'Explores how :kw shapes life and the universe.',
                    'Clear, engaging chapters demystify :kw with real examples.',
                    'A field guide to the big ideas behind :kw.',
                ],
            ],
            'Technology' => [
                'keywords' => ['algorithms','cryptography','networks','AI','machine learning','software design','databases','distributed systems','UX','web','mobile','cloud','security','robotics','hardware','product','startups','blockchain','VR','devops'],
                'templates' => [
                    'Practical insights on :kw for builders and thinkers.',
                    'Real-world patterns and pitfalls when working with :kw.',
                    'A concise roadmap to mastering modern :kw.',
                    'Stories from the frontier of :kw and innovation.',
                ],
            ],
            'History' => [
                'keywords' => ['empires','revolutions','trade routes','ancient cities','medieval courts','exploration','colonialism','world wars','civilizations','silk road','renaissance','industrial age','migration','archaeology','monarchs','republics','reform','conflict','maps','biographies'],
                'templates' => [
                    'A vivid chronicle of :kw that shaped today\'s world.',
                    'Lives, letters, and landscapes behind :kw.',
                    'Following the threads of :kw across centuries.',
                    'A compact history that makes :kw come alive.',
                ],
            ],
            'Philosophy' => [
                'keywords' => ['ethics','reason','existence','time','self','mind','language','virtue','justice','beauty','truth','freedom','meaning','logic','skepticism','stoicism','phenomenology','metaphysics','epistemology','aesthetics'],
                'templates' => [
                    'A friendly guide to :kw and everyday life.',
                    'Classic ideas reimagined: :kw for the modern reader.',
                    'Short chapters unpack :kw with clarity and care.',
                    'A conversation across ages about :kw.',
                ],
            ],
            'Business' => [
                'keywords' => ['strategy','leadership','culture','teams','innovation','finance','marketing','sales','negotiation','operations','startups','product','brand','analytics','growth','creativity','management','resilience','customers','execution'],
                'templates' => [
                    'Field-tested lessons on :kw, minus the fluff.',
                    'How great companies turn :kw into advantage.',
                    'Simple playbooks to level up your :kw.',
                    'Real stories that illuminate :kw at work.',
                ],
            ],
        ];

        $targetPerCategory = 120; // 100+ as requested

        foreach (Category::orderBy('name')->get() as $category) {
            $bp = $blueprints[$category->name] ?? $blueprints['Fiction'];
            $existing = Book::where('category_id', $category->id)->count();
            $toCreate = max(0, $targetPerCategory - $existing);
            if ($toCreate <= 0) continue;

            $used = Book::where('category_id', $category->id)->pluck('title')->map(fn($t) => Str::lower($t))->all();
            $used = array_flip($used);

            for ($i = 0; $i < $toCreate; $i++) {
                // Compose a plausible title
                $k1 = $faker->randomElement($bp['keywords']);
                $k2 = $faker->randomElement($bp['keywords']);
                while ($k2 === $k1 && count($bp['keywords']) > 1) {
                    $k2 = $faker->randomElement($bp['keywords']);
                }

                $patterns = [
                    'The ' . Str::title($k1),
                    Str::title($k1) . ' and ' . Str::title($k2),
                    'Notes on ' . Str::title($k1),
                    'Beyond ' . Str::title($k1),
                    'Inside ' . Str::title($k1),
                    'The Art of ' . Str::title($k1),
                    'A Short History of ' . Str::title($k1),
                ];
                $title = $faker->randomElement($patterns);
                $baseTitle = $title;
                $suffix = 2;
                while (isset($used[Str::lower($title)])) {
                    $title = $baseTitle . ' (' . $suffix . ')';
                    $suffix++;
                }
                $used[Str::lower($title)] = true;

                // Mini synopsis
                $tpl = $faker->randomElement($bp['templates']);
                $kw = $faker->randomElement($bp['keywords']);
                $synopsis = Str::ucfirst(str_replace(':kw', $kw, $tpl));

                Book::create([
                    'category_id' => $category->id,
                    'title' => $title,
                    'author' => $faker->name(),
                    'synopsis' => $synopsis,
                ]);
            }
        }
    }
}

