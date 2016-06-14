<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    // Updates incident too
    protected $touches = ['incident'];
    
    // Incident this update is for
    public function incident()
    {
        return $this->belongsTo('App\Incident');
    }

    // Location of update
    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    // Users this update is from
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
