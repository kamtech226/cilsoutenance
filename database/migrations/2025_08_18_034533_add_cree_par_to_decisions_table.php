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
    Schema::table('decisions', function (Blueprint $table) {
        // place-la où tu veux; after() est optionnel
        $table->foreignUuid('cree_par')
              ->after('session_id')
              ->constrained('users')
              ->cascadeOnDelete();
    });
}

public function down(): void
{
    Schema::table('decisions', function (Blueprint $table) {
        $table->dropForeign(['cree_par']);
        $table->dropColumn('cree_par');
    });
}

};
