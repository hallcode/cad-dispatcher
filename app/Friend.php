<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Friend extends Model
{
    use SoftDeletes;
    
    // Set to the correct table
    protected $table = "friends_list";

    // Friender - the person who makes the friend request
    public function friender()
    {
        return $this->belongsTo('App\User', 'friender_id');
    }

    // Friendee - the person who recieves the friend request
    public function friendee()
    {
        return $this->belongsTo('App\User', 'friender_id');
    }
}
