<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Network extends Model
{
    use SoftDeletes;

    public static function findFromCode($code)
    {
        $networks = Network::where('code', $code)->get();

        if ($networks->count() == 0)
        {
            return abort(404);
        }
        else
        {
            return $networks->first();
        }
    }

    // Grades - grades belonging to this network.
    public function grades()
    {
        return $this->hasMany('App\Grade');
    }

    // Types allowed in the network
    public function types()
    {
        return $this->hasMany('App\Type');
    }

    // Incidents in the network
    public function incidents()
    {
        return $this->hasMany('App\Incident');
    }

    // Users belonging to the network
    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('is_mod', 'is_accepted');
    }

    // The user that created the network
    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

}
