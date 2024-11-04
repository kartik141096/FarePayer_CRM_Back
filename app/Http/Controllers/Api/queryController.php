<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class queryController extends Controller
{
    public function getQueries(Request $request)
    {
        $query = DB::table('query');

        if ($request->created_from) { $query->where('created_on', '>=', $request->created_from); }
        if ($request->created_to) { $query->where('created_on', '<=', $request->created_to); }
        if ($request->updated_from) { $query->where('updated_on', '>=', $request->updated_from); }
        if ($request->updated_to) { $query->where('updated_on', '<=', $request->updated_to); }
        if ($request->search_by_id) { $query->where('id', $request->search_by_id); }
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
        
        $records = $query->orderBy('id', 'desc')->paginate(10);

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
            'destination' => 'required|string|max:255',
            'adult_count' => 'required|int|max:5',
            'child_count' => 'required|int|max:5',
            'infant_count' => 'required|int|max:5',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'source' => 'required|string|max:255',
            'priority' => 'required|string|max:255',
            'assign_to' => 'required|string|max:255',
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

}
