<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        return response()->json(Order::all(), 200);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'CustomerID' => 'required|exists:customers,CustomerID',
            'ServiceID' => 'required|exists:services,ServiceID',
            'CenterID' => 'required|exists:service_centers,CenterID',
            'CarType' => 'required|nullable|string|max:100',
            'orderCarType' => 'required|nullable|string|max:100',  
            'PhoneNumber' => 'required|nullable|string|max:20',
            'Email' => 'required|email|max:255',
            'GooglePlaceID' => 'required|nullable|string|max:255',
            'CustomerNotes' => 'required|nullable|string',
            'City' => 'required',
            'CityName' => 'required',
            'Region' => 'required',
            'areaName' => 'required',
            'StatusOrder' => 'required|string|max:50',
        ]);
        $validatedData['CarType'] = $validatedData['orderCarType'];
        $validatedData['City'] = $validatedData['CityName'];
        $validatedData['Region'] = $validatedData['areaName'];
        $order = Order::create($validatedData);
        return response()->json($order, 201);
    }


    public function show($id) {
        $order = Order::findOrFail($id);
        return response()->json($order, 200);
    }

    public function update(Request $request, $id) {
        $validatedData = $request->validate([
            'CustomerID' => 'required|exists:customers,CustomerID',
            'ServiceID' => 'required|exists:services,ServiceID',
            'CenterID' => 'required|exists:service_centers,CenterID',
            'CarType' => 'required|nullable|string|max:100',
            'PhoneNumber' => 'required|nullable|string|max:20',
            'Email' => 'required|email|unique:orders|max:255',
            'GooglePlaceID' => 'required|nullable|string|max:255',
            'CustomerNotes' => 'required|nullable|string',
            'City' => 'required',
            'Region' => 'required',
            'StatusOrder' => 'required|string|max:50',
        ]);

        $order = Order::findOrFail($id);
        $order->update($validatedData);
        return response()->json($order, 200);
    }

    public function destroy($id) {
        Order::findOrFail($id)->delete();
        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}
