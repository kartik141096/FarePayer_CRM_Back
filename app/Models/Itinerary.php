<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural of the model name
    protected $table = 'itineraries';

    // Define the fillable fields
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'type',
        'destinations', 
        'adult_count',
        'child_count',
        'infant_count',
        'note'
    ];

    // Cast attributes to desired types
    protected $casts = [
        'destinations' => 'array',  // Auto-convert JSON to array
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}