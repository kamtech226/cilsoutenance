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
    Schema::create('participations', function (Blueprint $t) {
            $t->engine = 'InnoDB';

            // PK UUID pour l’entité d’association (ou tu peux choisir un PK composite)
            $t->uuid('id')->primary();

            // FK UUID vers la table des sessions
            $t->foreignUuid('session_id')
              ->constrained('training_sessions')           // <-- ou 'training_sessions' si c'est ton nom
              ->cascadeOnDelete();

            // 🔴 FK BIGINT vers users(id)
            $t->foreignUuid('user_id')
              ->constrained('users')
              ->cascadeOnDelete();

            $t->string('role_dans_session')->nullable();
            $t->boolean('presence')->default(false);
            $t->text('signature')->nullable();

            // Un user ne doit apparaître qu'une fois par session
            $t->unique(['session_id', 'user_id']);

            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participations');
    }
};
