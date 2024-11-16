<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Itinerary;


class itineraryController extends Controller
{
    public function addItinerary(Request $request)
    {
        $validatedData = $request->validate([
            'itinerary.name' => 'required|string',
            'itinerary.startDate' => 'required|date',
            'itinerary.endDate' => 'required|date',
            'itinerary.type' => 'required|string',
            'itinerary.destination' => 'required|array',
            'itinerary.adult_count' => 'required|integer',
            'itinerary.child_count' => 'required|integer',
            'itinerary.infant_count' => 'required|integer',
            'itinerary.note' => 'nullable|string',
        ], [
            'itinerary.name.required' => 'The Name field is required.',
            'itinerary.startDate.required' => 'The Start Date field is required.',
            'itinerary.endDate.required' => 'The End Date field is required.',
            'itinerary.type.required' => 'The Type field is required.',
            'itinerary.destination.required' => 'The Destination field is required.',
            'itinerary.adult_count.required' => 'The Adult Count field is required.',
            'itinerary.child_count.required' => 'The Child Count field is required.',
            'itinerary.infant_count.required' => 'The Infant Count field is required.',
            'itinerary.note.required' => 'The Note field is required.',
        ]);

        $itinerary = new Itinerary();
        $itinerary->name = $validatedData['itinerary']['name'];
        $itinerary->start_date = $validatedData['itinerary']['startDate'];
        $itinerary->end_date = $validatedData['itinerary']['endDate'];
        $itinerary->type = $validatedData['itinerary']['type'];
        $itinerary->destination = json_encode($validatedData['itinerary']['destination']); // Encode the destination array to JSON
        $itinerary->adult_count = $validatedData['itinerary']['adult_count'];
        $itinerary->child_count = $validatedData['itinerary']['child_count'];
        $itinerary->infant_count = $validatedData['itinerary']['infant_count'];
        $itinerary->note = $validatedData['itinerary']['note'];

        $itinerary->save();

        return response()->json([
            'message' => 'Itinerary successfully added!',
            'itinerary' => $itinerary
        ], 201);
    }

    public function getItineraries(Request $request)
    {
        $type = $request->input('type');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $destination_id = $request->input('destination_id');
        $notes = $request->input('notes');
        $name = $request->input('name');

        // Build the query
        $query = Itinerary::query();
    
        // Apply filters if they are provided in the request
        if ($type) {
            $query->where('type', $type);
        }
    
        if ($start_date) {
            $query->whereDate('start_date', '>=', $start_date);
        }
    
        if ($end_date) {
            $query->whereDate('end_date', '<=', $end_date);
        }
    
        if ($destination_id) {
            // Assuming 'destinations' is stored as a JSON string
            $query->whereJsonContains('destinations', ['id' => $destination_id]);
        }
    
        if ($notes) {
            $query->where('note', 'like', '%' . $notes . '%');
        }

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }
    
        // Execute the query and get the filtered itineraries
        $itineraries = $query->paginate(10);

        foreach ($itineraries as $key => $value){
            $itineraries[$key]['destinations'] = json_decode($itineraries[$key]->destinations, true);
            $durationDays = $itineraries[$key]['start_date']->diffInDays($itineraries[$key]['end_date'])+1;
            $itineraries[$key]['duration'] = ($durationDays - 1) . 'N - ' . $durationDays . 'D';
        }

    
        // Return the filtered itineraries as a JSON response
        return response()->json([
            'itineraries' => $itineraries
        ]);
    }

    public function getItineraryDetails(Request $request)
    {
        $itinerary = Itinerary::find($request['id']);

        if (!$itinerary) {
            return response()->json(['message' => 'Itinerary not found'], 404);
        }
        $itinerary['destinations'] = json_decode($itinerary->destinations, true);
        $durationDays = $itinerary['start_date']->diffInDays($itinerary['end_date'])+1;
        $itinerary['duration'] = ($durationDays - 1) . 'N & ' . $durationDays . 'D';
        $itinerary['daysCount'] = $durationDays;
        
        return response()->json($itinerary, 200);
    }

    public function getItineraryDestinations()
    {
        // Fetch all itineraries from the database
        $itineraries = Itinerary::all();

        // Initialize an associative array to store unique destinations by id
        $destinationList = [];

        foreach ($itineraries as $itinerary) {
            // Decode the JSON-encoded destination field for each itinerary
            $destinations = json_decode($itinerary->destination, true);

            // Check if decoding was successful and that destinations is an array
            if (is_array($destinations)) {
                // Loop through each destination and add it to the array if unique
                foreach ($destinations as $destination) {
                    if (isset($destination['id']) && isset($destination['name'])) {
                        $destinationList[$destination['id']] = [
                            'id' => $destination['id'],
                            'name' => $destination['name'],
                        ];
                    }
                }
            }
        }

        // Return the unique destination list as a JSON response
        return response()->json([
            'destinations' => array_values($destinationList) // Reset array keys
        ]);
    }





}
