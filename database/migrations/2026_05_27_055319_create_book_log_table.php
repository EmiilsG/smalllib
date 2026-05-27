<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->string('action'); // create, borrow, return, update, delete
            $table->integer('old_available_copies')->nullable();
            $table->integer('new_available_copies')->nullable();
            $table->integer('old_total_copies')->nullable();
            $table->integer('new_total_copies')->nullable();
            $table->timestamps();

            $table->index('book_id');
            $table->index('action');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_log');
    }
};
