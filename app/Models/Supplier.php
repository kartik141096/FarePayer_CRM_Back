<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suppliers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company',
        'name',
        'email',
        'mobile',
        'destination_id',
        'destination_type',
        'created_at',
        'updated_at',
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Define a relationship with the destination tables (country, state, city).
     */
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
