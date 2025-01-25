<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransfersSlave extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'transfers_slave';

    // Fillable fields for mass assignment
    protected $fillable = [
        'transfers_master_id',
        'from_date',
        'to_date',
        'type',
        'vehical_price',
        'adult_price',
        'child_price'
    ];

    // Relationship with TransfersMaster
    public function master()
    {
        return $this->belongsTo(TransfersMaster::class, 'transfers_master_id');
    }
}
