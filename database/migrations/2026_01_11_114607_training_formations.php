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
        Schema::create('training_formations', function (Blueprint $table) {
            $table->id();

            // Feature (hasil rata-rata tim)
            $table->float('pace_avg');
            $table->float('shooting_avg');
            $table->float('passing_avg');
            $table->float('dribbling_avg');
            $table->float('defending_avg');
            $table->float('physical_avg');

            // Label / kelas
            $table->string('formation', 10);

            // Metadata
            $table->enum('source', ['baseline', 'user'])->default('user');
            $table->float('confidence')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_formations');
    }
};
