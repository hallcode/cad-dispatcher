<?php

namespace App;

use App\Location;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    // Updates incident too
    protected $touches = ['incident'];
    
    // Incident this update is for
    public function incident()
    {
        return $this->belongsTo('App\Incident');
    }

    // Location of update
    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    // Users this update is from
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function notifyUsers()
    {
        // Check if the grade of this incident is set to true, and that
        // NB: The $user->sendSMS function will check the SMS limit on the network before sending any SMSs.
        if ($this->incident->grade->send_sms == true)
        {
            // Send an SMS to each user assigned to the incident.
            foreach ($this->incident->users as $user)
            {
                $network = $this->incident->network;

                $message = 'UPDATE: ';
                $message .= $this->incident->set_date . ' [' . $this->incident->ref . '] ';
                $message .= str_limit($this->dets, 90);
                $message .= ' - Location: ';
                $message .= str_limit($this->location->formatted_address, 40);

                $user->sendSMS($message, $network);
            }
        }
        
        // Check if the grade is set to send Emails.
        if ($this->incident->grade->send_email == true)
        {
            // Send an email message to each user
            foreach ($this->incident->users as $user)
            {
                $subject = 'Update to ' . $this->incident->grade->name . ' ' . $this->incident->type->name;
                $headline = 'A ' . $this->incident->grade->name . ' ' . $this->incident->type->name .' you are assigned to has been updated by:<ul>';
                foreach ($this->users as $user2)
                {
                    $headline .= '<li>' . $user2->first_name . ' ' . $user2->last_name . '</li>';
                }
                $headline .= '</ul>';

                $body = $this->incident->set_date . ' [' . $this->incident->ref . '] <br><br>';
                $body .= $this->dets . '<br><br>';
                $body .= 'Location: ' . $this->location->formatted_address . '<br><br>';

                if ($this->result == true)
                {
                    $body .= '<b>This '.$this->incident->type->name.' has now been closed. No further updates are requred.</b>';
                }
                else
                {
                    $body .= 'Next update required in: (dd:hh:mm) ' . $this->incident->due_in;
                }

                $returns[] = $user->sendEmail($subject, $headline, $body);
            }
        }
    }

    // Set location
    public function setLocation($formatted_address, $type, $lat, $lng, $note = null)
    {
        $locations = Location::get()
            ->where('formatted_address', $formatted_address)
            ->where('lat', $lat)
            ->where('lng', $lng);
        
        if ($locations->count() == 0)
        {
            // Create new location
            $loc = new Location;
                $loc->formatted_address = $formatted_address;
                $loc->type = $type;
                $loc->lat = $lat;
                $loc->lng = $lng;
                if (!empty($note))
                {
                    $loc->notes = $note;
                }
            $loc->save();

            // Attach user to new location
            $this->location_id = $loc->id;
            $this->save();
        }
        else
        {
            // Assosiate with existing one
            $this->location_id = $locations->last()->id;
            $this->save();
        }
    }
}
