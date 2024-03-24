<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
class ServiceCenter extends Model
{
    use HasApiTokens;
    protected $primaryKey = 'CenterID';
    protected $fillable = ['Name', 'Phone', 'Email', 'ServicesOffered', 'CarTypesServiced','City','Region', 'Password'];

    protected $appends = ['services_list'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'CenterID');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'CenterID');
    }

    public function getServicesListAttribute(){
        $services_list = json_decode($this->ServicesOffered); 
        // $services = Service::whereIn('ServiceID',$services_list)->get()->pluck('Name');
        $services = Service::whereIn('ServiceID',$services_list)->get()->map(function ($service_name){
            return $service_name->Name.", ";
        });

        return $services;
    }
}

