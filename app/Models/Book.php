<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Buku
 *
 * Field isian: category_id, title, author (opsional), synopsis.
 */
class Book extends Model
{
  use HasFactory;

  protected $fillable = ["category_id", "title", "author", "synopsis"];

  public function category(): BelongsTo
  {
    return $this->belongsTo(Category::class);
  }

  public function borrowings(): HasMany
  {
    return $this->hasMany(Borrowing::class);
  }
}
