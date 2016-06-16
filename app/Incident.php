<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
    public function getSetDateAttribute($timestamp = null)
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
}
