<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {Schema::create('decisions', function (Blueprint $table) {
  $table->uuid('id')->primary();
  $table->string('type');
  $table->text('contenu');
  $table->timestamp('date_decision')->useCurrent();
  $table->foreignUuid('session_id')->nullable()->constrained('training_sessions')->cascadeOnDelete();
  $table->foreignUuid('point_id')->nullable()->constrained('point_odjs')->cascadeOnDelete();
  $table->foreignUuid('cree_par')->constrained('users')->cascadeOnDelete();
  $table->timestamps();
});


    
// Exclusivité: sur point OU sur session
DB::statement("
  ALTER TABLE decisions
  ADD CONSTRAINT chk_decision_scope
  CHECK (
    (session_id IS NOT NULL AND point_id IS NULL) OR
    (session_id IS NULL AND point_id IS NOT NULL)
  )
");}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decisions');
    }
};
