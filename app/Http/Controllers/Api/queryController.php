<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    //     if ($user && ($user->role === 'ROLE_ADMIN' || $user->role === 'ROLE_SUPER_ADMIN')) {

    //         $adminFields = ', query.source, query.assign_to';
    //     }else{
    //         $adminFields = '';
    //     }
        
    //     $fields = 'query.id, query.title, query.name, query.mobile, query.email, query.destination, query.adult_count, query.child_count, query.infant_count, query.from_date, query.to_date,
    //                 query.status, query.priority, query.created_on, query.updated_on'.$adminFields;

    //     $fieldArray = array_map('trim', explode(',', $fields));
    //     $query = DB::table('query')
    //                 ->leftJoin('cities', 'query.destination', '=', 'cities.id')
    //                 ->select($fieldArray, 'cities.name as destination_name');

    //     if ($request->created_from) { $query->where('created_on', '>=', $request->created_from); }
    //     if ($request->created_to) { $query->where('created_on', '<=', $request->created_to); }
    //     if ($request->updated_from) { $query->where('updated_on', '>=', $request->updated_from); }
    //     if ($request->updated_to) { $query->where('updated_on', '<=', $request->updated_to); }
    //     if ($request->search_by_id) { $query->where('query.id', $request->search_by_id); }
    //     if ($request->status) { $query->where('status', $request->status); }
    //     if ($request->destination) { $query->where('destination', $request->destination); }
    //     if ($request->assigned_to) { $query->where('assign_to', $request->users); }
    //     if ($request->source) { $query->where('source', $request->source); }
    //     if ($request->search_by_name_email_mobile) {
    //         $query->where(function ($q) use ($request) {
    //             $q->where('name', 'like', '%' . $request->search_by_name_email_mobile . '%')
    //             ->orWhere('email', 'like', '%' . $request->search_by_name_email_mobile . '%')
    //             ->orWhere('mobile', 'like', '%' . $request->search_by_name_email_mobile . '%');
    //         });
    //     }

    //     $records = $query->orderBy('query.id', 'desc')->paginate(10);

    //     return response()->json($records);
    // }

    public function getQueries(Request $request)
    {
        $user = Auth::user();

        $adminFields = [];
        if ($user && ($user->role === 'ROLE_ADMIN' || $user->role === 'ROLE_SUPER_ADMIN')) {
            $adminFields = ['query.source', 'query.assign_to'];
        }

        $fields = [
            'query.id', 'query.title', 'query.name', 'query.mobile', 'query.email',
            'query.destination', 'query.adult_count', 'query.child_count', 'query.infant_count',
            'query.from_date', 'query.to_date', 'query.status', 'query.priority',
            'query.created_on', 'query.updated_on', 'cities.name as destination_name'
        ];

        $fields = array_merge($fields, $adminFields);

        $query = DB::table('query')
            ->leftJoin('cities', 'query.destination', '=', 'cities.id')
            ->select($fields);

        if ($request->created_from) { $query->where('created_on', '>=', $request->created_from); }
        if ($request->created_to) { $query->where('created_on', '<=', $request->created_to); }
        if ($request->updated_from) { $query->where('updated_on', '>=', $request->updated_from); }
        if ($request->updated_to) { $query->where('updated_on', '<=', $request->updated_to); }
        if ($request->search_by_id) { $query->where('query.id', $request->search_by_id); }
        if ($request->status) { $query->where('status', $request->status); }
        if ($request->destination) { $query->where('destination', $request->destination); }
        if ($request->assigned_to) { $query->where('assign_to', $request->users); }
        if ($request->source) { $query->where('source', $request->source); }
        if ($request->search_by_name_email_mobile) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search_by_name_email_mobile . '%')
                ->orWhere('email', 'like', '%' . $request->search_by_name_email_mobile . '%')
                ->orWhere('mobile', 'like', '%' . $request->search_by_name_email_mobile . '%');
            });
        }

        $records = $query->orderBy('query.id', 'desc')->paginate(10);

        return response()->json($records);
    }

    public function addQuery(Request $request)
    {
        // // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:5',
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'destination' => 'required|int',
            'adult_count' => 'required|int|max:5',
            'child_count' => 'required|int|max:5',
            'infant_count' => 'required|int|max:5',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'source' => 'required|string|max:255',
            'priority' => 'required|string|max:255',
            'assign_to' => '',
        ]);

        // // Create a new query entry using the model
        $query = Query::create([
            'title' => $validatedData['title'],
            'name' => $validatedData['name'],
            'mobile' => $validatedData['mobile'],
            'email' => $validatedData['email'],
            'destination' => $validatedData['destination'],
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

        return response()->json(['message' => 'Query successfully added', 'data' => $query], 201);
    }
    
    public function searchCities(Request $request)
    {
        // Get search term from the request
        $term = $request->input('term');

        // Search for cities where the name matches the search term
        $cities = City::where('name', 'like', '%' . $term . '%')
                      ->limit(10) // Limit the results to avoid too many cities at once
                      ->get();

        // Return the matching cities as a JSON response
        return response()->json($cities);
    }

    public function getQueryDestinations()
    {
        $destinations = DB::table('query')
        ->join('cities', 'query.destination', '=', 'cities.id')
        ->select('query.destination as id', 'cities.name as destination_name')
        ->distinct()
        ->orderBy('cities.name') // Optional: order by destination name
        ->get();

        return response()->json($destinations);
    }

}
