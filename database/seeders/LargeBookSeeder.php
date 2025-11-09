<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeder Buku Massal
 *
 * Generate judul+sinopsis yang wajar (bukan copy‑paste) per kategori,
 * melengkapi sampai 120 buku/kategori, dan memperkaya sinopsis yang ada.
 */
class LargeBookSeeder extends Seeder
{
  public function run(): void
  {
    $faker = \Faker\Factory::create();

    // Helper: extract focus tokens from a title
    $extractTokens = function (string $title): array {
      $stop = [
        "the",
        "and",
        "of",
        "a",
        "an",
        "to",
        "in",
        "on",
        "for",
        "with",
        "without",
        "from",
        "by",
        "about",
        "into",
        "over",
        "under",
        "between",
        "beyond",
        "within",
        "across",
        "at",
        "as",
        "is",
        "are",
        "be",
        "being",
        "been",
        "or",
        "not",
        "it",
        "its",
        "their",
        "his",
        "her",
      ];
      $t = \Illuminate\Support\Str::of($title)
        ->lower()
        ->replaceMatches("/[:.,;!?()\[\]{}\-—_]+/", " ")
        ->explode(" ")
        ->filter(fn($w) => \Illuminate\Support\Str::length($w) >= 3 && !in_array($w, $stop))
        ->values();
      return $t->slice(0, 3)->all();
    };

    // Helper to craft richer, title-aware, multi-sentence synopses per category
    $makeSynopsis = function (
      string $categoryName,
      string $title,
      array $keywords,
      $faker,
      array $focusTokens = [],
    ): string {
      $pick = function () use ($keywords, $faker, $focusTokens) {
        if (!empty($focusTokens)) {
          return \Illuminate\Support\Str::lower((string) $focusTokens[array_rand($focusTokens)]);
        }
        return \Illuminate\Support\Str::lower((string) $faker->randomElement($keywords));
      };
      $k1 = $pick();
      $k2 = \Illuminate\Support\Str::lower(
        (string) ($focusTokens[1] ?? $faker->randomElement($keywords)),
      );
      $k3 = \Illuminate\Support\Str::lower(
        (string) ($focusTokens[2] ?? $faker->randomElement($keywords)),
      );

      switch ($categoryName) {
        case "Fiction":
          return sprintf(
            'In "%s", quiet choices echo for years as the characters wrestle with %s and the stories they tell themselves. Chapters move with a measured pace—letters slipped under doors, streets after rain, a room that never looks the same twice. The narration trusts the reader, leaving just enough unsaid to breathe. By the final pages, memory and %s converge, asking what it means to keep a promise when the world refuses to hold still.',
            $title,
            $k1,
            $k2,
          );
        case "Science":
          return sprintf(
            '"%s" offers a clear tour through %s and %s, connecting everyday phenomena to first principles. Intuitive explanations sit beside simple experiments and compact diagrams that reward curiosity. A brief glossary and margin notes make technical language approachable. Readers leave with a working toolkit for reasoning about %s without hand-waving.',
            $title,
            $k1,
            $k2,
            $k3,
          );
        case "Technology":
          return sprintf(
            'Built for practitioners, "%s" moves from whiteboard to production: %s, %s, and the trade‑offs in between. Case studies unpack failure modes, incident reviews, and debugging strategies that scale. Checklists, code archetypes, and diagrams turn patterns into muscle memory. The goal is a pragmatic workflow for reasoning about %s under pressure.',
            $title,
            $k1,
            $k2,
            $k3,
          );
        case "History":
          return sprintf(
            '"%s" threads letters, maps, and first‑hand accounts into a single journey through %s. It shows how policy, geography, and chance collided—and how ordinary lives absorbed the shock. Concise chapters keep the pace while letting the texture of %s remain in view. An epilogue reflects on what archives forget as much as what they preserve.',
            $title,
            $k1,
            $k2,
          );
        case "Philosophy":
          return sprintf(
            'A compact companion to thinking in public, "%s" maps classic arguments around %s with unusual clarity. Each section ends with questions and everyday examples, turning abstractions into working tools. Sidebars trace how terms shifted across eras. Read it slowly—the exercises on %s and choice reward a second pass.',
            $title,
            $k1,
            $k2,
          );
        case "Business":
          return sprintf(
            '"%s" distills field‑tested notes on turning %s into decisions, metrics, and weekly rituals. You get small playbooks, failure stories, and meeting templates that travel well between teams. Expect fewer slogans and more trade‑offs; the aim is repeatable progress. Appendices cover dashboards and post‑mortems that actually get used in %s.',
            $title,
            $k1,
            $k2,
          );
        case "Biography":
          $person = $focusTokens[0] ?? $faker->randomElement($keywords);
          return sprintf(
            '"%s" follows %s from early sparks to lasting influence. Letters, interviews, and notebooks reveal the craft behind public victories and the routines that sustained them. The middle chapters linger on setbacks—the revisions, rivalries, and near‑misses that reshaped their path. A closing essay considers legacy: how ideas about %s continue to ripple outward.',
            $title,
            $person,
            $k1,
          );
        case "Art":
          return sprintf(
            'Equal parts studio and gallery, "%s" decodes %s through materials, light, and composition. Annotated examples show how to see structure before style. Movements and methods appear as tools, not trivia. By the end, you will have habits for noticing %s in the world—and in your own work.',
            $title,
            $k1,
            $k2,
          );
        case "Health":
          return sprintf(
            'Practical and calm, "%s" builds sustainable routines around %s without hype. Short chapters cover sleep, stress, and nutrition with simple trackers to measure change. Templates help you plan weeks, not perfect days. The focus is progress you can keep while protecting %s when life gets noisy.',
            $title,
            $k1,
            $k2,
          );
        case "Travel":
          return sprintf(
            'A slow guide, "%s" strings together food, routes, and quiet corners across %s. Local notes and small maps anchor each chapter, with respectful customs and a bit of language. Designed for wandering, it helps you collect moments that add up to a story worth retelling about %s.',
            $title,
            $k1,
            $k2,
          );
        case "Cooking":
          return sprintf(
            'Flavor‑first and forgiving, "%s" teaches fundamentals before recipes: heat, timing, and patience. From %s to pantry improvisations, each method explains the why behind the steps. A handful of master techniques scale to %s so dinner feels calm even on busy nights.',
            $title,
            $k1,
            $k2,
          );
        case "Self-Help":
          return sprintf(
            'Built around reflection pages and weekly reviews, "%s" offers tiny, actionable ideas for better %s. Scripts and checklists reduce friction so good habits survive bad weeks. The approach favors small systems you can maintain over hacks. By the end, you\'ll have clear frameworks to protect %s when priorities compete.',
            $title,
            $k1,
            $k2,
          );
        case "Poetry":
          return sprintf(
            'Short free‑verse sequences in "%s" return to images of %s and %s. The lines turn on light, time, and breath, leaving space for the reader to finish the thought. Read one page at a time; the pauses do as much work as the words.',
            $title,
            $k1,
            $k2,
          );
        default:
          return sprintf(
            '"%s" is a friendly introduction to %s with practical examples. Short chapters and checklists keep the focus on use, not jargon. By the end, you\'ll have language and tools to reason about %s with confidence.',
            $title,
            $k1,
            $k2,
          );
      }
    };

    // Ensure base categories exist
    $categoryNames = [
      "Fiction",
      "Science",
      "Technology",
      "History",
      "Philosophy",
      "Business",
      "Biography",
      "Art",
      "Health",
      "Travel",
      "Cooking",
      "Self-Help",
      "Poetry",
    ];
    foreach ($categoryNames as $name) {
      Category::firstOrCreate(["name" => $name]);
    }

    $blueprints = [
      "Fiction" => [
        "keywords" => [
          "love",
          "war",
          "memory",
          "dreams",
          "secret",
          "midnight",
          "ocean",
          "forest",
          "city",
          "winter",
          "summer",
          "letters",
          "ghost",
          "river",
          "island",
          "garden",
          "shadow",
          "light",
          "storm",
          "destiny",
          "journey",
          "library",
          "chronicle",
          "echo",
          "mirror",
          "whisper",
          "promise",
          "silver",
          "ember",
          "maze",
        ],
        "templates" => [
          "A moving tale about :kw and the choices that define us.",
          "A gripping story where :kw collides with fate in unexpected ways.",
          "A heartfelt novel exploring :kw, family, and belonging.",
          "An intimate portrait of loss and :kw, set against a changing world.",
        ],
      ],
      "Science" => [
        "keywords" => [
          "cosmology",
          "evolution",
          "quantum physics",
          "neuroscience",
          "genetics",
          "climate",
          "ecology",
          "astronomy",
          "chemistry",
          "mathematics",
          "geology",
          "time",
          "black holes",
          "consciousness",
          "complexity",
          "information",
          "energy",
          "materials",
          "systems",
          "biophysics",
        ],
        "templates" => [
          "A lucid introduction to :kw for curious minds.",
          "Explores how :kw shapes life and the universe.",
          "Clear, engaging chapters demystify :kw with real examples.",
          "A field guide to the big ideas behind :kw.",
        ],
      ],
      "Technology" => [
        "keywords" => [
          "algorithms",
          "cryptography",
          "networks",
          "AI",
          "machine learning",
          "software design",
          "databases",
          "distributed systems",
          "UX",
          "web",
          "mobile",
          "cloud",
          "security",
          "robotics",
          "hardware",
          "product",
          "startups",
          "blockchain",
          "VR",
          "devops",
        ],
        "templates" => [
          "Practical insights on :kw for builders and thinkers.",
          "Real-world patterns and pitfalls when working with :kw.",
          "A concise roadmap to mastering modern :kw.",
          "Stories from the frontier of :kw and innovation.",
        ],
      ],
      "History" => [
        "keywords" => [
          "empires",
          "revolutions",
          "trade routes",
          "ancient cities",
          "medieval courts",
          "exploration",
          "colonialism",
          "world wars",
          "civilizations",
          "silk road",
          "renaissance",
          "industrial age",
          "migration",
          "archaeology",
          "monarchs",
          "republics",
          "reform",
          "conflict",
          "maps",
          "biographies",
        ],
        "templates" => [
          'A vivid chronicle of :kw that shaped today\'s world.',
          "Lives, letters, and landscapes behind :kw.",
          "Following the threads of :kw across centuries.",
          "A compact history that makes :kw come alive.",
        ],
      ],
      "Philosophy" => [
        "keywords" => [
          "ethics",
          "reason",
          "existence",
          "time",
          "self",
          "mind",
          "language",
          "virtue",
          "justice",
          "beauty",
          "truth",
          "freedom",
          "meaning",
          "logic",
          "skepticism",
          "stoicism",
          "phenomenology",
          "metaphysics",
          "epistemology",
          "aesthetics",
        ],
        "templates" => [
          "A friendly guide to :kw and everyday life.",
          "Classic ideas reimagined: :kw for the modern reader.",
          "Short chapters unpack :kw with clarity and care.",
          "A conversation across ages about :kw.",
        ],
      ],
      "Business" => [
        "keywords" => [
          "strategy",
          "leadership",
          "culture",
          "teams",
          "innovation",
          "finance",
          "marketing",
          "sales",
          "negotiation",
          "operations",
          "startups",
          "product",
          "brand",
          "analytics",
          "growth",
          "creativity",
          "management",
          "resilience",
          "customers",
          "execution",
        ],
        "templates" => [
          "Field-tested lessons on :kw, minus the fluff.",
          "How great companies turn :kw into advantage.",
          "Simple playbooks to level up your :kw.",
          "Real stories that illuminate :kw at work.",
        ],
      ],
      "Biography" => [
        // A selection of well-known figures; used as tokens for titles/synopses
        "keywords" => [
          "Ada Lovelace",
          "Alan Turing",
          "Albert Einstein",
          "Marie Curie",
          "Isaac Newton",
          "Leonardo da Vinci",
          "Galileo Galilei",
          "Nikola Tesla",
          "Thomas Edison",
          "Stephen Hawking",
          "Katherine Johnson",
          "Grace Hopper",
          "Rosalind Franklin",
          "Charles Darwin",
          "Niels Bohr",
          "Johannes Kepler",
          "Michael Faraday",
          "Max Planck",
          "Richard Feynman",
          "Carl Sagan",
          "Neil Armstrong",
          "Amelia Earhart",
          "Sally Ride",
          "Buzz Aldrin",
          "Ernest Shackleton",
          "Alexander the Great",
          "Julius Caesar",
          "Cleopatra",
          "Genghis Khan",
          "Napoleon Bonaparte",
          "Winston Churchill",
          "Nelson Mandela",
          "Mahatma Gandhi",
          "Martin Luther King Jr",
          "Abraham Lincoln",
          "George Washington",
          "Theodore Roosevelt",
          "Franklin D. Roosevelt",
          "Queen Elizabeth I",
          "Queen Victoria",
          "Catherine the Great",
          "Joan of Arc",
          "Florence Nightingale",
          "Vincent van Gogh",
          "Pablo Picasso",
          "Frida Kahlo",
          "Claude Monet",
          "Ludwig van Beethoven",
          "Wolfgang Amadeus Mozart",
          "Johann Sebastian Bach",
          "Igor Stravinsky",
          "William Shakespeare",
          "Jane Austen",
          "Charles Dickens",
          "Leo Tolstoy",
          "Mark Twain",
          "Homer",
          "Dante Alighieri",
          "Miguel de Cervantes",
          "Fyodor Dostoevsky",
          "Virginia Woolf",
          "Ernest Hemingway",
          "George Orwell",
          "Haruki Murakami",
          "J.R.R. Tolkien",
          "C.S. Lewis",
          "Agatha Christie",
          "Arthur Conan Doyle",
          "J.K. Rowling",
          "Chinua Achebe",
          "Gabriel Garcia Marquez",
          "James Joyce",
          "T.S. Eliot",
          "Maya Angelou",
          "Emily Dickinson",
          "Rabindranath Tagore",
          "Rumi",
          "Confucius",
          "Socrates",
          "Plato",
          "Aristotle",
          "Immanuel Kant",
          "Friedrich Nietzsche",
          "Søren Kierkegaard",
          "Simone de Beauvoir",
          "Hannah Arendt",
          "Bertrand Russell",
          "Sun Tzu",
          "Murasaki Shikibu",
          "Akira Kurosawa",
          "Hayao Miyazaki",
          "Yayoi Kusama",
          "Steve Jobs",
          "Bill Gates",
          "Elon Musk",
          "Jeff Bezos",
          "Walt Disney",
          "Coco Chanel",
          "Malala Yousafzai",
          "Oprah Winfrey",
          "Serena Williams",
          "Usain Bolt",
          "Pelé",
          "Diego Maradona",
          "Lionel Messi",
          "Michael Jordan",
          "Roger Federer",
          "Sachin Tendulkar",
          "Cristiano Ronaldo",
          "LeBron James",
        ],
        "templates" => [
          "A concise biography of :kw and their impact.",
          "The life and legacy of :kw in brief.",
          "An accessible portrait of :kw, highlighting key moments.",
          "Tracing the journey of :kw from origins to legacy.",
        ],
      ],
      "Art" => [
        "keywords" => [
          "painting",
          "sculpture",
          "impressionism",
          "modern art",
          "renaissance",
          "photography",
          "architecture",
          "design",
          "museums",
          "color theory",
          "typography",
          "composition",
          "printmaking",
          "installation",
          "street art",
        ],
        "templates" => [
          "An illustrated introduction to :kw and its key ideas.",
          "How :kw shaped artists and movements across eras.",
          "A compact field guide to :kw with examples.",
          "Clear, visual lessons that demystify :kw.",
        ],
      ],
      "Health" => [
        "keywords" => [
          "nutrition",
          "fitness",
          "mental health",
          "sleep",
          "meditation",
          "yoga",
          "cardio",
          "strength",
          "habits",
          "longevity",
          "mobility",
          "recovery",
          "hydration",
          "mindfulness",
          "stress",
        ],
        "templates" => [
          "Simple, evidence-based notes on :kw for daily life.",
          "Build sustainable routines around :kw without overwhelm.",
          "Small habits and checklists to improve your :kw.",
          "A practical companion to better :kw.",
        ],
      ],
      "Travel" => [
        "keywords" => [
          "Japan",
          "Alps",
          "Bali",
          "Istanbul",
          "New York",
          "Paris",
          "Sahara",
          "Amazon",
          "Himalaya",
          "Silk Road",
          "Mediterranean",
          "Patagonia",
          "Iceland",
          "Rome",
          "Kyoto",
        ],
        "templates" => [
          "Essential highlights and quiet corners of :kw.",
          "A short guide to food, routes, and seasons in :kw.",
          "Local notes for slow travel through :kw.",
          "Maps, moments, and stories from :kw.",
        ],
      ],
      "Cooking" => [
        "keywords" => [
          "Italian",
          "French",
          "Japanese",
          "Indonesian",
          "baking",
          "vegetarian",
          "grilling",
          "sauces",
          "spices",
          "desserts",
          "noodles",
          "rice",
          "sourdough",
          "salads",
          "soups",
        ],
        "templates" => [
          "Comfort recipes and techniques for everyday :kw.",
          "A friendly primer on :kw with simple steps.",
          "Flavor-first cooking: pantry and method for :kw.",
          "Short, reliable recipes to master :kw at home.",
        ],
      ],
      "Self-Help" => [
        "keywords" => [
          "habits",
          "mindset",
          "productivity",
          "focus",
          "discipline",
          "communication",
          "confidence",
          "leadership",
          "creativity",
          "decision-making",
          "resilience",
          "emotion",
          "learning",
          "journaling",
          "systems",
        ],
        "templates" => [
          "Tiny, actionable ideas to improve your :kw.",
          "Tools and prompts to build better :kw every day.",
          "A pragmatic approach to :kw with real examples.",
          "Clear frameworks to think about :kw.",
        ],
      ],
      "Poetry" => [
        "keywords" => [
          "love",
          "night",
          "sea",
          "wind",
          "silence",
          "memory",
          "time",
          "rain",
          "light",
          "shadow",
          "city",
          "river",
          "garden",
          "stone",
          "winter",
        ],
        "templates" => [
          "Short verses on :kw and the spaces between.",
          "Whispers of :kw captured in brief lines.",
          "Images of :kw in simple, quiet poems.",
          "Moments of :kw gathered with care.",
        ],
      ],
    ];

    $targetPerCategory = 120; // 100+ as requested

    foreach (Category::orderBy("name")->get() as $category) {
      $bp = $blueprints[$category->name] ?? $blueprints["Fiction"];

      // 1) Enrich synopsis for ALL existing books in this category
      $existingBooks = Book::where("category_id", $category->id)->get();
      foreach ($existingBooks as $eb) {
        $focusTokens = $extractTokens((string) $eb->title);
        $eb->synopsis = $makeSynopsis(
          $category->name,
          (string) $eb->title,
          $bp["keywords"],
          $faker,
          $focusTokens,
        );
        $eb->save();
      }

      // 2) Create additional books until we reach the target per category
      $existingCount = $existingBooks->count();
      $toCreate = max(0, $targetPerCategory - $existingCount);
      if ($toCreate <= 0) {
        continue;
      }

      $used = $existingBooks->pluck("title")->map(fn($t) => Str::lower($t))->all();
      $used = array_flip($used);

      for ($i = 0; $i < $toCreate; $i++) {
        // Compose a plausible title
        $k1 = $faker->randomElement($bp["keywords"]);
        $k2 = $faker->randomElement($bp["keywords"]);
        while ($k2 === $k1 && count($bp["keywords"]) > 1) {
          $k2 = $faker->randomElement($bp["keywords"]);
        }

        // Category-specific title patterns
        if ($category->name === "Biography") {
          $patterns = [
            Str::title($k1) . ": A Biography",
            "The Life of " . Str::title($k1),
            Str::title($k1) . " and Their Times",
            "Conversations with " . Str::title($k1),
            "On " . Str::title($k1),
          ];
        } else {
          $patterns = [
            "The " . Str::title($k1),
            Str::title($k1) . " and " . Str::title($k2),
            "Notes on " . Str::title($k1),
            "Beyond " . Str::title($k1),
            "Inside " . Str::title($k1),
            "The Art of " . Str::title($k1),
            "A Short History of " . Str::title($k1),
          ];
        }
        $title = $faker->randomElement($patterns);
        $baseTitle = $title;
        $suffix = 2;
        while (isset($used[Str::lower($title)])) {
          $title = $baseTitle . " (" . $suffix . ")";
          $suffix++;
        }
        $used[Str::lower($title)] = true;

        // Richer, multi-sentence synopsis derived from the composed title
        $focusTokens = $extractTokens($title);
        $synopsis = $makeSynopsis($category->name, $title, $bp["keywords"], $faker, $focusTokens);

        Book::create([
          "category_id" => $category->id,
          "title" => $title,
          "author" => $faker->name(),
          "synopsis" => $synopsis,
        ]);
      }
    }
  }
}
