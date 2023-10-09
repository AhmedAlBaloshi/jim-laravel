<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\BillingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BillingAddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $address = BillingAddress::where('customer_id', Auth::user()->id)->get();
        return response()->json(['success' => 1, 'address' => $address]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|integer',
            'first_name' => 'required|string|min:3|max:255',
            'last_name' => 'required|string|min:3|max:255',
            // 'company_name' => 'nullable|string|max:255',
            'address' => 'required|string|min:3|max:255',
            'town_city' => 'required|string|min:3|max:255',
            'state_country' => 'required|string|min:3|max:255',
            'post_zip' => 'required|string|min:3|max:20',
            'email_address' => 'required|email',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => 0, 'message' => $validator->errors()]);
        }

        $billingAddress = BillingAddress::create($request->all());
        return response()->json(['success' => 1, 'address' => $billingAddress]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|integer',
            'first_name' => 'required|string|min:3|max:255',
            'last_name' => 'required|string|min:3|max:255',
            'address' => 'required|string|min:3|max:255',
            'town_city' => 'required|string|min:3|max:255',
            'state_country' => 'required|string|min:3|max:255',
            'post_zip' => 'required|string|min:3|max:20',
            // 'email_address' => 'required|email',
            'phone' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => 0, 'message' => $validator->errors()]);
        }

        $address = BillingAddress::findOrFail($id);

        if (!$address) {
            return response()->json(['success' => 0, 'message' => 'Billing address not found']);
        }

        $address->update($request->all());
        return response()->json(['success' => 1, 'address' => $address]);
    }

    public function destroy($id)
    {
        $billingAddress = BillingAddress::find($id);

        if (!$billingAddress) {
            return response()->json(['success' => 0, 'message' => 'Billing address not found']);
        }

        $billingAddress->delete();
        return response()->json(['success' => 1, 'message' => 'Billing address deleted successfully']);
    }
}
