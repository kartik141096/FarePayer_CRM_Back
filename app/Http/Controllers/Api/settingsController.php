<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityMaster;
use App\Models\ActivitySlave;
use App\Models\HotelMaster;
use App\Models\HotelSlave;
use App\Models\ItineraryHotel;
use App\Models\MealPlan;
use App\Models\RoomType;
use App\Models\Supplier;
use App\Models\TransfersMaster;
use App\Models\TransfersSlave;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class settingsController extends Controller
{
    public function getRoomTypes(Request $request)
    {
        $page = $request->page;
        if ($page == 0) {
            $roomTypes = RoomType::all();
        } else {
            $roomTypes = RoomType::paginate(10, ['*'], 'page', $page);
        }
        return response()->json($roomTypes);
    }

    public function deleteRoomType(Request $request)
    {
        $id =  $request->id;
        $roomType = RoomType::find($id);
        if (!$roomType) {
            return response()->json(['message' => 'Room Type not found.'], 404);
        }
        $roomType->delete();
        return response()->json(['message' => 'Room Type deleted successfully.']);
    }

    public function changeRoomTypeStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $roomType = RoomType::find($request->id);

        if (!$roomType) {
            return response()->json(['message' => 'Room Type not found.'], 404);
        }

        $roomType->status = $request->input('status');

        $roomType->save();

        return response()->json(['message' => 'Room Type status updated successfully.']);
    }

    public function addRoomType(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $roomType = new RoomType();
        $roomType->name = $request->input('name');
        $roomType->save();

        return response()->json(['message' => 'Room Type created successfully.', 'data' => $roomType], 201);
    }

    public function getMealPlan(Request $request)
    {
        $page = $request->page;

        if ($page == 0) {
            $mealPlans = MealPlan::all();
        } else {
            $mealPlans = MealPlan::paginate(10, ['*'], 'page', $page);
        }

        return response()->json($mealPlans);
    }

    public function deleteMealPlan(Request $request)
    {
        $id =  $request->id;
        $mealPlan = MealPlan::find($id);
        if (!$mealPlan) {
            return response()->json(['message' => 'Meal Plan not found.'], 404);
        }
        $mealPlan->delete();
        return response()->json(['message' => 'Meal Plan deleted successfully.']);
    }

    public function changeMealPlanStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $mealPlan = MealPlan::find($request->id);

        if (!$mealPlan) {
            return response()->json(['message' => 'Meal Plan not found.'], 404);
        }

        $mealPlan->status = $request->input('status');

        $mealPlan->save();

        return response()->json(['message' => 'Meal Plan status updated successfully.']);
    }

    public function addMealPlan(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $mealPlan = new MealPlan();
        $mealPlan->name = $request->input('name');
        $mealPlan->save();

        return response()->json(['message' => 'Meal Plan created successfully.', 'data' => $mealPlan], 201);
    }
    
    public function getAllHotels(Request $request)
    {
        $page = $request->input('page', 1);
        $Hotels = HotelMaster::paginate(10, ['*'], 'page', $page);
        foreach($Hotels as $hotel){
            $hotel->destination_name = $hotel->destination_name;
            $hotel['img'] = asset('storage/'.$hotel['img']);
        }

        return response()->json($Hotels);
    }

    public function addHotel(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'destination_id' => 'required|integer',
            'destination_type' => 'required|string|in:countries,states,cities',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'details' => 'required|string|max:500',
            'img' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
            'website' => 'nullable|link',
        ], [
            'name.required' => 'Name field is required.',
            'category.required' => 'Category field is required.',
            'destination_id.required' => 'Destination field is required.',
            'destination_type.required' => 'Destination type is required.',
            'contact_person.required' => 'Contact person field is required.',
            'phone.required' => 'Phone field is required.',
            'email.required' => 'Email field is required.',
            'email.email' => 'Email field must be a valid email address.',
            'details.required' => 'Hotel details field is required.',
        ]);
    
        try {
            $imgPath = null;
            if ($request->hasFile('hotel.img') && $request->file('hotel.img')->isValid()) {
                $imgPath = $request->file('hotel.img')->store('hotel_images', 'public');
            }
    
            $Hotel = new HotelMaster();
            $Hotel->name = $validatedData['name'];
            $Hotel->category = $validatedData['category'];
            $Hotel->destination_id = $validatedData['destination_id'];
            $Hotel->destination_type = $validatedData['destination_type'];
            $Hotel->details = $validatedData['details'];
            $Hotel->contact_person = $validatedData['contact_person'];
            $Hotel->email = $validatedData['email'];
            $Hotel->phone = $validatedData['phone'];
            $Hotel->website = $validatedData['website'];
            $Hotel->img = $imgPath;
    
            $Hotel->save();
    
            return response()->json([
                'message' => 'Hotel created successfully.',
                'data' => $Hotel,
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while adding the hotel.', 'details' => $e->getMessage()], 500);
        }
    }
    
    public function changeHotelStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $hotel = HotelMaster::find($request->id);

        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found.'], 404);
        }

        $hotel->status = $request->input('status');

        $hotel->save();

        return response()->json(['message' => 'Hotel status updated successfully.']);
    }

    public function updateHotel(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:hotel_master,id',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'destination_id' => 'required|integer',
            'destination_type' => 'required|string|in:countries,states,cities',
            'details' => 'nullable|string',
            'img' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:15',
            'status' => 'nullable|boolean',
            'website' => 'nullable',
        ]);

        try {
            $hotel = HotelMaster::findOrFail($validatedData['id']);
            
            if ($request->hasFile('img') && $request->file('img')->isValid()) {
                $imgPath = $request->file('img')->store('hotel_images', 'public');
            } else {
                $imgPath = $hotel->img;
            }

            // Update the hotel details
            $hotel->update([
                'name' => $validatedData['name'],
                'category' => $validatedData['category'],
                'destination_id' => $validatedData['destination_id'],
                'destination_type' => $validatedData['destination_type'],
                'details' => $validatedData['details'],
                'img' => $imgPath, // Save the image path
                'contact_person' => $validatedData['contact_person'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'status' => $validatedData['status'] ?? true,
                'website' => $validatedData['website'],
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Hotel updated successfully',
                'hotel' => $hotel,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Hotel not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function deleteHotel($id)
    {
        try {
            $hotel = HotelMaster::findOrFail($id);
            $hotel->hotelSlaves()->delete();
            $hotel->delete();

            return response()->json([
                'message' => 'Hotel deleted successfully',
                'hotel_id' => $id,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Hotel not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function addHotelPrice(Request $request)
    {
        // Validate the incoming request data inside 'hotelPrice'
        $validated = $request->validate([
            'hotelPrice.hotel_master_id' => 'required|integer|exists:hotel_master,id',
            'hotelPrice.from_date' => 'required|date',
            'hotelPrice.to_date' => 'required|date|after_or_equal:hotelPrice.from_date',
            'hotelPrice.room_type' => 'required|integer',
            'hotelPrice.meal_plan' => 'required|integer',
            'hotelPrice.single_price' => 'required|numeric',
            'hotelPrice.double_price' => 'required|numeric',
            'hotelPrice.triple_price' => 'required|numeric',
            'hotelPrice.extra_bed' => 'required|numeric',
            'hotelPrice.CWB_price' => 'required|numeric',
            'hotelPrice.CNB_price' => 'required|numeric',
        ]);

        try {
            // Extract validated data from 'hotelPrice'
            $hotelPriceData = $validated['hotelPrice'];

            // Create a new hotel_slave record
            $hotelSlave = HotelSlave::create($hotelPriceData);

            return response()->json([
                'message' => 'Hotel price added successfully',
                'data' => $hotelSlave,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create hotel slave record',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteHotelPrice(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:hotel_slave,id',
        ]);

        try {
            $hotelSlave = HotelSlave::find($validated['id']);
            $hotelSlave->delete();

            return response()->json([
                "message" => "Hotel price record deleted successfully.",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => "Failed to delete the hotel price record.",
                "details" => $e->getMessage(),
            ], 500);
        }
    }

    public function getHotelPriceList(Request $request)
    {
        $validated = $request->validate([
            'hotel_master_id' => 'required|integer|exists:hotel_master,id',
        ]);
    
        try {

            $query = HotelSlave::with(['mealPlan', 'roomType'])
                ->where('hotel_master_id', $validated['hotel_master_id'])
                ->orderBy('id', 'desc');

            if ($request->page == 0) {
                $hotelSlaves = $query->get();

                $hotelSlaves->transform(function ($hotelSlave) {
                    $hotelSlave->meal_plan_name = $hotelSlave->mealPlan?->name;
                    $hotelSlave->room_type_name = $hotelSlave->roomType?->name;
                    return $hotelSlave;
                });

            } else {
                $hotelSlaves = $query->paginate(10);

                $hotelSlaves->getCollection()->transform(function ($hotelSlave) {
                    $hotelSlave->meal_plan_name = $hotelSlave->mealPlan?->name;
                    $hotelSlave->room_type_name = $hotelSlave->roomType?->name;
                    return $hotelSlave;
                });
            }
    
            return response()->json($hotelSlaves, 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => "Failed to retrieve records",
                "details" => $e->getMessage(),
            ], 500);
        }
    }
    
    public function getAllActivities(Request $request)
    {
        $page = $request->input('page', 1);
        $Activities = ActivityMaster::paginate(10, ['*'], 'page', $page);
        foreach($Activities as $activity){
            $activity->destination_name = $activity->destination_name;
            $activity['img'] = asset('storage/'.$activity['img']);
        }

        return response()->json($Activities);
    }
    
    public function addActivity(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'destination_id' => 'required|integer',
            'destination_type' => 'required|string|in:countries,states,cities',
            'details' => 'required|string|max:500',
            'img' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
        ], [
            'name.required' => 'Name field is required.',
            'destination_id.required' => 'Destination field is required.',
            'destination_type.required' => 'Destination type is required.',
            'details.required' => 'Activity details field is required.',
        ]);

        try {
            $imgPath = null;
            if ($request->hasFile('img') && $request->file('img')->isValid()) {
                $imgPath = $request->file('img')->store('activity_images', 'public');
            }
    
            $activity = new ActivityMaster();
            $activity->name = $validatedData['name'];
            $activity->destination_id = $validatedData['destination_id'];
            $activity->destination_type = $validatedData['destination_type'];
            $activity->details = $validatedData['details'];
            $activity->img = $imgPath;

            $activity->save();

            return response()->json([
                'message' => 'Activity created successfully.',
                'data' => $activity,
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while adding the Activity.', 'details' => $e->getMessage()], 500);
        }
    }

    public function changeActivityStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $activity = ActivityMaster::find($request->id);

        if (!$activity) {
            return response()->json(['message' => 'Activity not found.'], 404);
        }

        $activity->status = $request->input('status');

        $activity->save();

        return response()->json(['message' => 'Activity status updated successfully.']);
    }

    public function deleteActivity($id)
    {
        try {
            $activity = ActivityMaster::findOrFail($id);
            $activity->slaves()->delete();
            $activity->delete();

            return response()->json([
                'message' => 'Activity deleted successfully',
                'hotel_id' => $id,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Activity not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function updateActivity(Request $request)
    {   
        $request['id'] = (int)$request['id'];
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:activity_master,id',
            'name' => 'required|string|max:255',
            'destination_id' => 'required|integer',
            'destination_type' => 'required|string|in:countries,states,cities',
            'details' => 'nullable|string',
            'img' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
            'status' => 'nullable|boolean',
        ]);
        try {
            $hotel = ActivityMaster::findOrFail($validatedData['id']);
            
            if ($request->hasFile('img') && $request->file('img')->isValid()) {
                $imgPath = $request->file('img')->store('activity_images', 'public');
            } else {
                $imgPath = $hotel->img;
            }

            // Update the hotel details
            $hotel->update([
                'name' => $validatedData['name'],
                'destination_id' => $validatedData['destination_id'],
                'destination_type' => $validatedData['destination_type'],
                'details' => $validatedData['details'],
                'img' => $imgPath, // Save the image path
                'status' => $validatedData['status'] ?? true,
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Activity updated successfully',
                'hotel' => $hotel,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Activity not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function addActivityPrice(Request $request)
    {
        $validated = $request->validate([
            'activityPrice.activity_master_id' => 'required|integer|exists:activity_master,id',
            'activityPrice.from_date' => 'required|date',
            'activityPrice.to_date' => 'required|date|after_or_equal:activityPrice.from_date',
            'activityPrice.adult_price' => 'required|integer',
            'activityPrice.child_price' => 'required|integer',
            
        ]);

        try {
            // Extract validated data from 'activityPrice'
            $activityPriceData = $validated['activityPrice'];

            // Create a new hotel_slave record
            $activitySlave = ActivitySlave::create($activityPriceData);

            return response()->json([
                'message' => 'Activity price added successfully',
                'data' => $activitySlave,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create activity price record',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function getActivityPriceList(Request $request)
    {
        $validated = $request->validate([
            'activity_master_id' => 'required|integer|exists:activity_master,id',
        ]);
    
        try {
            $page = $request->input('page', 1);
            $perPage = 10; // Number of records per page
            
            $query = ActivitySlave::where('activity_master_id', $validated['activity_master_id'])
                ->orderBy('id', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
    
            return response()->json($query, 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => "Failed to retrieve records",
                "details" => $e->getMessage(),
            ], 500);
        }
    }
    
    public function deleteActivityPrice(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:activity_slave,id',
        ]);

        try {
            $activitySlave = ActivitySlave::find($validated['id']);
            $activitySlave->delete();

            return response()->json([
                "message" => "Activity price record deleted successfully.",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => "Failed to delete the activity price record.",
                "details" => $e->getMessage(),
            ], 500);
        }
    }

    public function addTransfer(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'destination_id' => 'required|integer',
            'destination_type' => 'required|string|in:countries,states,cities',
            'details' => 'required|string|max:500',
            'img' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
        ], [
            'name.required' => 'Name field is required.',
            'destination_id.required' => 'Destination field is required.',
            'destination_type.required' => 'Destination type is required.',
            'details.required' => 'Transfer details field is required.',
        ]);

        try {
            $imgPath = null;
            if ($request->hasFile('img') && $request->file('img')->isValid()) {
                $imgPath = $request->file('img')->store('transfers_images', 'public');
            }
    
            $transfer = new TransfersMaster();
            $transfer->name = $validatedData['name'];
            $transfer->destination_id = $validatedData['destination_id'];
            $transfer->destination_type = $validatedData['destination_type'];
            $transfer->details = $validatedData['details'];
            $transfer->img = $imgPath;

            $transfer->save();

            return response()->json([
                'message' => 'Transfer created successfully.',
                'data' => $transfer,
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while adding the Transfer.', 'details' => $e->getMessage()], 500);
        }
    }

    public function getAllTransfers(Request $request)
    {
        $page = $request->input('page', 1);
        $transfers = TransfersMaster::paginate(10, ['*'], 'page', $page);
        foreach($transfers as $transfer){
            $transfer->destination_name = $transfer->destination_name;
            $transfer['img'] = asset('storage/'.$transfer['img']);
        }

        return response()->json($transfers);
    }

    public function changeTransferStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $transfer = TransfersMaster::find($request->id);

        if (!$transfer) {
            return response()->json(['message' => 'Transfer not found.'], 404);
        }

        $transfer->status = $request->input('status');

        $transfer->save();

        return response()->json(['message' => 'Transfer status updated successfully.']);
    }

    public function updateTransfer(Request $request)
    {   
        $request['id'] = (int)$request['id'];
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:transfers_master,id',
            'name' => 'required|string|max:255',
            'destination_id' => 'required|integer',
            'destination_type' => 'required|string|in:countries,states,cities',
            'details' => 'nullable|string',
            'img' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
            'status' => 'nullable|boolean',
        ]);
        try {
            $transfer = TransfersMaster::findOrFail($validatedData['id']);
            
            if ($request->hasFile('img') && $request->file('img')->isValid()) {
                $imgPath = $request->file('img')->store('transfer_images', 'public');
            } else {
                $imgPath = $transfer->img;
            }

            // Update the transfer details
            $transfer->update([
                'name' => $validatedData['name'],
                'destination_id' => $validatedData['destination_id'],
                'destination_type' => $validatedData['destination_type'],
                'details' => $validatedData['details'],
                'img' => $imgPath, // Save the image path
                'status' => $validatedData['status'] ?? true,
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Transfer updated successfully',
                'transfer' => $transfer,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Transfer not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function deleteTransfer($id)
    {
        try {
            $transfer = TransfersMaster::findOrFail($id);
            $transfer->slaves()->delete();
            $transfer->delete();

            return response()->json([
                'message' => 'Transfer deleted successfully',
                'hotel_id' => $id,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Transfer not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function addTransferPrice(Request $request)
    {
        $baseValidation = [
            'transferPrice.transfers_master_id' => 'required|integer|exists:transfers_master,id',
            'transferPrice.from_date' => 'required|date',
            'transferPrice.to_date' => 'required|date|after_or_equal:transferPrice.from_date',
            'transferPrice.type' => 'required',
        ];
        
        // Conditionally add validation rules based on the transfer type
        if ($request['transferPrice']['type'] == 'SIC') {
            $additionalValidation = [
                'transferPrice.adult_price' => 'required|integer|min:1',
                'transferPrice.child_price' => 'required|integer|min:1',
            ];
        } else {
            $additionalValidation = [
                'transferPrice.vehical_price' => 'required|integer|min:1',
            ];
        }
        
        $validated = $request->validate(array_merge($baseValidation, $additionalValidation));

        try {
            $transferPriceData = $validated['transferPrice'];

            $transferSlave = TransfersSlave::create($transferPriceData);

            return response()->json([
                'message' => 'Transfer price added successfully',
                'data' => $transferSlave,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create transfer price record',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function getTransferPriceList(Request $request)
    {
        $validated = $request->validate([
            'transfers_master_id' => 'required|integer|exists:transfers_master,id',
        ]);
    
        try {
            $page = $request->input('page', 1);
            $perPage = 10; // Number of records per page
            
            $query = TransfersSlave::where('transfers_master_id', $validated['transfers_master_id'])
                ->orderBy('id', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
    
            return response()->json($query, 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => "Failed to retrieve records",
                "details" => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteTransferPrice(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:transfers_slave,id',
        ]);

        try {
            $transferSlave = TransfersSlave::find($validated['id']);
            $transferSlave->delete();

            return response()->json([
                "message" => "Transfer price record deleted successfully.",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => "Failed to delete the transfer price record.",
                "details" => $e->getMessage(),
            ], 500);
        }
    }

    public function addSupplier(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'company'          => 'required|string|max:255',
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:suppliers,email',
            'mobile'           => 'required|string|min:10|max:15|unique:suppliers,mobile',
            'destination_id'   => 'required|integer',
            'destination_type' => 'required|string|in:countries,states,cities',
        ]);

        // Return validation errors if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        // Create a new supplier record
        $supplier = Supplier::create([
            'company'          => $request->input('company'),
            'name'             => $request->input('name'),
            'email'            => $request->input('email'),
            'mobile'           => $request->input('mobile'),
            'destination_id'   => $request->input('destination_id'),
            'destination_type' => $request->input('destination_type'),
        ]);

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Supplier added successfully!',
            'data'    => $supplier
        ], 201);
    }

    public function getAllSuppliers(Request $request){

        $page = $request->input('page', 1);
        $suppliers = Supplier::paginate(10, ['*'], 'page', $page);
        foreach($suppliers as $supplier){
            $supplier->destination_name = $supplier->destination_name;
        }

        return response()->json($suppliers);
    }

    public function deleteSupplier($id)
    {
        // Find the supplier by ID, including soft-deleted suppliers
        $supplier = Supplier::find($id);
    
        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found'
            ], 404);
        }
    
        // Soft delete the supplier
        $supplier->delete();
    
        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Supplier soft deleted successfully!'
        ], 200);
    }
    

}
