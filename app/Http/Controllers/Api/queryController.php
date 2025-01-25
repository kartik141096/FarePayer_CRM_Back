<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\userController;
use App\Models\Query;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class queryController extends Controller
{
    // public function getQueries(Request $request)
    // {
    //     $user = Auth::user();

    //     $adminFields = [];
    //     if ($user && ($user->role->id == '1' || $user->role->id == '2')) {
    //         $adminFields = ['query.source', 'query.assign_to'];
    //     }

    //     $fields = [
    //         'query.id', 'query.title', 'query.name', 'query.mobile', 'query.email',
    //         'query.adult_count', 'query.child_count', 'query.infant_count',
    //         'query.from_date', 'query.to_date', 'query.status', 'query.priority',
    //         'query.created_on', 'query.updated_on',
    //         DB::raw('GROUP_CONCAT(
    //             DISTINCT CONCAT(
    //                 query_destinations.name
    //             ) SEPARATOR ", ") as destinations'),
    //         DB::raw('GROUP_CONCAT(
    //             DISTINCT CASE 
    //                 WHEN query_destinations.type = "cities" THEN cities.name
    //                 WHEN query_destinations.type = "states" THEN states.name
    //                 WHEN query_destinations.type = "countries" THEN countries.name
    //                 ELSE "Unknown" END
    //             SEPARATOR ", ") as destination_names'),
    //     ];

    //     $fields = array_merge($fields, $adminFields);

    //     $query = DB::table('query')
    //         ->leftJoin('query_destinations', 'query.id', '=', 'query_destinations.query_id')
    //         ->leftJoin('cities', 'query_destinations.destination_id', '=', 'cities.id')
    //         ->leftJoin('states', 'query_destinations.destination_id', '=', 'states.id')
    //         ->leftJoin('countries', 'query_destinations.destination_id', '=', 'countries.id')
    //         ->leftJoin('users', 'query.assign_to', '=', 'users.id') // Join users table once
    //         ->select($fields)
    //         ->groupBy(
    //             'query.id', 'query.title', 'query.name', 'query.mobile', 'query.email',
    //             'query.adult_count', 'query.child_count', 'query.infant_count',
    //             'query.from_date', 'query.to_date', 'query.status', 'query.priority',
    //             'query.created_on', 'query.updated_on',
    //             'query.source',  // Added query.source to GROUP BY
    //             'query.assign_to' // Added query.assign_to to GROUP BY
    //         )
    //         ->distinct(); // This should eliminate duplicate rows

    //     // Apply filters if any
    //     if ($request->created_from) { $query->where('created_on', '>=', $request->created_from); }
    //     if ($request->created_to) { $query->where('created_on', '<=', $request->created_to); }
    //     if ($request->updated_from) { $query->where('updated_on', '>=', $request->updated_from); }
    //     if ($request->updated_to) { $query->where('updated_on', '<=', $request->updated_to); }
    //     if ($request->search_by_id) { $query->where('query.id', $request->search_by_id); }
    //     if ($request->status) { $query->where('status', $request->status); }
    //     if ($request->destination_id) { $query->where('query_destinations.destination_id', $request->destination_id); }
    //     if ($request->destination_type) { $query->where('query_destinations.type', $request->destination_type); }
    //     if ($request->assigned_to) { $query->where('query.assign_to', $request->assigned_to); }
    //     if ($request->source) { $query->where('query.source', $request->source); }
    //     if ($request->search_by_name_email_mobile) {
    //         $query->where(function ($q) use ($request) {
    //             $q->where('query.name', 'like', '%' . $request->search_by_name_email_mobile . '%')
    //                 ->orWhere('query.email', 'like', '%' . $request->search_by_name_email_mobile . '%')
    //                 ->orWhere('query.mobile', 'like', '%' . $request->search_by_name_email_mobile . '%');
    //         });
    //     }

    //     // Get the data
    //     $records = $query->orderBy('query.id', 'desc')->paginate(10);

    //     return response()->json($records);
    // }

    public function getQueries(Request $request)
    {
        $user = Auth::user();

        // Initialize adminFields as empty
        $adminFields = [];
        
        // If the user is an admin or superadmin, add additional fields
        if ($user && ($user->role->id == '1' || $user->role->id == '2')) {
            $adminFields = ['query.source', 'query.assign_to'];
        }

        // Define the fields to be selected
        $fields = [
            'query.id', 'query.title', 'query.name', 'query.mobile', 'query.email',
            'query.adult_count', 'query.child_count', 'query.infant_count',
            'query.from_date', 'query.to_date', 'query.status', 'query.priority',
            'query.created_on', 'query.updated_on',
            DB::raw('GROUP_CONCAT(
                DISTINCT CONCAT(
                    query_destinations.name
                ) SEPARATOR ", ") as destinations'),
            // DB::raw('GROUP_CONCAT(
            //     DISTINCT CASE 
            //         WHEN query_destinations.type = "cities" THEN cities.name
            //         WHEN query_destinations.type = "states" THEN states.name
            //         WHEN query_destinations.type = "countries" THEN countries.name
            //         ELSE "Unknown" END
            //     SEPARATOR ", ") as destination_names'),
        ];

        // Merge adminFields if applicable
        $fields = array_merge($fields, $adminFields);

        // Build the base query
        $query = DB::table('query')
            ->leftJoin('query_destinations', 'query.id', '=', 'query_destinations.query_id')
            ->leftJoin('cities', 'query_destinations.destination_id', '=', 'cities.id')
            ->leftJoin('states', 'query_destinations.destination_id', '=', 'states.id')
            ->leftJoin('countries', 'query_destinations.destination_id', '=', 'countries.id')
            ->leftJoin('users', 'query.assign_to', '=', 'users.id')
            ->select($fields)
            ->groupBy(
                'query.id', 'query.title', 'query.name', 'query.mobile', 'query.email',
                'query.adult_count', 'query.child_count', 'query.infant_count',
                'query.from_date', 'query.to_date', 'query.status', 'query.priority',
                'query.created_on', 'query.updated_on',
                'query.source',  // Added query.source to GROUP BY
                'query.assign_to' // Added query.assign_to to GROUP BY
            )
            ->distinct();

        // If the user is not an admin or superadmin, restrict the queries to those assigned to the logged-in user
        if ($user && !in_array($user->role->id, [1, 2])) {
            $query->where('query.assign_to', $user->id);
        }

        // Apply filters if any
        if ($request->created_from) { $query->where('created_on', '>=', $request->created_from); }
        if ($request->created_to) { $query->where('created_on', '<=', $request->created_to); }
        if ($request->updated_from) { $query->where('updated_on', '>=', $request->updated_from); }
        if ($request->updated_to) { $query->where('updated_on', '<=', $request->updated_to); }
        if ($request->search_by_id) { $query->where('query.id', $request->search_by_id); }
        if ($request->status) { $query->where('status', $request->status); }
        if ($request->destination_id) { $query->where('query_destinations.destination_id', $request->destination_id); }
        if ($request->destination_type) { $query->where('query_destinations.type', $request->destination_type); }
        if ($request->assigned_to) { $query->where('query.assign_to', $request->assigned_to); }
        if ($request->source) { $query->where('query.source', $request->source); }
        if ($request->search_by_name_email_mobile) {
            $query->where(function ($q) use ($request) {
                $q->where('query.name', 'like', '%' . $request->search_by_name_email_mobile . '%')
                    ->orWhere('query.email', 'like', '%' . $request->search_by_name_email_mobile . '%')
                    ->orWhere('query.mobile', 'like', '%' . $request->search_by_name_email_mobile . '%');
            });
        }
        $records = $query->orderBy('query.id', 'desc')->paginate(10);
        $userController = new UserController();
        
        foreach($records as $record){
            $userDetails = (array)$userController->getUserDetails($record->assign_to);
            // return  $userDetails['original'];
            if(isset($userDetails['original']) && $userDetails['original']['message'] != 'User not found'){
                $record->assign_to = array(
                    'name' => $userDetails['original']['name'],
                    'email' => $userDetails['original']['email'],
                );
            }else{
                $record->assign_to = array(
                    'name' => '(Deleted User)',
                );
            }
        }
        return response()->json($records);
    }


    public function addQuery(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:5',
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'destinations' => 'required|array',
            'adult_count' => 'required|int|max:5',
            'child_count' => 'required|int|max:5',
            'infant_count' => 'required|int|max:5',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'source' => 'required|string|max:255',
            'priority' => 'required|string|max:255',
            'assign_to' => '',
        ]);

        // Create the query
        $query = Query::create([
            'title' => $validatedData['title'],
            'name' => $validatedData['name'],
            'mobile' => $validatedData['mobile'],
            'email' => $validatedData['email'],
            'adult_count' => $validatedData['adult_count'],
            'child_count' => $validatedData['child_count'],
            'infant_count' => $validatedData['infant_count'],
            'from_date' => $validatedData['from_date'],
            'to_date' => $validatedData['to_date'],
            'source' => $validatedData['source'],
            'status' => "New",
            'priority' => $validatedData['priority'],
            'assign_to' => $validatedData['assign_to'],
            'created_on' => now(),
            'updated_on' => now(),
        ]);

        // Now insert the destinations into the query_destinations table
        $queryDestinations = [];
        foreach ($validatedData['destinations'] as $destination) {
            $queryDestinations[] = [
                'query_id' => $query->id,
                'destination_id' => $destination['id'],
                'name' => $destination['name'],
                'type' => $destination['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert the destinations
        DB::table('query_destinations')->insert($queryDestinations);

        return response()->json(['message' => 'Query successfully added', 'data' => $query], 201);
    }

    public function getQueryDestinations()
    {
        // Fetch unique destinations from the `query_destinations` table
        $destinations = DB::table('query_destinations')
            ->select('destination_id as id', 'name', 'type')
            ->distinct() // Ensure uniqueness
            ->orderBy('name') // Sort by name
            ->get();
    
        return response()->json($destinations);
    }
    
}
