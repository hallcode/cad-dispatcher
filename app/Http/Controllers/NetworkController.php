<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Network;
use App\User;

class NetworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $network = Network::where('code', $code)->get();

        return view('network.show');
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

    public function SMS($network_id)
    {
        // $alex = User::find(1);
        // $network = Network::find($network_id);

        // $incident = $alex->incidents->last();

        // return var_dump($alex->sendSMS($incident->set_date . '/' . $incident->ref . ' - ' . $incident->grade->name . ' ' . $incident->type->name . ' - ' . str_limit($incident->dets, 75) . ' Location: ' . $incident->location->formatted_address, $network));

    }

    public function email()
    {
        // $alex = User::find(1);
        // $incident = $alex->incidents->last();

        // $message = 'The details of the incident are: ';
        // $message .= $incident->dets;
        // $message .= ' Location: ' . $incident->location->formatted_address;

        // $alex->sendEmail('New Incident: ' . $incident->set_date . ' / ' . $incident->ref, 'You have been assigned to a new incident', $message, 1);
    }
}
