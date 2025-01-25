<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityMaster;
use App\Models\HotelMaster;
use App\Models\HotelSlave;
use Illuminate\Http\Request;
use App\Models\Itinerary;
use App\Models\ItineraryDestination;
use App\Models\ItineraryHotel;
use App\Models\ItineraryMaster;
use App\Models\ItinerarySlave;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class itineraryController extends Controller
{
    public function addItinerary(Request $request)
    {
        $validatedData = $request->validate([
            'itinerary.name' => 'required|string',
            'itinerary.startDate' => 'required|date',
            'itinerary.endDate' => 'required|date|after_or_equal:itinerary.startDate',
            'itinerary.type' => 'required|string',
            'itinerary.destination' => 'required|array|min:1',
            'itinerary.destination.*.id' => 'nullable|integer',
            'itinerary.destination.*.name' => 'required|string',
            'itinerary.destination.*.type' => 'required|string',
            'itinerary.adult_count' => 'required|integer',
            'itinerary.child_count' => 'required|integer',
            'itinerary.infant_count' => 'required|integer',
            'itinerary.note' => 'nullable|string',
        ]);

        // Save the itinerary
        $itinerary = new Itinerary();
        $itinerary->name = $validatedData['itinerary']['name'];
        $itinerary->start_date = $validatedData['itinerary']['startDate'];
        $itinerary->end_date = $validatedData['itinerary']['endDate'];
        $itinerary->type = $validatedData['itinerary']['type'];
        $itinerary->adult_count = $validatedData['itinerary']['adult_count'];
        $itinerary->child_count = $validatedData['itinerary']['child_count'];
        $itinerary->infant_count = $validatedData['itinerary']['infant_count'];
        $itinerary->note = $validatedData['itinerary']['note'];
        $itinerary->save();

        // Insert destinations into itineraries_destinations and store IDs
        $itineraryDestinationIds = [];
        foreach ($validatedData['itinerary']['destination'] as $destination) {
            $itineraryDestination = DB::table('itineraries_destinations')->insertGetId([
                'itinerary_id' => $itinerary->id,
                'destination_id' => $destination['id'] ?? null,
                'name' => $destination['name'],
                'type' => $destination['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $itineraryDestinationIds[] = $itineraryDestination;
        }

        // Get the first destination's ID for itinerary_master entries
        $defaultItineraryDestinationId = $itineraryDestinationIds[0];

        // Calculate the number of days between start_date and end_date
        $startDate = \Carbon\Carbon::parse($validatedData['itinerary']['startDate']);
        $endDate = \Carbon\Carbon::parse($validatedData['itinerary']['endDate']);
        $daysCount = $startDate->diffInDays($endDate) + 1;

        // Insert rows into itinerary_master
        for ($day = 1; $day <= $daysCount; $day++) {
            DB::table('itinerary_master')->insert([
                'itinerary_id' => $itinerary->id,
                'itinerary_destination_id' => $defaultItineraryDestinationId,
                //'destination_type' => $validatedData['itinerary']['destination'][0]['type'], // Using the type of the first destination
                'day' => $day,
                'day_heading' => "Day-".$day." Heading",
                'day_description' => "Enter here the description of Day - ".$day.". It is very important to showcase your daily itinerary.",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

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
        // return $itineraries;
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

        $itinerary = Itinerary::with('destinations', 'itineraryMasterEntries')
            ->find($request['id']);
    
        if (!$itinerary) {
            return response()->json(['message' => 'Itinerary not found'], 404);
        }
    
        // Calculate duration
        $startDate = \Carbon\Carbon::parse($itinerary['start_date']);
        $endDate = \Carbon\Carbon::parse($itinerary['end_date']);
        $durationDays = $startDate->diffInDays($endDate) + 1;
    
        $itinerary['duration'] = ($durationDays - 1) . 'N & ' . $durationDays . 'D';
        $itinerary['daysCount'] = $durationDays;
    
        // Fetch itinerary master entries with slave details
        $itinerary['itinerary_master'] = $itinerary->itineraryMasterEntries->map(function ($entry) {
            $itinerarySlaves = \App\Models\ItinerarySlave::where('itinerary_master_id', $entry->id)->get();
    
            $slaveData = $itinerarySlaves->map(function ($slave) {
                // Mapping table names to respective models
                $modelMap = [
                    'itinerary_hotel' => \App\Models\ItineraryHotel::class,
                    'hotel_master' => \App\Models\HotelMaster::class,
                    'meal_plan' => \App\Models\MealPlan::class,
                    'room_type' => \App\Models\RoomType::class,
                    'activity_master' => \App\Models\ActivityMaster::class,
                ];
    
                $modelClass = $modelMap[$slave->table_name] ?? null;
    
                if ($modelClass) {
                    $tableData = $modelClass::find($slave->table_id);
    
                    if ($tableData) {
                        // Return full data with nested arrays for hotel, meal_plan, and room_type
                        $data = array_merge(
                            [
                                'type' => $slave->table_name,
                                'id' => $slave->table_id,
                            ],
                            $tableData->toArray()
                        );
                        
                        // Specifically handle hotel_id, room_type, and meal_plan to include full details
                        if ($slave->table_name == 'itinerary_hotel') {
                            $data['hotel'] = \App\Models\HotelMaster::find($data['hotel_id']); // Full hotel details
                        }
                        if(isset($data['img'])){
                            $data['img'] = asset('storage/'.$data['img']);
                        }
                        if (isset($data['room_type'])) {
                            $data['room_type'] = \App\Models\RoomType::find($data['room_type']); // Full room type details
                        }
                        if (isset($data['meal_plan'])) {
                            $data['meal_plan'] = \App\Models\MealPlan::find($data['meal_plan']); // Full meal plan details
                        }
                        unset($data['hotel_id']);
                        return $data;
                    }
                }
    
                return null;
            })->filter();
    
            return [
                'master_id' => $entry->id,
                'day' => $entry->day,
                'itinerary_destination_id' => $entry->itinerary_destination_id,
                'day_heading' => $entry->day_heading,
                'day_description' => $entry->day_description,
                'itinerary_slave' => $slaveData->values(),
            ];
        });
    
        unset($itinerary->itineraryMasterEntries);
    
        return response()->json($itinerary, 200);
    }

    public function getItineraryDestinations()
    {
        $destinations = ItineraryDestination::select('destination_id', 'name', 'type')
            ->distinct('destination_id')
            ->get();

        return response()->json([
            'destinations' => $destinations
        ]);
    }

    public function getHotelsByDestinations(Request $request)
    {
        $validatedData = $request->validate([
            'destinations' => 'required|array',
            'destinations.*.destination_id' => 'required|integer',
            'destinations.*.type' => 'required|string|in:countries,states,cities',
        ]);

        $destinations = $validatedData['destinations'];

        $hotels = HotelMaster::getHotelsByDestinations($destinations);
        
        foreach($hotels as $hotel){
            $hotel['img'] = asset('storage/'.$hotel['img']);
            $hotel->destination_name = $hotel->destination_name;
        }

        return response()->json([
            'message' => 'Hotels retrieved successfully.',
            'hotels' => $hotels
        ]);
    }
    
    public function getActivitiesByDestinations(Request $request)
    {
        $validatedData = $request->validate([
            'destinations' => 'required|array',
            'destinations.*.destination_id' => 'required|integer',
            'destinations.*.type' => 'required|string|in:countries,states,cities',
        ]);

        $destinations = $validatedData['destinations'];

        $Activities = ActivityMaster::getActivitiesByDestinations($destinations);
        
        foreach($Activities as $activity){
            $activity['img'] = asset('storage/'.$activity['img']);
            $activity->destination_name = $activity->destination_name;

        }

        return response()->json([
            'message' => 'Activities retrieved successfully.',
            'activities' => $Activities
        ]);
    }
// =====================================================
    public function updateItineraryMaster(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'itinerary_id' => 'required|integer|exists:itineraries,id',
            'day' => 'required|integer|min:1',
            'itinerary_destination_id' => 'nullable|integer|exists:itineraries_destinations,id',
            'day_heading' => 'nullable|string|max:255',
            'day_description' => 'nullable|string',
        ]);

        // Find the itinerary_master entry that matches itinerary_id and day
        $itineraryMaster = ItineraryMaster::where('itinerary_id', $validatedData['itinerary_id'])
            ->where('day', $validatedData['day'])
            ->first();

        // Check if the entry exists
        if (!$itineraryMaster) {
            return response()->json([
                'message' => 'Itinerary entry not found for the given day and itinerary ID.'
            ], 404);
        }

        // Update the provided fields only if they exist in the request
        if (isset($validatedData['itinerary_destination_id'])) {
            $itineraryMaster->itinerary_destination_id = $validatedData['itinerary_destination_id'];
        }

        if (isset($validatedData['day_heading'])) {
            $itineraryMaster->day_heading = $validatedData['day_heading'];
        }

        if (isset($validatedData['day_description'])) {
            $itineraryMaster->day_description = $validatedData['day_description'];
        }

        // Save the changes
        $itineraryMaster->save();

        // Return a success response
        return response()->json([
            'message' => 'Itinerary destination updated successfully!',
            'itineraryMaster' => $itineraryMaster
        ], 200);
    }

    public function filterHotelPrice(Request $request)
    {

        $accommodation = $request['newAccomodation'];

        if(!isset($accommodation['hotelId']) || $accommodation['hotelId'] == null){
            return response()->json([
                'error' => false,
                'message' => "Hotel Not Found",
            ], 422);
        }

        $query = HotelSlave::query();

        if (isset($accommodation['hotelId'])) {
            $query->where('hotel_master_id', $accommodation['hotelId']);
        }

        if (!empty($accommodation['checkin']) && !empty($accommodation['checkout'])) {
            
            $accommodation['checkin'] = explode('T',$request['newAccomodation']['checkin'])[0];
            $accommodation['checkout'] = explode('T',$request['newAccomodation']['checkout'])[0];
            $query->where('from_date', '<=', $accommodation['checkin'])
                  ->where('to_date', '>=', $accommodation['checkout']);

        } elseif (!empty($accommodation['checkin'])) {
        
            $accommodation['checkin'] = explode('T',$request['newAccomodation']['checkin'])[0];
            $query->where('from_date', '<=', $accommodation['checkin']);

        } elseif (!empty($accommodation['checkout'])) {
        
            $accommodation['checkout'] = explode('T',$request['newAccomodation']['checkout'])[0];
            $query->where('to_date', '>=', $accommodation['checkout']);
        }
        
        if (!empty($accommodation['roomType']) && $accommodation['roomType'] != 0) {
            $query->where('room_type', $accommodation['roomType']);
        }

        if (!empty($accommodation['mealPlan']) && $accommodation['mealPlan'] != 0) {
            $query->where('meal_plan', $accommodation['mealPlan']);
        }

        $query->with(['roomType', 'mealPlan']);
        $filteredHotels = $query->get();
        $roomTypes = $filteredHotels->pluck('roomType')->unique('id');
        $mealPlans = $filteredHotels->pluck('mealPlan')->unique('id');
        $result = $filteredHotels->map(function ($hotel) {
            return [
                'id' => $hotel->id,
                'hotel_master_id' => $hotel->hotel_master_id,
                'from_date' => $hotel->from_date,
                'to_date' => $hotel->to_date,
                'single_price' => $hotel->single_price,
                'double_price' => $hotel->double_price,
                'triple_price' => $hotel->triple_price,
                'extra_bed' => $hotel->extra_bed,
                'CWB_price' => $hotel->CWB_price,
                'CNB_price' => $hotel->CNB_price,
                'room_type' => [
                    'id' => $hotel->roomType->id,
                    'name' => $hotel->roomType->name,
                ],
                'meal_plan' => [
                    'id' => $hotel->mealPlan->id,
                    'name' => $hotel->mealPlan->name,
                ],
                'created_at' => $hotel->created_at,
                'updated_at' => $hotel->updated_at,
                'deleted_at' => $hotel->deleted_at,
            ];
        });
    
        // Return the response
        return response()->json([
            'success' => true,
            'data' => $result,
            'room_types' => $roomTypes->values(),
            'meal_plans' => $mealPlans->values(),
        ]);
    }

    public function AddHotelToItinerary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'itinerary_id' => 'required|integer|exists:itineraries,id',
            'itinerary_master_id' => 'nullable|integer',
            'newAccomodation.id' => 'required|integer|exists:itinerary_hotel,id',
            'newAccomodation.name' => 'required|string|max:255',
            'newAccomodation.category' => 'nullable|string|max:255',
            'newAccomodation.checkin' => 'required|date',
            'newAccomodation.checkout' => 'required|date|after_or_equal:newAccomodation.checkin',
            'newAccomodation.roomType' => 'required|integer|min:1',
            'newAccomodation.mealPlan' => 'required|integer|min:1',
            'newAccomodation.numberOfRooms.single' => 'required|integer|min:0',
            'newAccomodation.numberOfRooms.double' => 'required|integer|min:0',
            'newAccomodation.numberOfRooms.triple' => 'required|integer|min:0',
            'newAccomodation.numberOfRooms.extra_bed' => 'required|integer|min:0',
            'newAccomodation.numberOfRooms.CWB' => 'required|integer|min:0',
            'newAccomodation.numberOfRooms.CNB' => 'required|integer|min:0',
        ]);

        $roomCounts = [
            $request->input('newAccomodation.numberOfRooms.single'),
            $request->input('newAccomodation.numberOfRooms.double'),
            $request->input('newAccomodation.numberOfRooms.triple'),
            $request->input('newAccomodation.numberOfRooms.extra_bed'),
            $request->input('newAccomodation.numberOfRooms.CWB'),
            $request->input('newAccomodation.numberOfRooms.CNB'),
        ];
        if (array_sum($roomCounts) <= 0) {
            
            $validator->errors()->add('numberOfRooms', 'Add atleast 1 room');
            
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
        if($request->input('newAccomodation.roomType') < 1 || $request->input('newAccomodation.mealPlan') < 1){
            
            $validator->errors()->add('error', 'All Fields are required');
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $itineraryDetail = ItineraryHotel::create([
            'hotel_id' => $request->input('newAccomodation.hotelId'),
            'category' => $request->input('newAccomodation.category'),
            'checkin' => $request->input('newAccomodation.checkin'),
            'checkout' => $request->input('newAccomodation.checkout'),
            'room_type' => $request->input('newAccomodation.roomType'),
            'meal_plan' => $request->input('newAccomodation.mealPlan'),
            'single' => $request->input('newAccomodation.numberOfRooms.single'),
            'double' => $request->input('newAccomodation.numberOfRooms.double'),
            'triple' => $request->input('newAccomodation.numberOfRooms.triple'),
            'extra_bed' => $request->input('newAccomodation.numberOfRooms.extra_bed'),
            'CWB' => $request->input('newAccomodation.numberOfRooms.CWB'),
            'CNB' => $request->input('newAccomodation.numberOfRooms.CNB'),
            'single_price' => $request->input('newAccomodation.roomPrice.single'),
            'double_price' => $request->input('newAccomodation.roomPrice.double'),
            'triple_price' => $request->input('newAccomodation.roomPrice.triple'),
            'extra_bed_price' => $request->input('newAccomodation.roomPrice.extra_bed'),
            'CWB_price' => $request->input('newAccomodation.roomPrice.CWB'),
            'CNB_price' => $request->input('newAccomodation.roomPrice.CNB'),
        ]);

        ItinerarySlave::create([
            'itinerary_id' => $request->input('itinerary_id'),
            'itinerary_master_id' => $request->input('itinerary_master_id'),
            'table_name' => 'itinerary_hotel',
            'table_id' => $itineraryDetail->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hotel added successfully.',
            'data' => [
                'itineraryDetail' => $itineraryDetail,
            ],
        ], 201);
    }

    public function getItineraryItemByIdAndTable(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'table' => 'required|string'
        ]);
    
        $id = $validatedData['id'];
        $table = $validatedData['table'];
    
        $modelMap = [
            'itinerary_hotel' => \App\Models\ItineraryHotel::class,
        ];
    
        if (!array_key_exists($table, $modelMap)) {
            return response()->json([
                'error' => 'Invalid table name.'
            ], 400);
        }
    
        $modelClass = $modelMap[$table];
    
        $record = $modelClass::find($id);
        // return $record;
        if (!$record) {
            return response()->json([
                'error' => 'Record not found.'
            ], 404);
        }
    
        if ($table === 'itinerary_hotel') {
            $hotelDetails = \App\Models\HotelMaster::find($record->hotel_id);
            $mealPlanDetails = \App\Models\MealPlan::find($record->meal_plan);
            $roomTypeDetails = \App\Models\RoomType::find($record->room_type);
    
            $record->hotel_details = $hotelDetails ? $hotelDetails->toArray() : null;
            $record->meal_plan_details = $mealPlanDetails ? $mealPlanDetails->toArray() : null;
            $record->room_type_details = $roomTypeDetails ? $roomTypeDetails->toArray() : null;
        }
    
        return response()->json($record, 200);
    }
    
    public function updateItineraryHotel(Request $request)
    {
        $data = $request->input('itineraryHotel');
    
        $totalRooms = $data['numberOfRooms']['single'] + 
                      $data['numberOfRooms']['double'] + 
                      $data['numberOfRooms']['triple'] + 
                      $data['numberOfRooms']['extra_bed'] + 
                      $data['numberOfRooms']['CWB'] + 
                      $data['numberOfRooms']['CNB'];

        if ($totalRooms <= 0) {
            return response()->json(['message' => 'Select atleast 1 room'], 400);
        }

        if($data['roomType'] == 0){
            return response()->json(['message' => 'Room Type Cannot be Empty'], 400);
        }
    
        if($data['mealPlan'] == 0){
            return response()->json(['message' => 'Meal Plan Cannot be Empty'], 400);
        }
    
        $itineraryHotel = ItineraryHotel::find($data['id']);
        if (!$itineraryHotel) {
            return response()->json(['message' => 'Itinerary hotel not found'], 404);
        }
    
        $itineraryHotel->hotel_id = $data['hotelId'];
        $itineraryHotel->checkin = $data['checkin'];
        $itineraryHotel->checkout = $data['checkout'];
        $itineraryHotel->meal_plan = $data['mealPlan'];
        $itineraryHotel->room_type = $data['roomType'];
        $itineraryHotel->single = $data['numberOfRooms']['single'];
        $itineraryHotel->double = $data['numberOfRooms']['double'];
        $itineraryHotel->triple = $data['numberOfRooms']['triple'];
        $itineraryHotel->extra_bed = $data['numberOfRooms']['extra_bed'];
        $itineraryHotel->CWB = $data['numberOfRooms']['CWB'];
        $itineraryHotel->CNB = $data['numberOfRooms']['CNB'];
        $itineraryHotel->single_price = $data['roomPrice']['single'];
        $itineraryHotel->double_price = $data['roomPrice']['double'];
        $itineraryHotel->triple_price = $data['roomPrice']['triple'];
        $itineraryHotel->extra_bed_price = $data['roomPrice']['extra_bed'];
        $itineraryHotel->CWB_price = $data['roomPrice']['CWB'];
        $itineraryHotel->CNB_price = $data['roomPrice']['CNB'];
    
        $itineraryHotel->save();
    
        return response()->json(['message' => 'Itinerary hotel updated successfully', 'data' => $itineraryHotel], 200);
    }
    
    public function deleteItinerarySlave(Request $request)
    {
        $itinerarySlaveId = $request->input('itinerarySlaveID');

        $itinerarySlave = DB::table('itinerary_slave')->where('id', $itinerarySlaveId)->first();

        if (!$itinerarySlave) {
            return response()->json(['message' => 'Itinerary Slave not found'], 404);
        }

        $tableName = $itinerarySlave->table_name;
        $slaveId = $itinerarySlave->table_id;

        if (Schema::hasTable($tableName)) {
            DB::table($tableName)->where('id', $slaveId)->delete();
        } else {
            return response()->json(['message' => "Table '{$tableName}' does not exist"], 400);
        }

        DB::table('itinerary_slave')->where('id', $itinerarySlaveId)->delete();

        return response()->json(['message' => 'Hotel deleted successfully'], 200);
    }
    
    public function getItineraryByID(Request $request)
    {
        // Validate the request input
        $request->validate([
            'id' => 'required|integer|exists:itineraries,id',
        ]);
    
        // Retrieve the itinerary details
        $itinerary = Itinerary::find($request->input('id'));
    
        // Check if the itinerary exists
        if (!$itinerary) {
            return response()->json(['message' => 'Itinerary not found'], 404);
        }
    
        // Fetch associated destinations
        $destinations = ItineraryDestination::where('itinerary_id', $itinerary->id)->get();
    
        // Combine the itinerary data with its destinations
        $itineraryData = $itinerary->toArray();
        $itineraryData['destination'] = $destinations;
    
        // Return the itinerary and associated destinations
        return response()->json($itineraryData, 200);
    }
    
    public function updateItinerary(Request $request)
    {
        $request->validate([
            'itinerary.id' => 'required|integer|exists:itineraries,id',
            'itinerary.name' => 'required|string|max:255',
            'itinerary.start_date' => 'required|date',
            'itinerary.end_date' => 'required|date',
            'itinerary.type' => 'required|string',
            'itinerary.adult_count' => 'required|integer',
            'itinerary.child_count' => 'required|integer',
            'itinerary.infant_count' => 'required|integer',
            'itinerary.note' => 'nullable|string',
            'itinerary.destination' => 'nullable|array',
            'itinerary.destination.*.id' => 'required_with:itinerary.destination|integer|exists:itineraries_destinations,id',
            'itinerary.destination.*.destination_id' => 'required_with:itinerary.destination|integer',
            'itinerary.destination.*.name' => 'required_with:itinerary.destination|string|max:255',
            'itinerary.destination.*.type' => 'required_with:itinerary.destination|string|max:255',
            'itinerary.newDestination' => 'nullable|array',
            'itinerary.newDestination.*.destination_id' => 'required|integer',
            'itinerary.newDestination.*.name' => 'required|string|max:255',
            'itinerary.newDestination.*.type' => 'required|string|max:255',
        ]);
        
        if (
            empty($request->input('itinerary.destination')) &&
            empty($request->input('itinerary.newDestination'))
        ) {
            return response()->json(['message' => 'Destination is Required'], 422);
        }
        
        $itinerary = Itinerary::find($request->input('itinerary.id'));
        if (!$itinerary) {
            return response()->json(['message' => 'Itinerary not found'], 404);
        }
    
        $itinerary->update([
            'name' => $request->input('itinerary.name'),
            'start_date' => $request->input('itinerary.start_date'),
            'end_date' => $request->input('itinerary.end_date'),
            'type' => $request->input('itinerary.type'),
            'adult_count' => $request->input('itinerary.adult_count'),
            'child_count' => $request->input('itinerary.child_count'),
            'infant_count' => $request->input('itinerary.infant_count'),
            'note' => $request->input('itinerary.note'),
        ]);
    
        $existingDestinations = ItineraryDestination::where('itinerary_id', $itinerary->id)->get();
    
        $existingDestinations->each(function ($destination) use ($request) {
            $destinationExistsInPayload = collect($request->input('itinerary.destination'))->contains('id', $destination->id);
            if (!$destinationExistsInPayload) {
                $destination->delete();
            }
        });
    
        foreach ($request->input('itinerary.destination') as $destinationData) {
            ItineraryDestination::updateOrCreate(
                ['id' => $destinationData['id'], 'itinerary_id' => $itinerary->id],
                [
                    'destination_id' => $destinationData['destination_id'],
                    'name' => $destinationData['name'],
                    'type' => $destinationData['type'],
                ]
            );
        }
    
        if ($request->has('itinerary.newDestination')) {
            foreach ($request->input('itinerary.newDestination') as $newDestinationData) {
                ItineraryDestination::create([
                    'itinerary_id' => $itinerary->id,
                    'destination_id' => $newDestinationData['destination_id'],
                    'name' => $newDestinationData['name'],
                    'type' => $newDestinationData['type'],
                ]);
            }
        }
    
        $itineraryData = $itinerary->toArray();
        $itineraryData['destinations'] = $itinerary->destinations;
    
        return response()->json([
            'message' => 'Itinerary successfully updated',
            'data' => $itineraryData
        ], 200);
    }

    public function addActivityToItinerary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'itinerary_id' => 'required|integer|exists:itineraries,id',
            'itinerary_master_id' => 'nullable|integer',
            'activity_id' => 'required|integer|exists:activity_master,id',
        ]);

        ItinerarySlave::create([
            'itinerary_id' => $request->input('itinerary_id'),
            'itinerary_master_id' => $request->input('itinerary_master_id'),
            'table_name' => 'activity_master',
            'table_id' => $request->input('activity_id'),
        ]);

        $activityDetails = ActivityMaster::where('id', $request->input('activity_id'))->get();

        return response()->json([
            'success' => true,
            'message' => 'Activity added successfully.',
            'data' => [
                'activityDetail' => $activityDetails,
            ],
        ], 201);
    }
    
    
    
    


}
