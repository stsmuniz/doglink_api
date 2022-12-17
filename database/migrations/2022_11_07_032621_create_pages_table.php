<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('primary_color', 7)->default('ffffff');
            $table->string('secondary_color', 7)->default('000000');
            $table->string('custom_url', 20);
            $table->string('title', 20)->nullable();
            $table->string('tagline', 80)->nullable();
            $table->string('theme')->default('default');
            $table->foreignId('user_id')->constrained();
            $table->string('background_color', 7)->default('ffffff');
            $table->string('text_color', 7)->default('000000');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
};
