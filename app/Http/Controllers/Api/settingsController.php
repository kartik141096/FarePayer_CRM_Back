<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class settingsController extends Controller
{
    public function getRoomTypes(Request $request)
    {
        $page = $request->input('page', 1);
        $roomTypes = RoomType::paginate(10, ['*'], 'page', $page);

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
}
