<?php

namespace App\Http\Controllers;
use App\Models\ServiceCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class ServiceCenterController extends Controller
{
    public function index() {
        return response()->json(ServiceCenter::all(), 200);
    }
    public function register(Request $request) {
        $validatedData = $request->validate([
            'Name' => 'required|string|max:255',
            'Phone' => 'required|string|max:20|unique:service_centers',
            'Email' => 'required|string|email|max:255|unique:service_centers',
            'ServicesOffered' => 'required|array',
            'CarTypesServiced' => 'required|array',
            'City' => 'required|string',
            'Region' => 'required|string',
            'Password' => 'required|string|min:6',
        ]);

        // تحويل ServicesOffered و CarTypesServiced إلى JSON
        $validatedData['ServicesOffered'] = json_encode($validatedData['ServicesOffered']);
        $validatedData['CarTypesServiced'] = json_encode($validatedData['CarTypesServiced']);

        $validatedData['Password'] = Hash::make($request->Password);

        $serviceCenter = ServiceCenter::create($validatedData);

        $token = $serviceCenter->createToken('serviceCenterToken')->plainTextToken;

        return response()->json(['service_center' => $serviceCenter, 'token' => $token], 201);
    }


    public function login(Request $request) {
        $credentials = $request->validate([
            'Email' => 'required|email',
            'Password' => 'required',
        ]);

        $serviceCenter = ServiceCenter::where('Email', $credentials['Email'])->first();

        if (! $serviceCenter || !Hash::check($credentials['Password'], $serviceCenter->Password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = $serviceCenter->createToken('ServiceCenterToken')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
             'access_token' => $token,
              'token_type' => 'Bearer',
            'serviceCenterName'=> $serviceCenter->Name,
            'serviceCenterPhone'=> $serviceCenter->Phone,
            'serviceCenterEmail'=> $serviceCenter->Email,
            'serviceCenterID'=> $serviceCenter->CenterID,
            ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        // $request->user()->tokens()->delete();
        return response()->json(['message' => 'You have successfully logged out']);
    }

    public function show($id) {
        $serviceCenter = ServiceCenter::findOrFail($id);
        return response()->json($serviceCenter, 200);
    }

    public function update(Request $request, $id) {
        $validatedData = $request->validate([
            'Name' => 'sometimes|required|string|max:255',
            'Email' => 'sometimes|required|string|email|max:255',
            'Password' => 'sometimes|required|string|min:6',
        ]);
        
        $serviceCenter = ServiceCenter::findOrFail($id);
            $serviceCenter->Name = $validatedData['Name'];
            $serviceCenter->Email = $validatedData['Email'];
            $serviceCenter->Password = Hash::make($validatedData['Password']);
        $serviceCenter->save();
        return response()->json($serviceCenter, 200);
    }

    public function destroy($id) {
        ServiceCenter::findOrFail($id)->delete();
        return response()->json(['message' => 'ServiceCenter deleted successfully'], 200);
    }
}
