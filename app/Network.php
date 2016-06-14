<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    // Grades - grades belonging to this network.
    public function grades()
    {
        return $this->hasMany('App\Grade');
    }

    // Incidents in the network
    public function incidents()
    {
        return $this->hasMany('App\Incident');
    }
}
