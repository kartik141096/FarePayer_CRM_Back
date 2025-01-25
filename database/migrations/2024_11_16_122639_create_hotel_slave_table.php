<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelSlaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_slave', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('hotel_master_id')
                ->constrained('hotel_master') // References the 'hotel_master' table
                ->onDelete('cascade'); // Deletes related rows on hotel_master deletion
            $table->date('from_date'); // Start date
            $table->date('to_date'); // End date
            $table->foreignId('room_type')
                ->constrained('room_type') // References the 'room_type' table
                ->onDelete('cascade'); // Deletes related rows on room_type deletion
            $table->foreignId('meal_plan')
                ->constrained('meal_plan') // References the 'meal_plan' table
                ->onDelete('cascade'); // Deletes related rows on meal_plan deletion
            $table->decimal('single_price', 10, 2)->nullable(); // Price for single occupancy
            $table->decimal('double_price', 10, 2)->nullable(); // Price for double occupancy
            $table->decimal('triple_price', 10, 2)->nullable(); // Price for triple occupancy
            $table->decimal('extra_bed', 10, 2)->nullable(); // Extra bed cost
            $table->decimal('CWB_price', 10, 2)->nullable(); // Child with bed price
            $table->decimal('CNB_price', 10, 2)->nullable(); // Child without bed price
            $table->timestamps(); // Includes created_at and updated_at
            $table->softDeletes(); // Adds deleted_at column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_slave');
    }
}
