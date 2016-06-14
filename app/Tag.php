<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use SoftDeletes;
    
    // Incidents Many Many relationship
    public function incidents()
    {
        return $this->belongsToMany('App\Incident');
    }
}
