<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\ServiceCenter;
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

        $center = ServiceCenter::find($validatedData['CenterID']);
        if ($center) {
            $orderServiceName = $order->service_name;
            $message = "تم إرسال طلب خدمة جديد
            نوع الخدمة: " . $orderServiceName . "
            رقم هاتف الزبون: " . $order->PhoneNumber . "
            رفيق السيارة ";
                        $this->sendSms($center->Phone, $message);
        } else {
        }

        return response()->json($order, 201);
    }


    public function show($id) {
        $order = Order::findOrFail($id);
        return response()->json($order, 200);
    }

    public function update(Request $request, $id) {
        // Validate only the fields that need updating
        $validatedData = $request->validate([
            'StatusOrder' => 'required|string|max:50',
            'CustomerNotes' => 'nullable|string',
        ]);

        // Find the order by ID
        $order = Order::findOrFail($id);

        // Update only the specified fields
        $order->StatusOrder = $validatedData['StatusOrder'];
        if(isset($validatedData['CustomerNotes'])) {
            $order->CustomerNotes = $validatedData['CustomerNotes'];
        }

        // Save the changes
        $order->save();

        // Return the updated order
        return response()->json($order, 200);
    }


    public function destroy($id) {
        Order::findOrFail($id)->delete();
        return response()->json(['message' => 'Order deleted successfully'], 200);
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
