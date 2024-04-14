<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;


class CustomerController extends Controller
{
    public function index() {
        return response()->json(Customer::all(), 200);
    }

    public function register(Request $request) {
        $validatedData = $request->validate([
            'Name' => 'required|max:255',
            'Phone' => 'required|unique:customers|max:20',
            'Email' => 'required|email|unique:customers|max:255',
            'Password' => 'required|max:255',
        ]);
        $validatedData['Password'] = Hash::make($validatedData['Password']);
        $customer = Customer::create($validatedData);
        $token = $customer->createToken('auth_token')->plainTextToken;
        return response()->json(['data' => $customer, 'access_token' => $token, 'token_type' => 'Bearer'], 201);
    }
    public function login(Request $request) {
        $request->validate([
            'Email' => 'required|email',
            'Password' => 'required',
        ]);
        $customer = Customer::where('Email', $request->Email)->first();
        if (!$customer || !Hash::check($request->Password, $customer->Password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $token = $customer->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login successful',
             'access_token' => $token,
              'token_type' => 'Bearer',
               'CustomerID'=>$customer->CustomerID,
               'Name'=>$customer->Name,
               'Phone'=>$customer->Phone,
               'Email'=>$customer->Email,
            ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        // $request->user()->tokens()->delete();
        return response()->json(['message' => 'You have successfully logged out']);
    }

    public function show($id) {
        $customer = Customer::findOrFail($id);
        return response()->json($customer, 200);
    }

    public function update(Request $request, $id) {


        $validatedData = $request->validate([
            'Name' => 'sometimes|required|max:255',
            'Phone' => 'sometimes|required|max:20',
            'Email' => 'sometimes|required|string|email|max:255',
            'Password' => 'sometimes|required|max:255',
        ]);

        $customer = Customer::find($id);
        $customer->update($request->all());
        // Log::info('Updating customer: ', $request->all());
        return response()->json($customer, 200);
    }

    public function destroy($id) {
        Customer::findOrFail($id)->delete();
    return response()->json(['message' => 'Customer deleted successfully'], 200);
    }

    public function getOrdersList($customer_id){
        $orders = Order::where('CustomerID',$customer_id)->get();
        return response()->json($orders, 200);
    }
    public function sendResetCode(Request $request) {
        $request->validate([
            'Phone' => 'required|regex:/^2189[1234]\d{7}$/',
        ]);
        $user = Customer::where('Phone', $request->Phone)->firstOrFail();
        $otp = rand(100000, 999999); // Generate a 6-digit OTP

        // تخزين الـ OTP
        Cache::put('otp_'.$user->Phone, $otp, now()->addMinutes(5)); // يخزن الـ OTP لمدة 5 دقائق

        $response = $this->sendSms($request->Phone, "تم إرسال رمز التحقق الخاص بك
        رمز التحقق: $otp (رفيق السيارة)");

                return response()->json(['message' => 'OTP sent successfully', 'sms_response' => $response]);
    }


    public function verifyOtp(Request $request) {
        $request->validate([
            'Phone' => 'required|regex:/^2189[1234]\d{7}$/',
            'otp' => 'required|digits:6',
        ]);

        $user = Customer::where('Phone', $request->Phone)->firstOrFail();

        // استرجاع الـ OTP
        $correctOtp = Cache::get('otp_'.$user->Phone);

        if ($request->otp == $correctOtp) {
            Cache::forget('otp_'.$user->Phone);  // Optional: Clear OTP from storage after successful verification
            return response()->json(['message' => 'OTP verified successfully']);
        } else {
            return response()->json(['message' => 'Invalid OTP'], 401);
        }
    }


    public function resetPassword(Request $request) {
        $request->validate([
            'Phone' => 'required',
            'new_password' => 'required|min:6',
        ]);

        // Assuming OTP has already been verified
        $user = Customer::where('Phone', $request->Phone)->firstOrFail();
        $user->update(['Password' => Hash::make($request->new_password)]);
        return response()->json(['message' => 'Password reset successfully']);
    }

    protected function retrieveOtp($user) {
        // Implement logic to retrieve OTP from your storage (e.g., cache or database)
        // Placeholder logic
        return '123456';  // Example OTP
    }

    protected function clearOtp($user) {
        // Implement logic to clear OTP after verification
    }


    protected function sendSms($phone, $message) {
        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://sms.techlines.ly/api/v1/send-sms', [
            'form_params' => [
                'receiver' => $phone,
                'message' => $message,
            ],
            'headers' => [
                'username' => 'otp-sms',
                'password' => 'R2Y4V6YNM#$_wRfvn',
            ],
        ]);
        return $response->getBody()->getContents();
    }

}
