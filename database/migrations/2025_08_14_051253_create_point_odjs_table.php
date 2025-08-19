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
      Schema::create('point_odjs', function (Blueprint $table) {
    $table->engine = 'InnoDB';

    $table->uuid('id')->primary();

    // -> Ordre du Jour (UUID)
    $table->foreignUuid('odj_id')
      ->constrained('ordre_du_jours')
      ->cascadeOnDelete();

    $table->string('titre');
    $table->text('description')->nullable();
    $table->unsignedTinyInteger('priorite')->default(3);
    $table->string('statut', 20)->default(\App\Enums\StatutPoint::Propose->value);
    $table->timestamp('propose_le')->useCurrent();

    // 🔴 Proposé par un utilisateur (users.id = BIGINT UNSIGNED)
    $table->foreignUuid('propose_par')
      ->constrained('users'); // si tu veux: ->cascadeOnDelete()

    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_odjs');
    }
};
