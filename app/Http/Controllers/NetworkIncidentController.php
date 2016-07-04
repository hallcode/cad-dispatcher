<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Network;
use App\Incident;
use App\Location;
use App\Update;
use App\Type;
use App\Grade;
use Carbon\Carbon;
use Mapper;
use Auth;

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
    public function create($network_code)
    {
        $network = Network::findFromCode($network_code);

        $types = Type::where('default', 1)->get();
        $default_grades = Grade::where('default', 1)->get();

        return view('incident.create', [
            'page_title' => 'Create Incident',
            'network' => $network,
            'default_types' => $types,
            'default_grades' => $default_grades,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $network_code)
    {
        // Get correct network
        $network = Network::findFromCode($network_code);

        // Validate form
        $this->validate($request, [
            'eventType' => 'required',
            'grade' => 'required',
            'users' => 'required',
            'dets' => 'required',
            'localSearch' => 'required',
            'location' => 'present',
        ]);

        // Create Location
        $location = new Location;
           $location->formatted_address = $request->formatted_address;
           $location->type = $request->type;
           $location->lat = $request->lat;
           $location->lng = $request->lng;
           $location->notes = $request->location_note;
        $location->save();

        // Generate Reference
        $new_ref = Incident::withTrashed()->get()->filter(function($value, $key) {
            return $value->set_date == date('d M Y');
        })->count() + 1;

        // Create Incident
        $incident = new Incident;
            $incident->ref = $new_ref;
            $incident->network_id = $network->id;
            $incident->type_id = $request->eventType;
            $incident->grade_id = $request->grade;
            $incident->creator_id = Auth::user()->id;
            $incident->location_id = $location->id;
            $incident->dets = $request->dets;
            $incident->type_id = $request->eventType;
            if (isset($request->date) && $request->set_date != '')
            {
                $incident->date = date("Y-m-d H:i:s", strtotime($request->date));
            }
        $incident->save();

        // Attach users
        $user_ids = explode(',', $request->users);
        $incident->users()->attach($user_ids);

        // Notify assigned users
        $incident->notifyUsers();

        // Return incident
        return redirect(route('incident.show', ['network' => $incident->network->code, 'date' => camel_case($incident->set_date), 'ref' => $incident->ref]));

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
        $network = Network::findFromCode($network_code);

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
        $network = Network::findFromCode($network_code);

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
         $network = Network::findFromCode($network_code);

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
