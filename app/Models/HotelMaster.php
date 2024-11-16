<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelMaster extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural form of the model name
    protected $table = 'hotel_master';

    // Specify the primary key if it's different from the default 'id'
    protected $primaryKey = 'id'; // Optional if the primary key is 'id'

    // Disable timestamps if the table doesn't have 'created_at' and 'updated_at'
    public $timestamps = true; // Set to false if no timestamps in your table

    // Specify which attributes are mass assignable
    protected $fillable = [
        'name',  // Hotel name
        'category',  // Category of the hotel (string)
        'destination',  // Destination location
        'details',  // Details about the hotel
        'img',  // Image of the hotel
        'contact_person',  // Contact person name
        'email',  // Contact email
        'phone',  // Contact phone number
        'status',  // Hotel status (active/inactive)
        'website',  // Hotel website
    ];

    // Define relationships if necessary, for example:
    public function hotelSlaves()
    {
        return $this->hasMany(HotelSlave::class, 'hotel_master_id');
    }
}
