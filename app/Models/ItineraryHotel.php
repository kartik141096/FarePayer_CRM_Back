<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItineraryHotel extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'itinerary_hotel';

    // Define the fillable fields
    protected $fillable = [
        'hotel_id',
        'category',
        'checkin',
        'checkout',
        'room_type',
        'meal_plan',
        'single',
        'double',
        'triple',
        'extra_bed',
        'CWB',
        'CNB',
        'single_price',
        'double_price',
        'triple_price',
        'extra_bed_price',
        'CWB_price',
        'CNB_price',
    ];

    // Cast attributes to desired types
    protected $casts = [
        'checkin' => 'datetime',
        'checkout' => 'datetime',
        'single' => 'integer',
        'double' => 'integer',
        'triple' => 'integer',
        'extra_bed' => 'integer',
        'CWB' => 'integer',
        'CNB' => 'integer',
    ];

    // Define any relevant relationships (example: to hotels)
    public function hotel()
    {
        return $this->belongsTo(HotelMaster::class, 'hotel_id');
    }
}
