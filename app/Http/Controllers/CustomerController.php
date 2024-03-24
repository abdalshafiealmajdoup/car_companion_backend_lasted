<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


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
}
