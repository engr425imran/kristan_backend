<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['valet','valet_manager', 'status','amount'];
    
    public function valet()
    {
        return $this->belongsTo(User::class, 'valet','id');
    }
}
