<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactPref extends Model
{
    // Cascade updates
    protected $touches = ['user'];
    
    // Set the correct table
    protected $table = "contact_prefs";
    
    // The user this set of preferences relates to.
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // The object this set of preferences relates to.
    // Can be used to 
    public function object()
    {
        return $this->morphTo();
    }
}
