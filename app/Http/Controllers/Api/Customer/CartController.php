<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = Cart::with('products', 'products.category')->where('customer_id', $request->user_id)->get();
        return response()->json(['success' => 1, 'cartItems' => $cartItems]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'datetime' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => 0, 'message' => $validator->errors()]);
        }

        $cartItem = new Cart();
        $cartItem->customer_id = $request->customer_id;
        $cartItem->pid = $request->product_id;
        $cartItem->pqty = $request->quantity;
        $cartItem->price = $request->price;
        $cartItem->price_type = 'single';
        $cartItem->datetime = $request->datetime;
        $cartItem->save();
        return response()->json(['success' => 1, 'cartItems' => $cartItem]);
    }

    public function destroy($id)
    {
        $cartItem = Cart::find($id);

        if (!$cartItem) {
            return response()->json(['success' => 0, 'message' => 'Cart item not found']);
        }

        $cartItem->delete();
        return response()->json(['success' => 1, 'message' => 'Cart item deleted successfully']);
    }
}
