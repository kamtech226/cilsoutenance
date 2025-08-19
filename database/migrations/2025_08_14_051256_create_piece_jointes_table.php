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
      Schema::create('piece_jointes', function (Blueprint $table) {
  $table->uuid('id')->primary();
  $table->foreignUuid('point_id')->constrained('point_odjs')->cascadeOnDelete(); // 1–N recommandé
  $table->string('nom_fichier');
  $table->string('url');
  $table->string('type_mime');
  $table->unsignedInteger('taille_ko');
  $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piece_jointes');
    }
};
