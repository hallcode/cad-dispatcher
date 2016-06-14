<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use SoftDeletes;

    // Incident Many Many Tag relation
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    // Relationship to users assigned to this incident
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    // Network the incident belongs to
    public function network()
    {
        return $this->belongsTo('App\Network');
    }

    // Type of incident
    public function type()
    {
        return $this->belongsTo('App\Type');
    }

    // The incident's grade
    public function grade()
    {
        return $this->belongsTo('App\Grade');
    }

    // The user that created the incident
    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    // Location of the incident
    public function location()
    {
        return $this->belongsTo('App\Location');
    }
}
