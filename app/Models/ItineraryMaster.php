<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItineraryMaster extends Model
{
    use HasFactory, SoftDeletes;

    // Specify the table name
    protected $table = 'itinerary_master';

    // Define the fillable fields
    protected $fillable = [
        'itinerary_id',
        'itinerary_destination_id',
        'day',
        'day_heading',
        'day_description',
    ];

    // Cast attributes to desired types
    protected $casts = [
        'day' => 'integer',
    ];

    // Define the relationship to the Itinerary model
    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class, 'itinerary_id');
    }
}
