<?php

namespace App\Http\Controllers;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index() {
        return response()->json(Service::all(), 200);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'Name' => 'required|string|max:100|unique:services',
        ]);

        $service = Service::create($validatedData);
        return response()->json($service, 201);
    }

    public function show($id) {
        $service = Service::findOrFail($id);
        return response()->json($service, 200);
    }

    public function update(Request $request, $id) {
        $validatedData = $request->validate([
            'Name' => 'required|string|max:100|unique:services,Name,'.$id,
        ]);

        $service = Service::findOrFail($id);
        $service->update($validatedData);
        return response()->json($service, 200);
    }

    public function destroy($id) {
        Service::findOrFail($id)->delete();
        return response()->json(['message' => 'Services deleted successfully'], 200);
    }
}

