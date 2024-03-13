<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleRequest extends Model
{
    protected $fillable = ['customer_id', 'location', 'ticket_number', 'duration', 'valet_manager', 'valet', 'status', 'processed'];
    protected $appends = ['clientname', 'Valetname', 'state'];
    // protected $appends = ['clientname', 'Valetname','state','Ratestatus'];
    public function getStateAttribute()
    {

        if ($this->status == 1) {
            return "Completed";
        }
        if ($this->valet == NULL) {
            return "pending";
        } else {
            return "InProgress";
        }
    }
    public function customer()
    {
        return $this->hasOne(User::class, 'id', 'customer_id');
    }
    public function valetdetails()
    {
        return $this->hasOne(User::class, 'id', 'valet');
    }

    public function getClientNameAttribute()
    {
        if ($this->customer_id) {
            $user =  User::Where(['id' => $this->customer_id])->first();
            return $user->first_name . ' ' . $user->last_name;
        }
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'request_id', 'id');
    }

    public function getValetNameAttribute()
    {
        if ($this->valet) {
            $user =  User::Where(['id' => $this->valet])->first();
            return $user->first_name . ' ' . $user->last_name;
        }
    }

    //  public function getRateStatusAttribute()
    // {
    //     $status = false;
    //     $feedback = Feedback::where(['request_id'=> $this->id])->first();
    //     if(!empty($feedback)){
    //         $status = true;
    //     }
    //     return $status;
    // }
}
