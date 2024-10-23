<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\storeProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productDetails = Product::with(['subLocation.location'])
            ->get();
        return response()->json(['product' => $productDetails], 200);
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
            'name' => ['required', 'min:3', 'max:30', 'string'],
            'qty' => ['required', 'integer', 'min:1'],
            'alert_qty' => ['nullable', 'integer', 'min:1'],
            'ingredients' => ['required', 'min:3', 'string'],
            'location_id' => ['required', 'exists:locations,id'],
            'production_date' => ['required', 'date'],
            'expire_date' => ['required', 'date','after:production_date'],
            'subLocation_id' => ['required', 'exists:sub_locations,id']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::create([
            'name' => $request->name,
            'qty' => $request->qty,
            'alert_qty' => $request->alert_qty,
            'ingredients' => $request->ingredients,
            'location_id' => $request->location_id,
            'production_date' => $request->production_date,
            'expire_date'=>$request->expire_date,
            'subLocation_id' => $request->subLocation_id
        ]);
        // locationUrl to show details
        $productUrl = route('products.show', $product->id);
        // generateQrcode() is helper function in Helpers folder for clean code {Don`t repet your self}
        $product->qr_code = generateQrcode($productUrl);
        $product->save();
        // Return the path to the generated QR code
        return response()->json([
            'success' => 'product Created Successfully',
            'product' => $product,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $productWithLocation = Product::with(['subLocation.location'])->find($id);
        if (!$productWithLocation) {
            return response()->json(['error' => 'product not found']);
        }

    return response()->json(['productWithLocation' => $productWithLocation], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'product not found']);
        }
        $product->update($request->all());
        return response()->json([
            'success' => 'product updeted successfully',
            'product' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'success' => 'product deleted successfully'
        ], 200);
    }
}
