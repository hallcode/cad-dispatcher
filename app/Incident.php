<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Network;
use Carbon\Carbon;

class Incident extends Model
{
    use SoftDeletes;

    // Incident Many Many Tag relation
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    // Relationship to users assigned to this incident
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    // Network the incident belongs to
    public function network()
    {
        return $this->belongsTo('App\Network');
    }

    // Type of incident
    public function type()
    {
        return $this->belongsTo('App\Type');
    }

    // The incident's grade
    public function grade()
    {
        return $this->belongsTo('App\Grade');
    }

    // The user that created the incident
    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    // Location of the incident
    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    // Updates to this incident
    public function updates()
    {
        return $this->hasMany('App\Update');
    }

    // Date function
    public function getSetDateAttribute()
    {
        if ($this->date == null)
        {
            return date('d M Y', strtotime($this->created_at));
        }
        else
        {
            return date('d M Y', strtotime($this->date));
        }
    }

    // Date Timestamp function
    public function getSetTimestampAttribute()
    {
        if ($this->date == null)
        {
            return $this->created_at;
        }
        else
        {
            return $this->date;
        }
    }

    // Due time
    public function getDueInAttribute()
    {
        if ($this->date == null)
        {
            $date = new Carbon($this->updated_at);
        }
        else
        {
            $date = new Carbon($this->date);;
        }

        $date = $date->addMinutes($this->grade->response_time);

        $diff_mins = Carbon::now()->diffInMinutes($date);

        //  Raw minutes if less than an hour
        if ($diff_mins < 60)
        {
            $total = '00:00:' . $diff_mins;
        }
        // Return hours if less than one day 
        elseif ($diff_mins < 1440)
        {
            $hrs = $diff_mins / 60;
            $mins = fmod($diff_mins, 60);
            $total = '00:' . sprintf("%02d", number_format($hrs, 0)) . ':' . sprintf("%02d", number_format($mins, 0));
        }
        // If more than one day return days and hours
        elseif ($diff_mins >= 1440)
        {
            $days = $diff_mins / 1440;
            $mins_r = fmod($diff_mins, 1440);
            $hrs = $mins_r / 60;
            $mins = fmod($mins_r, 60);
            $total = sprintf("%02d", number_format($days, 0)) . ':' . sprintf("%02d", number_format($hrs, 0)) . ':' . sprintf("%02d", number_format($mins, 0));
        }

        $prefix = '';
        if ($date->isPast())
        {
            $prefix = ' - ';
        }

        return $total . $prefix;
    }

    public function getIsOverdueAttribute()
    {
        if ($this->date == null)
        {
            $date = new Carbon($this->updated_at);
        }
        else
        {
            $date = new Carbon($this->date);;
        }

        return $date->addMinutes($this->grade->response_time)->isPast();
    }

    public function getDueInRawAttribute()
    {
        if ($this->date == null)
        {
            $date = new Carbon($this->updated_at);
        }
        else
        {
            $date = new Carbon($this->date);;
        }

        $date = $date->addMinutes($this->grade->response_time);

        return Carbon::now()->diffInMinutes($date, false);
    }

    public function getUrlDateAttribute()
    {
        return camel_case($this->set_date);
    }

    public static function findFromRef($network_id, $incident_date, $incident_ref)
    {
        $network = Network::find($network_id);

        $incident = Incident::withTrashed()->get()->filter(function ($value, $key) use ($incident_date) {
            return $value->set_date == date('d M Y', strtotime($incident_date));
        })->where('ref', $incident_ref)->where('network_id', $network->id)->first();

        if ($incident == null)
        {
            return abort('404');
        }

        return $incident;
    }

    public function notifyUsers()
    {
        // Check if the grade of this incident is set to true, and that
        // NB: The $user->sendSMS function will check the SMS limit on the network before sending any SMSs.
        if ($this->grade->send_sms == true)
        {
            // Send an SMS to each user assigned to the incident.
            foreach ($this->users as $user)
            {
                $network = $this->network;

                $message = strtoupper($this->grade->name) . ': ';
                $message .= $this->set_date . ' [' . $this->ref . '] ';
                $message .= str_limit($this->dets, 90);
                $message .= ' - Location: ';
                $message .= str_limit($this->location->formatted_address, 40);

                $user->sendSMS($message, $network);
            }
        }
        
        // Check if the grade is set to send Emails.
        if ($this->grade->send_email == true)
        {
            // Send an email message to each user
            foreach ($this->users as $user)
            {
                $subject = 'New ' . $this->grade->name . ' Incident';
                $headline = 'You have been assigned to a new ' . $this->grade->name . ' grade incident.';

                $body = $this->set_date . ' [' . $this->ref . '] <br><br>';
                $body .= $this->dets . '<br><br>';
                $body .= 'Location: ' . $this->location->formatted_address . '<br><br>';
                $body .= 'Update required in: (dd:hh:mm)' . $this->due_in;

                $user->sendEmail($subject, $headline, $body);
            }
        }
    }
}
