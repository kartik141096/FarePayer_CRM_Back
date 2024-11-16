<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $table = 'room_type';

    protected $primaryKey = 'id'; 

    public $timestamps = true; 

    protected $fillable = [
        'name',  // Room type name
        'status',  // Room status (1/0)
    ];

    public function hotelSlaves()
    {
        return $this->hasMany(HotelSlave::class, 'room_type');
    }
}
