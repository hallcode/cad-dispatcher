<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Network;

use App\Http\Requests;

class IntApiController extends Controller
{
    // Return JSON list of incidents in a network
    public function networkIncidentList($network_code)
    {
        $network = Network::where('code', $network_code)->first();

        $incidents = $network->incidents;

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
                "users" => $incident->users->count(),
            ];
        }

        return json_encode($array);
    }

    // Return JSON list of users in a network
    public function networkUserList($network_code)
    {
        $network = Network::where('code', $network_code)->first();

        $users = $network->users->filter(function($value, $key) {
            if ($value->statuses->last()->order > 0)
            {
                return true;
            }
            else {
                return false;
            }
        });

        $array = [];

        foreach ($users as $user)
        {
            $array[] = [
                "id" => $user->id,
                "link" => "#",
                "location" => $user->locations->last()->formatted_address,
                "status_color" => $user->statuses->last()->color,
                "status_name" => $user->statuses->last()->name,
                "name" => $user->first_name . ' ' . strtoupper($user->last_name),
                "call_sign" => $user->serial,
                "incidents" => $user->incidents->count(),
                "order" => $user->statuses->last()->order,
            ];
        }

        return json_encode($array);
    }
}
