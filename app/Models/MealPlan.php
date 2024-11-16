<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural form of the model name
    protected $table = 'meal_plan';

    // Specify the primary key if it's different from the default 'id'
    protected $primaryKey = 'id'; // Optional if the primary key is 'id'

    // Disable timestamps if the table doesn't have 'created_at' and 'updated_at'
    public $timestamps = true; // Set to false if no timestamps in your table

    // Specify which attributes are mass assignable
    protected $fillable = [
        'name',  // Meal plan name (e.g., Full Board, Half Board)
        'status',  // Meal plan status (active/inactive)
    ];

    // Define relationships if necessary, for example:
    public function hotelSlaves()
    {
        return $this->hasMany(HotelSlave::class, 'meal_plan');
    }
}
