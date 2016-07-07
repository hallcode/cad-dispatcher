<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use Auth;
use App\Incident;
use App\Status;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function networks()
    {
        return view('networks');
    }

    // Internal API
    public function apiIncidents()
    {
        $incidents = Auth::user()->incidents;

        $array = [];

        foreach ($incidents as $incident)
        {
            $array[] = [
                "id" => $incident->id,
                "link" => route('incident.show', ['network' => $incident->network->code, 'date' => camel_case($incident->set_date), 'ref' => $incident->ref]),
                "network_id" => $incident->network->id,
                "network_code" => $incident->network->code,
                "network_name" => $incident->network->name,
                "network_link" => route('network.show', ['network' => $incident->network->code]),
                "ref" => $incident->set_date . ' / ' . $incident->ref,
                "type" => $incident->type->name,
                "dets" => str_limit($incident->dets, 150),
                "updates" => $incident->updates->count(),
                "grade_name" => $incident->grade->name,
                "grade_color" => $incident->grade->color,
                "location" => $incident->location->formatted_address,
                "due_in" =>  $incident->due_in,
                "due_in_raw" =>  $incident->due_in_raw,
                "is_overdue" => $incident->is_overdue,
            ];
        }

        return json_encode($array);
    }

    public function userUpdate()
    {
        $statuses = Status::get();
        
        return view('update', ['statuses' => $statuses, 'page_title' => 'Update Status & Location']);
    }

    public function storeUserUpdate(Request $request)
    {
        if (!empty($request->formatted_address))
        {
            // User supplied an address, update it.
            Auth::user()->setLocation($request->formatted_address, $request->type, $request->lat, $request->lng, $request->location_note);
        }

        if (!empty($request->status))
        {
            // User supplied an status.
            Auth::user()->setStatus($request->status);
        }

        return redirect(route('me.incidents'));
    }
}
