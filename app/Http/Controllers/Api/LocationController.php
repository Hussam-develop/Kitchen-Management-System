<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locationDetails = Location::with('subLocations.products')
            ->get();

        return response()->json(['mainLocation' => $locationDetails], 200);
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
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $location = Location::create([
            'name' => $request->name,
        ]);
        // locationUrl to show details
        // وضعت رابط عرض الموقع بدلا من التفاصيل ويمكنني ان اعدل محتواه كيفما اشاء
        $locationUrl = route('locations.show', $location->id);
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
        $locationDetails = Location::with('subLocations.products')->find($id);
        if (!$locationDetails) {
            return response()->json(['error' => 'location not found']);
        }

        return response()->json(['mainLocation' => $locationDetails], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $location = Location::find($id);
        $location->update($request->all());
        return response()->json([
            'success' => 'location updeted successfully',
            'location' => $location
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return response()->json([
            'success' => 'location deleted successfully'
        ], 200);
    }
}
