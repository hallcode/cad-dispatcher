<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'sms', 'ref', 'avatar_id', 'can_create_users', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Contact Preferences
    public function prefs()
    {
        return $this->hasMany('App\ContactPrefs');
    }

    // Friends List
    public function following()
    {
        return $this->belongsToMany('App\User', 'friends_list', 'friender_id', 'friendee_id');
    }

    public function followers()
    {
        return $this->belongsToMany('App\User', 'friends_list', 'friendee_id', 'friender_id');
    }
}
