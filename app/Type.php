<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    // Networks this type is attached to
    public function network()
    {
        return $this->belongsTo('App\Network');
    }
}
