<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upload extends Model
{
    use SoftDeletes;

    // User who uploaded the file
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
