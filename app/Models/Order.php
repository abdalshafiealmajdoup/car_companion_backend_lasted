<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'OrderID';
    protected $appends = ['service_name'];
    protected $fillable = [
        'CustomerID', 'ServiceID','CenterID', 'CarType', 'PhoneNumber', 'Email', 'GooglePlaceID', 'CustomerNotes', 'City', 'Region', 'StatusOrder'
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID');
    }

    public function serviceCenter()
    {
        return $this->belongsTo(ServiceCenter::class, 'CenterID');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'ServiceID');
    }

    public function getServiceNameAttribute(){
        return $this->service->Name;
    }
}
