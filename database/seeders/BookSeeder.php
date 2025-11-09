<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
  public function run(): void
  {
    $data = [
      "Fiction" => [
        ["To Kill a Mockingbird", "Harper Lee", "A novel about racial injustice and moral growth."],
        ["1984", "George Orwell", "A dystopian society under total surveillance."],
        ["Pride and Prejudice", "Jane Austen", "Romance, class, and wit in Regency England."],
        ["The Great Gatsby", "F. Scott Fitzgerald", "Wealth, love, and the American Dream."],
      ],
      "Science" => [
        ["A Brief History of Time", "Stephen Hawking", "Cosmology explained for the curious mind."],
        ["The Origin of Species", "Charles Darwin", "Foundational work on evolution."],
        ["Cosmos", "Carl Sagan", "A journey through the universe and science."],
      ],
      "Technology" => [
        ["The Innovators", "Walter Isaacson", "The people who built the digital revolution."],
        ["Hackers", "Steven Levy", "Heroes of the computer revolution."],
        ["Algorithms to Live By", "Brian Christian", "Applying algorithms to everyday life."],
      ],
      "History" => [
        ["Sapiens", "Yuval Noah Harari", "A brief history of humankind."],
        ["The Guns of August", "Barbara Tuchman", "The outbreak of World War I."],
      ],
      "Philosophy" => [
        ["The Republic", "Plato", "Plato’s ideas on justice and the state."],
        ["Meditations", "Marcus Aurelius", "Stoic wisdom from Marcus Aurelius."],
      ],
      "Business" => [
        ["Good to Great", "Jim Collins", "How companies make the leap."],
        ["The Lean Startup", "Eric Ries", "Entrepreneurship through validated learning."],
      ],
    ];

    foreach ($data as $categoryName => $books) {
      $category = Category::firstWhere("name", $categoryName);
      if (!$category) {
        continue;
      }
      foreach ($books as [$title, $author, $synopsis]) {
        Book::updateOrCreate(
          [
            "category_id" => $category->id,
            "title" => $title,
          ],
          [
            "author" => $author,
            "synopsis" => $synopsis,
          ],
        );
      }
    }
  }
}
