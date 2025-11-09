<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create("borrowings", function (Blueprint $table) {
      $table->id();
      $table->foreignId("book_id")->constrained("books")->cascadeOnDelete();
      $table->string("borrower_name");
      $table->date("borrowed_at");
      $table->date("due_date");
      $table->date("returned_at")->nullable();
      $table->unsignedInteger("fine_amount")->default(0); // rupiah
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists("borrowings");
  }
};
