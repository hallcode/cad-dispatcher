<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Network;
use App\Incident;
use App\Location;
use App\Update;
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
        $incident = Incident::findFromRef($network->id, $incident_date, $incident_ref);

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
                $map->informationWindow($update->location->lat, $update->location->lng, 'Update',
                    ['markers' => ['icon' => '/cad/public/markers/update.png']]
                );
            }
        }

        // User Markers
        foreach ($incident->users as $user)
        {
            if ($incident->location->lat != $user->locations->last()->lat && $incident->location->lng != $user->locations->last()->lng)
            {
                $map->informationWindow($user->locations->last()->lat, $user->locations->last()->lng, $user->label,
                    ['markers' => ['icon' => '/cad/public/markers/user_dealing.png']]
                );
            }
        }

        return view('incident.show', [
            'network' => $network,
            'incident' => $incident,
            'map' => $map,
            'page_title' => $incident->set_date . ' / ' . $incident->ref, 
        ]);
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

    /**
     * Show the form to create an update
     *
     * @param string $network_code
     * @param string $incident_date
     * @param int $incident_ref
     * @return \Illuminate\Http\Response
     */
     public function addUpdate($network_code, $incident_date, $incident_ref)
     {
        // Get Network
        $network = Network::get()->where('code', $network_code)->first();

        // Get Incident
        $incident = Incident::findFromRef($network->id, $incident_date, $incident_ref);
        
        // Return view and pass vars
        return view('incident.update', [
            'page_title' => 'Update Incident',
            'incident' => $incident,
            'network' => $network,
        ]);
     }

     /**
     * Save a new update to the database
     *
     * @param  \Illuminate\Http\Request  $request 
     * @param string $network_code
     * @param string $incident_date
     * @param int $incident_ref
     * @return \Illuminate\Http\Response
     */
     public function storeUpdate(Request $request, $network_code, $incident_date, $incident_ref)
     {
         // Step one: validate all the fields.
         $this->validate($request, [
             'users' => 'required',
             'dets' => 'required',
             'localSearch' => 'required',
             'location' => 'present',
         ]);

         // Get network
         $network = Network::get()->where('code', $network_code)->first();

         // Get incident
         $incident = Incident::findFromRef($network->id, $incident_date, $incident_ref);

         // Create location in database
         $location = new Location;
            $location->formatted_address = $request->formatted_address;
            $location->type = $request->type;
            $location->lat = $request->lat;
            $location->lng = $request->lng;
            $location->notes = $request->location_note;
         $location->save();

         // Create new update
         $update = new Update;
            $update->incident_id = $incident->id;
            $update->location_id = $location->id;
            $update->dets = $request->dets;
            if ($request->isResult == '1')
            {
                $update->result = 1;
            }
            else {
                $update->result = 0;
            }
         $update->save();

         // Assign Users to Update
         $user_ids = explode(',', $request->users);
         $update->users()->attach($user_ids);

         $update->notifyUsers();

         if ($request->isResult == 1)
         {
             $incident->delete();
         }

         return redirect(route('incident.show', ['network' => $incident->network->code, 'date' => camel_case($incident->set_date), 'ref' => $incident->ref]));
     }
}
