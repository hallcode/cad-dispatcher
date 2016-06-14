<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactPref extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function object()
    {
        return $this->morphTo();
    }
}
