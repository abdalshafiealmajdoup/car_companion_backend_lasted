<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
use HasFactory;

    protected $primaryKey = 'ServiceID';
    protected $fillable = ['Name'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'ServiceID');
    }
}
