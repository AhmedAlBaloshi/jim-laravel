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
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'verifyLogin', 'guard', 'respondWithToken', 'resendOtp']]);
    }

    public function login(Request $request)
    {

        // $request->validate([
        //     'email' => 'required|string|email',
        //     'password' => 'required|string',
        // ]);
        // $credentials = $request->only('email', 'password');

        // $token = Auth::attempt($credentials);
        // if (!$token) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Unauthorized',
        //     ], 401);
        // }

        // $user = Auth::user();
        // return response()->json([
        //     'success' => '1',
        //     'user' => $user,
        //     'token' =>  $token,
        // ]);
        $user = User::where('country_code', $request->country_code)->where('mobile', $request->mobile)->first();
        if (!$user) {
            $user = $this->storeCustomer($request->all());
        }

        $otp = rand(1000, 9999);
        $user->login_code = $otp;
        $user->update();
        $details = [
            'title' => 'OTP for login',
            'body' => '  This is your otp ' . $otp . ''
        ];

        $phoneno = $request->country_code . $request->mobile;
        $this->sendToMobile($phoneno, $otp);
        return response()->json([
            'success' => 1,
            'otp' => $otp
        ], 200);
    }

    public function resendOtp(Request $request)
    {
        $user = User::where('country_code', $request->country_code)->where('mobile', $request->mobile)->first();

        $otp = rand(1000, 9999);
        $user->login_code = $otp;
        $user->update();
        $details = [
            'title' => 'OTP for login',
            'body' => '  This is your otp ' . $otp . ''
        ];

        $phoneno = $request->country_code . $request->mobile;
        $this->sendToMobile($phoneno, $otp);
        return response()->json([
            'success' => 1,
            'otp' => $otp
        ], 200);
    }

    public function verifyLogin(Request $request)
    {
        $credentials = ['mobile' => $request->mobile, 'country_code' => $request->country_code, 'login_code' => $request->otp];
        $user = User::where('mobile', $credentials['mobile'])->where('country_code', $credentials['country_code'])->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid mobile'], 401);
        }

        if ($user->login_code == $credentials['login_code']) {
            $token = $this->guard()->login($user);
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Invalid OTP'], 401);
    }

    public static function sendToMobile($phoneno, $otp)
    {
        $message = "This is your otp " . $otp;
        $mesg = urlencode($message);
        $sender = urlencode('Shaheen');
        $source = urlencode('Shaheen');
        $url = "https://tamimahsms.com/user/smspush.aspx?username=DalileeOman&password=dgc2021&phoneno=$phoneno&message=$mesg&sender=$sender&source=$source";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function storeCustomer($request)
    {

        $user = new User();
        $user->full_name = $request['full_name'];
        $user->type = 'customer';
        $user->status = '1';
        $user->country_code = $request['country_code'];
        $user->mobile = $request['mobile'];
        $user->verified = 1;
        $user->date = date('Y-m-d');
        $user->save();

        $customer = new Customer();
        $customer->user_id = $user->id;
        $customer->name = $request['full_name'];
        $customer->contact = $request['country_code'] . $request['mobile'];
        $customer->save();

        return $user;
    }

    public function profile()
    {
        $user = User::with(['customer' => function ($q) {
            return $q->select('id', 'email', 'name', 'contact', 'created_at', 'updated_at', 'user_id');
        }])->findOrFail(Auth::user()->id);
        return response()->json(['success' => 1, 'user' => $user]);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'country_code' => 'required',
            'mobile' => 'required',
            'gender' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'message' => $validator->errors()]);
        }


        $user = User::findOrFail(Auth::user()->id);
        if (!$user) {
            return response()->json(['success' => 1, 'message' => 'User not found']);
        }
        $user->full_name = $request->full_name;
        $user->country_code = $request->country_code;
        $user->mobile = $request->mobile;
        $user->gender = $request->gender;
        $user->update();
        $customer = Customer::where('user_id', $user->id)->first();
        $customer->name = $request->full_name;
        $customer->contact = $request->mobile;
        $customer->update();

        return response()->json([
            'success' => 1,
            'message' => 'User updated successfully',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 36000,
            'user' => auth()->user()

        ]);
    }

    public function guard()
    {
        return Auth::guard();
    }
}
