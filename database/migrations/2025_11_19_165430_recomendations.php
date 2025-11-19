<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->enum('recommended_formation', ['4-3-3', '3-5-2', '3-4-3', '4-2-3-1', '5-4-1']);
            $table->decimal('confidence_score', 5, 4); // e.g., 0.8500
            $table->timestamp('generated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recommendations');
    }
};