<?php

namespace App\Http\Controllers;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index() {
        return response()->json(Address::all(), 200);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'CustomerID' => 'nullable|exists:customers,CustomerID',
            'CenterID' => 'nullable|exists:service_centers,CenterID',
            'Country' => 'required|string|max:100',
            'City' => 'required|string|max:100',
            'District' => 'required|string|max:100',
            'Street' => 'required|string|max:255',
            'Building' => 'required|string|max:50',
            'ZipCode' => 'required|string|max:20',
            'Latitude' => 'required|numeric',
            'Longitude' => 'required|numeric',
            'AdditionalInfo' => 'nullable|string',
        ]);

        $address = Address::create($validatedData);
        return response()->json($address, 201);
    }

    public function show($id) {
        $address = Address::findOrFail($id);
        return response()->json($address, 200);
    }

    public function update(Request $request, $id) {
        $validatedData = $request->validate([
            'CustomerID' => 'nullable|exists:customers,CustomerID',
            'CenterID' => 'nullable|exists:service_centers,CenterID',
            'Country' => 'sometimes|required|string|max:100',
            'City' => 'sometimes|required|string|max:100',
            'District' => 'sometimes|required|string|max:100',
            'Street' => 'sometimes|required|string|max:255',
            'Building' => 'sometimes|required|string|max:50',
            'ZipCode' => 'sometimes|required|string|max:20',
            'Latitude' => 'sometimes|required|numeric',
            'Longitude' => 'sometimes|required|numeric',
            'AdditionalInfo' => 'nullable|string',
        ]);

        $address = Address::findOrFail($id);
        $address->update($validatedData);
        return response()->json($address, 200);
    }

    public function destroy($id) {
        Address::findOrFail($id)->delete();
        return response()->json(['message' => 'Address deleted successfully'], 200);
    }
}
