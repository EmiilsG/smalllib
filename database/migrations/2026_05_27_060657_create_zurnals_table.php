<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zurnals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gramatas_id');
            $table->string('darbiba');
            $table->integer('vecais_pieejams')->nullable();
            $table->integer('jaunais_pieejams')->nullable();
            $table->integer('vecais_kopa')->nullable();
            $table->integer('jaunais_kopa')->nullable();
            $table->timestamp('izmainits');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zurnals');
    }
};
