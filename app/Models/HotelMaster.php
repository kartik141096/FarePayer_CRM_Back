<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HotelMaster extends Model
{
    use HasFactory;

    protected $table = 'hotel_master';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'category',
        'destination_id',
        'destination_type',
        'details',
        'img',
        'contact_person',
        'email',
        'phone',
        'status',
        'website',
    ];

    public function hotelSlaves()
    {
        return $this->hasMany(HotelSlave::class, 'hotel_master_id');
    }

    public static function getHotelsByDestinations(array $destinations)
    {
        // Initialize the query for HotelMaster
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

    // public static function getHotelsByDestinations(array $destinations)
    // {
    //     $query = HotelMaster::query();

    //     foreach ($destinations as $destination) {
    //         $query->orWhere(function ($q) use ($destination) {
    //             $q->where('destination_id', $destination['destination_id'])
    //               ->where('destination_type', $destination['type']);
    //         });
    //     }

    //     return $query->get();
    // }
}
