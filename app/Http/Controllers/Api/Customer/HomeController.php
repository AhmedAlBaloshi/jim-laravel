<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('uid', $request->user_id)->whereIn('status', ['ongoing', 'accepted'])->get();
        $storeTypes = StoreType::where('status', 1)->LIMIT(3)->get();
        $offers = Offer::where('expire', '>=', date('Y-m-d'))->get();
        $featureProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->limit(2)
            ->get();
        $restaurants = Store::where('type_id', 4)->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->limit(10)
            ->get();

        return response()->json(['success' => 1, 'storeTypes' => $storeTypes, 'featureProducts' => $featureProducts, 'restaurants' => $restaurants, 'orders' => $orders, 'offers' => $offers]);
    }
}
