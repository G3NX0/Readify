<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Peminjaman
 *
 * Menghitung denda berdasarkan due_date vs returned_at/now.
 */
class Borrowing extends Model
{
  use HasFactory;

  protected $fillable = [
    "book_id",
    "borrower_name",
    "borrowed_at",
    "due_date",
    "returned_at",
    "fine_amount",
  ];

  protected $casts = [
    "borrowed_at" => "date",
    "due_date" => "date",
    "returned_at" => "date",
  ];

  public function book(): BelongsTo
  {
    return $this->belongsTo(Book::class);
  }

  /**
   * Hitung denda keterlambatan.
   *
   * @param int|null $ratePerDay tarif per hari (Rp)
   */
  public function calculateFine(?int $ratePerDay = null): int
  {
    $ratePerDay = $ratePerDay ?? (int) config("borrowing.fine_per_day", 10000);
    $due = $this->due_date ? Carbon::parse($this->due_date) : null;
    if (!$due) {
      return 0;
    }

    $returned = $this->returned_at ? Carbon::parse($this->returned_at) : Carbon::now();
    if ($returned->lessThanOrEqualTo($due)) {
      return 0;
    }

    $days = $due->diffInDays($returned);
    return $days * $ratePerDay;
  }
}
