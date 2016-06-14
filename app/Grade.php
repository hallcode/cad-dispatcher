<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use SoftDeletes;

    // Network this grade can be used in
    public function network()
    {
        return $this->belongsTo('App\Network');
    }
}
