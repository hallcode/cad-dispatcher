<?php

namespace App\Policies;

use Auth;

use App\User;
use App\Network;
use Illuminate\Auth\Access\HandlesAuthorization;

class NetworkPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function mod(User $user, Network $network)
    {
        if ($network->users->contains(Auth::user()->id) && $network->users->first()->pivot->is_mod == true)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function view(User $user, Network $network)
    {
        if ($network->users->contains(Auth::user()->id) && $network->users->first()->pivot->is_accepted)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function accept(User $user, Network $network)
    {
        if ($network->users->contains(Auth::user()->id) && $network->users->first()->pivot->is_accepted == false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
