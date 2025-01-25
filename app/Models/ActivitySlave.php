<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivitySlave extends Model
{
    use HasFactory;

    // Table name (optional if it follows naming convention)
    protected $table = 'activity_slave';

    // Fillable fields for mass assignment
    protected $fillable = [
        'activity_master_id',
        'from_date',
        'to_date',
        'adult_price',
        'child_price'
    ];

    // Define relationship with ActivityMaster
    public function master()
    {
        return $this->belongsTo(ActivityMaster::class, 'activity_master_id');
    }
}
