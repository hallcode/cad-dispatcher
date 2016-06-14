<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // Users who have used this status
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
