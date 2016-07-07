<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Network;
use App\User;
use App\Status;
use Auth;

class NetworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $networks = Network::get()->filter(function($value, $key) {
            if ($value->public == 0)
            {
                if ($value->users->contains(Auth::user()->id))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return true;
            }
        });
        return view('network.index', ['page_title' => 'All Networks', 'networks' => $networks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        $network = Network::findFromCode($code);

        if (Auth::user()->cannot('view', $network))
        {
            return abort(403);
        }

        return view('network.show', ['network' => $network, 'page_title' => $network->name]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function join($code)
    {
        $network = Network::findFromCode($code);

        if ($network->public)
        {
            $network->users()->attach( [ Auth::user()->id => ['is_accepted' => 1] ] );
            return redirect(route('n.show', ['n' => $network->code]));
        }
        else
        {
            return abort(403);
        }
    }

    public function accept($code)
    {
        $network = Network::findFromCode($code);

        if (Auth::user()->can('accept', $network))
        {
            $network->users()->updateExistingPivot(Auth::user()->id, ['is_accepted' => 1] );
            return redirect(route('n.show', ['n' => $network->code]));
        }
        else
        {
            return abort(403);
        }
    }

    public function leave($code)
    {
        $network = Network::findFromCode($code);

        if (Auth::user()->can('view', $network))
        {
            $network->users()->detach(Auth::user()->id);
            return redirect(route('n.index'));
        }
        else
        {
            return abort(403);
        }
    }

    public function updateUsers($code)
    {
        $network = Network::findFromCode($code);

        $statuses = Status::get();

        if (Auth::user()->can('mod', $network))
        {
            return view('network.updateUsers', [
                'page_title' => 'Update Users',
                'network' => $network,
                'statuses' => $statuses,
            ]);
        }
        else
        {
            return abort(403);
        }
    }

    public function storeUpdateUsers(Request $request, $code)
    {
        $network = Network::findFromCode($code);

        // Validate form
        $this->validate($request, [
            'users' => 'required',
        ]);

        // Get Users
        $users = $network->users->whereInLoose('id', explode(',', $request->users));  

        if (Auth::user()->can('mod', $network))
        {
            if (!empty($request->formatted_address))
            {
                // User supplied an address, update it.
                // Or, how to make 100 db requests at once
                foreach ($users as $user)
                {
                    $user->setLocation($request->formatted_address, $request->type, $request->lat, $request->lng, $request->location_note);
                }
            }

            if (!empty($request->status))
            {
                // User supplied an status.
                foreach ($users as $user)
                {
                    $user->setStatus($request->status);
                }
            }

            return redirect(route('n.show', ['n' => $network->code]));
        }
        else
        {
            return abort(403);
        }
    }
}
