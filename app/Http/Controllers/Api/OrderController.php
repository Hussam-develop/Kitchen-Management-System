<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{


    // Product Withdraw Process
    public function withdraw(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::find($id);

        if ($product->qty < $request->quantity) {
            return response()->json(['error' => 'Not enough stock available.'], 400);
        }
        // Create an order
        $order = Order::create([
            'product_id' => $id,
            'quantity' => $request->quantity,
            // by default status is pending
        ]);

        // Prepare confirmation message data
        $confirmationData = [
            'product_name' => $product->name,
            'quantity' => $request->quantity,
            'updated_quantity' => $product->qty - $request->quantity,
        ];

        return response()->json(['success' => 'waiting for confirm ', $confirmationData], 200);
    }

    /*
    // product withdraw process {confirmition}
    */

    public function confirmWithdraw($id)
    {
        $order = Order::where('status', 'pending')->find($id);

        if (!$order) {
            return response()->json(['error' => 'order not found']);
        }
        $product = Product::find($order->product_id);
        // Update stock level
        $product->qty -= $order->quantity;
        $product->save();
        $order->status = 'completed';
        $order->save();
        return response()->json(['message' => 'Order confirmed successfully.', 'product' => $product], 200);
    }

    /*
    // product return process {waiting for confirmition}
    */
    
    public function returnProduct(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
            'return_reason' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $order = Order::where('status', 'completed')->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found or cannot be returned.'], 404);
        }

        if ($order->quantity < $request->quantity) {
            return response()->json(['error' => 'Return quantity exceeds the ordered quantity.'], 400);
        }
        $order->update([
            'quantity' => $request->quantity,
            'return_reason' => $request->return_reason,
        ]);
        // Prepare confirmation message data
        $confirmationData = [
            'product_name' => $order->product->name,
            'quantity' => $request->quantity,
            'updated_quantity' => $order->product->qty + $request->quantity,
        ];

        return response()->json(['success' => 'waiting for confirm ', 'products' => $confirmationData], 200);
    }

    /*
    // product return process {confirmation}
    */

    public function confirmReturn($id)
    {
        $order = Order::where('status', 'completed')->find($id);

        if (!$order) {
            return response()->json(['error' => 'order not found']);
        }
        $product = Product::find($order->product_id);
        // Update stock level
        $product->qty += $order->quantity;
        $product->save();
        return response()->json(['message' => 'Order confirmed successfully.', $product], 200);
    }
}
