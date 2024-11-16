<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelSlave extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural form of the model name
    protected $table = 'hotel_slave';

    // Specify the primary key if it's different from the default 'id'
    protected $primaryKey = 'id'; // Optional if the primary key is 'id'

    // Disable timestamps if the table doesn't have 'created_at' and 'updated_at'
    public $timestamps = true; // Set this to false if your table does not have timestamps

    // Specify which attributes are mass assignable
    protected $fillable = [
        'hotel_master_id', // Foreign key to the hotel_master table
        'from_date', // Room availability start date
        'to_date', // Room availability end date
        'room_type', // Room type (relates to room_type table)
        'meal_plan', // Meal plan (relates to meal_plan table)
        'single_price', // Price for a single room
        'double_price', // Price for a double room
        'triple_price', // Price for a triple room
        'extra_bed', // Extra bed charge
        'CWB_price', // Price for Children With Bed
        'CNB_price', // Price for Children Without Bed
    ];

    // Define the relationship with the HotelMaster model
    public function hotelMaster()
    {
        return $this->belongsTo(HotelMaster::class, 'hotel_master_id');
    }

    // Define the relationship with the RoomType model
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type');
    }

    // Define the relationship with the MealPlan model
    public function mealPlan()
    {
        return $this->belongsTo(MealPlan::class, 'meal_plan');
    }
}
