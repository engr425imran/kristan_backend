<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DistributedTip extends Model
{
    protected $fillable = ['tip','valet', 'valet_manager'];
}
