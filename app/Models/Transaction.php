<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $table = 'transactions';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'ride_id',
        'payment_intent_id',
        'amount',
        'customer_id',
        'payment_method_id',
        'currency',
        'description',
    ];

    public function scopeRecent($query)
    {
        return $query->where('created_at', '>', (new \Carbon\Carbon)->submonths(1) );
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\User', 'sender_id');
    }

    public function ride()
    {
        return $this->belongsTo('App\Models\Ride', 'ride_id');
    }
}
