<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('position', 20); // GK, DEF, MID, FW
            $table->unsignedTinyInteger('pace')->nullable();
            $table->unsignedTinyInteger('shooting')->nullable();
            $table->unsignedTinyInteger('passing')->nullable();
            $table->unsignedTinyInteger('dribbling')->nullable();
            $table->unsignedTinyInteger('defending')->nullable();
            $table->unsignedTinyInteger('physical')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('players');
    }
};