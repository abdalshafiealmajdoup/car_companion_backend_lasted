<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
    public function index() {
        return response()->json(Admin::all(), 200);
    }

    public function register(Request $request) {
        $validatedData = $request->validate([
            'Name' => 'required|string|max:255',
            'Email' => 'required|string|email|max:255|unique:admins',
            'Password' => 'required|string|min:6',
        ]);

        $validatedData['Password'] = Hash::make($validatedData['Password']);
        $admin = Admin::create($validatedData);
        $token = $admin->createToken('AdminToken')->plainTextToken;
        return response()->json(['admin' => $admin, 'token' => $token], 201);
    }
    public function login(Request $request) {
        $request->validate([
            'Email' => 'required|email',
            'Password' => 'required',
        ]);

        $admin = Admin::where('Email', $request->Email)->first();

        if (!$admin || !Hash::check($request->Password, $admin->Password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'Login successful', 'access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function show($id) {
        $admin = Admin::findOrFail($id);
        return response()->json($admin, 200);
    }

    public function update(Request $request, $id) {
        $validatedData = $request->validate([
            'Name' => 'sometimes|required|string|max:255',
            'Email' => 'sometimes|required|string|email|max:255|unique:admins,Email,'. $id . ',AdminID',
            'Password' => 'sometimes|required|string|min:6',
        ]);

        if (isset($validatedData['Password'])) {
            // Hash the new password if it is being updated
            $validatedData['Password'] = Hash::make($validatedData['Password']);
        }

        $admin = Admin::findOrFail($id);
        $admin->update($validatedData);
        return response()->json($admin, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        // $request->user()->tokens()->delete();
        return response()->json(['message' => 'You have successfully logged out']);
    }

    public function destroy($id) {
        Admin::findOrFail($id)->delete();
        return response()->json(['message' => 'Admin deleted successfully'], 200);
    }
}

