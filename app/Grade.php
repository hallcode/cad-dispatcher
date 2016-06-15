<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use SoftDeletes;

    // Network this grade can be used in
    public function network()
    {
        return $this->belongsTo('App\Network');
    }
}
