<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQueryDestinationsTable extends Migration
{
    public function up()
    {
        Schema::create('query_destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('query_id')->constrained('queries')->onDelete('cascade'); // Foreign key to queries table
            $table->unsignedBigInteger('destination_id');
            $table->string('name');
            $table->string('type');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('query_destinations');
    }
}
