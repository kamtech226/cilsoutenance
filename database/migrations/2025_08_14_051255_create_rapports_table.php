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
       Schema::create('rapports', function (Blueprint $t) {
            $t->engine = 'InnoDB';

            $t->uuid('id')->primary();

            // FK UUID vers la table des sessions (adapte le nom si tu utilises "training_sessions")
            $t->foreignUuid('session_id')
              ->constrained('training_sessions')   // ou 'training_sessions' si c'est ton nom
              ->cascadeOnDelete();

            $t->string('titre');
            $t->longText('contenu')->nullable();
            $t->string('fichier_url');
            $t->timestamp('date_depot')->useCurrent();
            $t->string('statut'); // Enum côté modèle (StatutRapport)

            // 🔴 users.id = BIGINT UNSIGNED → foreignId()
            $t->foreignUuid('redige_par')
              ->constrained('users'); // (on peut ajouter ->cascadeOnDelete() si tu veux)

            // Validation par SG/Présidente possible → SET NULL ⇒ nullable()
            $t->foreignUuid('valide_par')
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
        Schema::dropIfExists('rapports');
    }
};
