<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItineraryDestination extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural of the model name
    protected $table = 'itineraries_destinations';

    // Define the fillable fields
    protected $fillable = [
        'itinerary_id',
        'destination_id',
        'name',
        'type',
    ];

    // Cast any attributes that need special handling
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Define the inverse relationship to the Itinerary model.
     */
    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function itineraryMasterEntries()
    {
        return $this->hasMany(ItineraryMaster::class, 'itinerary_destination_id');
    }
}
