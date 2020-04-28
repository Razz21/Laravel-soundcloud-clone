<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    protected $guarded = [];

    protected $casts = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
