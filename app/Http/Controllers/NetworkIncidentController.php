<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Network;
use App\Incident;
use Carbon\Carbon;
use Mapper;

class NetworkIncidentController extends Controller
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
    public function show($network_code, $incident_date, $incident_ref)
    {
        // Get Network
        $network = Network::get()->where('code', $network_code)->first();

        // Get Incident
        $incident = Incident::withTrashed()->get()->filter(function ($value, $key) use ($incident_date) {
            return $value->set_date == date('d M Y', strtotime($incident_date));
        })->where('ref', $incident_ref)->where('network_id', $network->id)->first();

        // Generate Map
        $map = Mapper::map($incident->location->lat, $incident->location->lng);
        $map->informationWindow($incident->location->lat, $incident->location->lng, 'Incident: ' . $incident->set_date .' / ' . $incident->ref,
            ['markers' => ['icon' => '/cad/public/markers/incident.png']]
        );

        // Update Markers
        foreach ($incident->updates as $update)
        {
            if (($incident->location->lat != $update->location->lat && $incident->location->lng != $update->location->lng) && $update->location_id != null)
            {
                $map->informationWindow($update->location->lat, $update->location->lng, 'Update: ',
                    ['markers' => ['icon' => '/cad/public/markers/update.png']]
                );
            }
        }

        return view('incident.show', ['network' => $network, 'incident' => $incident, 'map' => $map]);
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
}
