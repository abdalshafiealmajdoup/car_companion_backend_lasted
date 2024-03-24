<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $primaryKey = 'AddressID';
    protected $fillable = ['CustomerID', 'CenterID', 'Country', 'City', 'District', 'Street', 'Building', 'ZipCode', 'Latitude', 'Longitude', 'AdditionalInfo'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID');
    }

    public function serviceCenter()
    {
        return $this->belongsTo(ServiceCenter::class, 'CenterID');
    }
}
