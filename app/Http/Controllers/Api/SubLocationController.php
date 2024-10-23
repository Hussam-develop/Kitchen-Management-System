<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subLocationDetails = SubLocation::with(['products', 'location'])
            ->get();
        return response()->json(['subLocation' => $subLocationDetails], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'location_id' => 'required|exists:locations,id'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $location = SubLocation::create([
            'name' => $request->name,
            'location_id' => $request->location_id
        ]);
        // locationUrl to show details
        $locationUrl = route('sub-locations.show', $location->id);
        // generateQrcode() is helper function in Helpers folder for clean code {Don`t repet your self}
        $location->qr = generateQrcode($locationUrl);
        $location->save();
        // Return the path to the generated QR code
        return response()->json([
            'success' => 'Location Created Successfully',
            'location' => $location,
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $subLocationDetails = SubLocation::with('products')->find($id);
        if (!$subLocationDetails) {
            return response()->json(['error' => 'location not found']);
        }

        return response()->json(['subLocation' => $subLocationDetails], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubLocation $subLocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $location = SubLocation::find($id);
        if (!$location) {
            return response()->json(['error' => 'location not found']);
        }
        $location->update($request->all());

        return response()->json([
            'success' => 'sub-location updeted successfully',
            'subLocation' => $location
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubLocation $subLocation)
    {
        $subLocation->delete();
        return response()->json([
            'success' => 'location deleted successfully'
        ], 200);
    }
}
