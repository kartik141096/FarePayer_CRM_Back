<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransfersMaster extends Model
{
    use HasFactory;

    protected $table = 'transfers_master';

    protected $fillable = [
        'name',
        'destination_id',
        'destination_type',
        'img',
        'status'
    ];

    public function slaves()
    {
        return $this->hasMany(TransfersSlave::class, 'transfers_master_id');
    }

    public function getDestinationNameAttribute()
    {
        $destinationType = $this->destination_type;
        $destinationId = $this->destination_id;

        if (!in_array($destinationType, ['countries', 'states', 'cities'])) {
            return null;  // Invalid destination type
        }

        return DB::table($destinationType)
            ->where('id', $destinationId)
            ->value('name');
    }
}
