<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kitchen;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    /**
     * Handle the incoming request.
     */


    // update kichen name
    public function updateName(Request $request)
    {

        //note: default kitchen name created by settingServiceProvider
        // check name is empty or not
        if (!$request->name) {
            return response()->json(['error' => 'Name is empty']);
        }
        $kitchen = Kitchen::find($request->id);
        $kitchen->update(['name' => $request->name]);
        return response()->json([
            'success' => 'name updated success',
            'kitchen' => $kitchen
        ], 201);
    }
}
