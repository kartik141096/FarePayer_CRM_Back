<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelSlave extends Model
{
    use HasFactory;

    protected $table = 'hotel_slave';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'hotel_master_id',
        'from_date',
        'to_date',
        'room_type',
        'meal_plan',
        'single_price',
        'double_price',
        'triple_price',
        'extra_bed',
        'CWB_price',
        'CNB_price',
    ];

    public function hotelMaster()
    {
        return $this->belongsTo(HotelMaster::class, 'hotel_master_id');
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type');
    }

    public function mealPlan()
    {
        return $this->belongsTo(MealPlan::class, 'meal_plan');
    }
}
