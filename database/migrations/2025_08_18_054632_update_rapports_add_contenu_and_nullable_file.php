<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rapports', function (Blueprint $t) {
            // rendre le fichier optionnel
            $t->string('fichier_url')->nullable()->change();

            // ajouter un contenu texte (optionnel) si tu veux saisir un résumé
            if (!Schema::hasColumn('rapports', 'contenu')) {
                $t->text('contenu')->nullable()->after('titre');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rapports', function (Blueprint $t) {
            // revenir en arrière si besoin
            if (Schema::hasColumn('rapports', 'contenu')) {
                $t->dropColumn('contenu');
            }
            $t->string('fichier_url')->nullable(false)->change();
        });
    }
};
