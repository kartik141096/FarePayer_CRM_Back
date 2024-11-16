<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealPlaneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_plane', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Meal plan name (e.g., Breakfast, Half Board)
            $table->string('status')->default('active'); // Status of the meal plan (active/inactive)
            $table->timestamps(); // Includes created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meal_plane');
    }
}
