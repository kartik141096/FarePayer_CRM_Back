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
        Schema::create('query', function (Blueprint $table) {
            $table->id();
            $table->string('title', 5);
            $table->string('name');
            $table->string('mobile');
            $table->string('email');
            $table->string('destination');
            $table->string('adult_count', 5);
            $table->string('child_count', 5);
            $table->string('infant_count', 5);
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('source');
            $table->string('status');
            $table->string('priority');
            $table->string('assign_to');
            $table->timestamps(0); // This will create 'created_at' and 'updated_at' columns with timestamp type
            
            // Setting default current timestamps for created_on and updated_on
            $table->timestamp('created_on')->useCurrent()->onUpdate('CURRENT_TIMESTAMP');
            $table->timestamp('updated_on')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('queries');
    }
};
