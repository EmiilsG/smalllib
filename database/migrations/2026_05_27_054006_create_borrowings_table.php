<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reader_id')->constrained()->cascadeOnDelete();
            $table->date('borrowed_at');
            $table->date('due_at');
            $table->date('returned_at')->nullable();
            $table->timestamps();

            $table->index('borrowed_at');
            $table->index('due_at');
            $table->index('returned_at');
            $table->index(['reader_id', 'returned_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
