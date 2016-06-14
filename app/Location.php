<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    // Incidents at this location
    public function incidents()
    {
        return $this->hasMany('App\Incident');
    }
}
