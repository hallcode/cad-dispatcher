<?php

namespace App;

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
                $subject = 'Update to ' . $this->incident->grade->name . ' Incident';
                $headline = 'A ' . $this->incident->grade->name . ' grade incident you are assigned to has been updated.';

                $body = $this->incident->set_date . ' [' . $this->incident->ref . '] <br><br>';
                $body .= $this->dets . '<br><br>';
                $body .= 'Location: ' . $this->location->formatted_address . '<br><br>';

                if ($this->result == true)
                {
                    $body .= '<b>This incident has now been closed. No further updates are requred.</b>';
                }
                else
                {
                    $body .= 'Next update required in: (dd:hh:mm)' . $this->incident->due_in;
                }

                $user->sendEmail($subject, $headline, $body);
            }
        }
    }
}
