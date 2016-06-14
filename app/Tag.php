<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    // Incidents Many Many relationship
    public function incidents()
    {
        return $this->belongsToMany('App\Incident');
    }
}
