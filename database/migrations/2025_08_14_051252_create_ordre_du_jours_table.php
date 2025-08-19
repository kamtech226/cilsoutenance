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
      Schema::create('ordre_du_jours', function (Blueprint $table) {
    $table->engine = 'InnoDB';

    $table->uuid('id')->primary();

    // 1–1 vers la table des sessions (adapte le nom si tu utilises "training_sessions")
    $table->foreignUuid('session_id')
      ->unique()
      ->constrained('training_sessions')   // <-- ou 'training_sessions' si c'est ton nom de table
      ->cascadeOnDelete();

    $table->unsignedInteger('version')->default(1);
    $table->timestamp('date_validation')->nullable();

    // 🔴 clé étrangère vers users.id (BIGINT UNSIGNED) + SET NULL ⇒ colonne nullable
    $table->foreignUuid('valide_par')
      ->nullable()
      ->constrained('users')
      ->nullOnDelete();

    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordre_du_jours');
    }
};
