<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::create('amendements', function (Blueprint $t) {
    $t->engine = 'InnoDB';

    $t->uuid('id')->primary();

    // FK UUID vers point_odjs
    $t->foreignUuid('point_id')
      ->constrained('point_odjs')
      ->cascadeOnDelete();

    $t->text('contenu');
    $t->timestamp('propose_le')->useCurrent();

    // 🔴 users.id = BIGINT UNSIGNED → foreignId()
    // 🔴 SET NULL ⇒ la colonne doit être nullable()
    $t->foreignUuid('propose_par')
      ->nullable()
      ->constrained('users')
      ->nullOnDelete();

    $t->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amendements');
    }
};
