<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory, HasApiTokens;

    protected $primaryKey = 'CustomerID';
    protected $fillable = ['Name', 'Phone', 'Email', 'Password'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'CustomerID');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'CustomerID');
    }
}
