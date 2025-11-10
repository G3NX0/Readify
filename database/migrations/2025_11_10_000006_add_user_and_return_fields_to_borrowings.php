<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            if (!Schema::hasColumn('borrowings', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->after('id');
            }
            if (!Schema::hasColumn('borrowings', 'return_requested_at')) {
                $table->timestamp('return_requested_at')->nullable()->after('due_date');
            }
            if (!Schema::hasColumn('borrowings', 'return_note')) {
                $table->string('return_note')->nullable()->after('return_requested_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            if (Schema::hasColumn('borrowings', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
            if (Schema::hasColumn('borrowings', 'return_requested_at')) {
                $table->dropColumn('return_requested_at');
            }
            if (Schema::hasColumn('borrowings', 'return_note')) {
                $table->dropColumn('return_note');
            }
        });
    }
};

