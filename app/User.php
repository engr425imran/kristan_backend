<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Auth;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name' , 'phone_number' , 'user_type' , 'profile' , 'email', 'password', 'company', 'location', 'created_by', 'otp','first_login','is_verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','otp','email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    // protected $appends = ['rating'];
    //  public function getRatingAttribute()
    // {
    //     $rating = 0;
    //     $vehicel_requests = array();
    //     if(Auth::user()->user_type == 1){
    //         $vehicel_requests = VehicleRequest::where(['valet_manager'=>auth()->user()->id])->pluck('id');
    //     }elseif(Auth::user()->user_type == 2){
    //         $vehicel_requests = VehicleRequest::where(['valet'=>auth()->user()->id])->pluck('id');
    //     }

    //     return $rating = Feedback::whereIn('request_id', $vehicel_requests)->avg('rating');
       
    // }

    // public function getCompanyAttribute()
    // {
    //     $company_name = "N/A";
    //     if($this->created_by > 0){
    //         $manager = User::where(['id'=> $this->created_by])->first();
    //         if(!empty($manager)){
    //             $company_name = !empty($manager->company_name) ? $manager->company_name : 'N/A';
    //         }
    //     }else{
    //         $company_name = $this->company_name;
    //     }

    //     return $company_name;
    // }
    
}
