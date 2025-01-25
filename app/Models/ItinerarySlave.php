<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItinerarySlave extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'itinerary_slave';

    // Define the fillable fields
    protected $fillable = [
        'itinerary_id',
        'itinerary_master_id',
        'table_name',
        'table_id',
    ];

    // Define the relationship to the Itinerary model
    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class, 'itinerary_id');
    }

    // Define the relationship to the ItineraryMaster model
    public function master()
    {
        return $this->belongsTo(ItineraryMaster::class, 'itinerary_master_id');
    }
}
