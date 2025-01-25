<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class destinationController extends Controller
{

    public function searchDestination(Request $request)
    {
        $term = $request->input('term');
        $countries = Country::where('name', 'like', '%' . $term . '%')
            ->limit(10)
            ->get(['id', 'name'])
            ->map(function ($country) {
                return [
                    'type' => 'countries',
                    'id' => $country->id,
                    'name' => $country->name,
                ];
            });
        if ($countries->isEmpty()) {
            $countries = collect([]);
        }
        $states = State::where('name', 'like', '%' . $term . '%')
            ->limit(10)
            ->get(['id', 'name', 'country_id'])
            ->map(function ($state) {
                return [
                    'type' => 'states',
                    'id' => $state->id,
                    'name' => $state->name,
                    'country_id' => $state->country_id,
                ];
            });
        if ($states->isEmpty()) {
            $states = collect([]);
        }

        $cities = City::where('name', 'like', '%' . $term . '%')
            ->with('state') 
            ->limit(10)
            ->get(['id', 'name', 'state_id'])
            ->map(function ($city) {
                return [
                    'type' => 'cities',
                    'id' => $city->id,
                    'name' => $city->name,
                    'state_id' => $city->state_id,
                    'country_id' => optional($city->state)->country_id, 
                ];
            });
        if ($cities->isEmpty()) {
            $cities = collect([]);
        }
        $results = $countries->merge($states)->merge($cities);
        // $results = $results->map(function ($item) {
        //     if ($item['type'] === 'states') {
        //         $item['name'] .= ' (state)';
        //     }
        //     if ($item['type'] === 'countries') {
        //         $item['name'] .= ' (country)';
        //     }
        //     if ($item['type'] === 'cities') {
        //         $item['name'] .= ' (city)';
        //     }
        //     return $item;
        // });
        
        return response()->json($results);
    }

    public function findDestinationName(Request $request)
    {
        $tableName = $request->table;
        $id = $request->id;
        $models = [
            'countries' => ['model' => Country::class],
            'states' => ['model' => State::class],
            'cities' => ['model' => City::class],
        ];
        if (!array_key_exists($tableName, $models)) {
            return response()->json(['error' => 'Invalid table name provided'], 400);
        }
        $modelClass = $models[$tableName]['model'];
        try {
            $record = $modelClass::findOrFail($id);

            $response = [
                'destination_name' => $record->{'name'},
                'id' => $id, 
            ];
            return response()->json($response, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Record not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    

    
    



    

}
