<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

/**
 * Seeder Kategori
 *
 * Santuy tapi jelas: pastikan kategori dasar tersedia.
 */
class CategorySeeder extends Seeder
{
  public function run(): void
  {
    $names = [
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

    foreach ($names as $name) {
      Category::firstOrCreate(["name" => $name]);
    }
  }
}
