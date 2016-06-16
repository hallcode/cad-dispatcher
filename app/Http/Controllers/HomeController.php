<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use Auth;
use App\Incident;

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
                "link" => url('/incident/'.$incident->id),
                "network_id" => $incident->network->id,
                "network_code" => $incident->network->code,
                "network_name" => $incident->network->name,
                "network_link" => url('/network/'.$incident->network->id),
                "ref" => $incident->set_date . ' / ' . $incident->ref,
                "type" => $incident->type->name,
                "dets" => $incident->dets,
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
}
