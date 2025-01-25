<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActivityMaster extends Model
{
    use HasFactory;

    // Table name (optional if it follows naming convention)
    protected $table = 'activity_master';

    // Fillable fields for mass assignment
    protected $fillable = [
        'name',
        'destination_id',
        'destination_type',
        'details',
        'img',
        'status'
    ];

    // Define relationship with ActivitySlave
    public function slaves()
    {
        return $this->hasMany(ActivitySlave::class, 'activity_master_id');
    }

    
    public static function getActivitiesByDestinations(array $destinations)
    {

        $query = self::where('status', 1)->where(function ($query) use ($destinations) {
            foreach ($destinations as $destination) {
                $type = $destination['type'];
                $id = $destination['destination_id'];
        
                if ($type === 'countries') {
                    // Get all states and cities under the country
                    $stateIds = State::where('country_id', $id)->pluck('id');
                    $cityIds = City::whereIn('state_id', $stateIds)->pluck('id');
        
                    // Include hotels linked to country, states, and cities
                    $query->orWhere(function ($q) use ($id, $stateIds, $cityIds) {
                        $q->where('destination_type', 'countries')->where('destination_id', $id)
                        ->orWhere(function ($q2) use ($stateIds) {
                            $q2->where('destination_type', 'states')->whereIn('destination_id', $stateIds);
                        })
                        ->orWhere(function ($q3) use ($cityIds) {
                            $q3->where('destination_type', 'cities')->whereIn('destination_id', $cityIds);
                        });
                    });
                } elseif ($type === 'states') {
                    // Get all cities under the state
                    $cityIds = City::where('state_id', $id)->pluck('id');
        
                    // Include hotels linked to the state and its cities
                    $query->orWhere(function ($q) use ($id, $cityIds) {
                        $q->where('destination_type', 'states')->where('destination_id', $id)
                        ->orWhere(function ($q2) use ($cityIds) {
                            $q2->where('destination_type', 'cities')->whereIn('destination_id', $cityIds);
                        });
                    });
                } elseif ($type === 'cities') {
                    // Only include hotels linked to the specific city
                    $query->orWhere('destination_type', 'cities')->where('destination_id', $id);
                }
            }
        });
        // Execute and return the query results
        return $query->get();
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
