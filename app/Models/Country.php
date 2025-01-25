<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries'; // Table name
    protected $primaryKey = 'id'; // Primary key
    public $timestamps = false; // Disable timestamps if not needed

    protected $fillable = [
        'shortname', 'name', 'phonecode',
    ];

    // Relationship with State
    public function states()
    {
        return $this->hasMany(State::class, 'country_id', 'id'); // Define foreign and local keys
    }
}
