<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => 0, 'message' => $validator->errors()]);
        }

        $credentials = ['email' => $request->email, 'password' => $request->password];
        if (Auth::guard('user')->attempt($credentials)) {
            $customer = Auth::guard('user')->user();

            return response()->json(['success' => 1, 'user' => $customer]);
        } else {
            return response()->json(['success' => 0, 'message' => 'Invalid credentials.']);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'gender' => 'required',
            'country_code' => 'required',
            'mobile' => 'required',
            'address' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'message' => $validator->errors()]);
        }

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->gender = $request->gender;
        $user->type = 'customer';
        $user->status = '1';
        $user->country_code = $request->country_code;
        $user->mobile = $request->mobile;
        $user->verified = 1;
        $user->date = date('Y-m-d');
        $user->save();

        $customer = new Customer();
        $customer->user_id = $user->id;
        $customer->name = $request->first_name . ' ' . $request->last_name;
        $customer->email = $request->email;
        $customer->password = bcrypt($request->password);
        $customer->contact = $request->country_code . $request->mobile;
        $customer->address = $request->address;
        $customer->save();

        return response()->json(['success' => 1, 'user' => [$user]]);
    }
}
