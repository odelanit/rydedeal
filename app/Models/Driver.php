<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table = 'users';

    public function acceptedRides()
    {
        return $this->hasMany('App\Models\Ride', 'driver_id');
    }
}
