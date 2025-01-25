<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities'; // Table name
    protected $primaryKey = 'id'; // Primary key
    public $timestamps = false; // Disable timestamps if not needed

    protected $fillable = [
        'name', 'state_id',
    ];

    // Relationship with State
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id'); // Define foreign and local keys
    }
}
