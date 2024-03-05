<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = ['request_id', 'customer_id', 'valet_id', 'rating', 'message', 'tip', 'tip_type', 'read_by_valet'];
    protected $appends = ['clientname'];
    public function getClientNameAttribute()
    {
        return User::Where(['id' => $this->customer_id])->pluck('first_name')->first();
    }

    public function vehicle_request()
    {
        return $this->belongsTo(VehicleRequest::class, 'request_id', 'id');
    }
}
