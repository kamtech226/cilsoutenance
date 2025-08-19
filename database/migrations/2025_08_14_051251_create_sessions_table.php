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
Schema::create('training_sessions', function (Blueprint $table) {
    $table->uuid('id')->primary();            // <-- UUID
    $table->string('code')->unique();
    $table->string('theme');
    $table->date('date_session');
    $table->string('statut');
    $table->string('lieu');
    $table->foreignUuid('cree_par')->constrained('users')->cascadeOnDelete();
    $table->timestamps();
});



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
