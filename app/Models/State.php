<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = 'states'; // Table name
    protected $primaryKey = 'id'; // Primary key
    public $timestamps = false; // Disable timestamps if not needed

    protected $fillable = [
        'name', 'country_id',
    ];

    // Relationship with Country
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id'); // Define foreign and local keys
    }

    // Relationship with City
    public function cities()
    {
        return $this->hasMany(City::class, 'state_id', 'id'); // Define foreign and local keys
    }
}
