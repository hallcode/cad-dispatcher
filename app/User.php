<?php

namespace App;

use App\Location;
use App\Status;

use \ClickSendLib\Controllers\SMSController as SMS;
use Carbon\Carbon;
use Mail;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'sms', 'ref', 'avatar_id', 'can_create_users', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Contact Preferences
    public function prefs()
    {
        return $this->hasMany('App\ContactPref');
    }

    // Friends List
    public function following()
    {
        return $this->belongsToMany('App\User', 'friends_list', 'friender_id', 'friendee_id');
    }

    public function followers()
    {
        return $this->belongsToMany('App\User', 'friends_list', 'friendee_id', 'friender_id');
    }

    public function getFriendsAttribute()
    {
        return $this->following->merge($this->followers);
    }

    // Incidents user is assigned to
    public function incidents()
    {
        return $this->belongsToMany('App\Incident');
    }

    // Indidents the user has created
    public function incidents_created()
    {
        return $this->hasMany('App\Incident', 'creator_id');
    }

    // Locations this user has been at
    public function locations()
    {
        return $this->belongsToMany('App\Location');
    }

    // Networks the user is a member of
    public function networks()
    {
        return $this->belongsToMany('App\Network')->withPivot('is_mod', 'is_accepted');
    }

    // The networks that the user has created
    public function networks_created()
    {
        return $this->belongsTo('App\Network', 'creator_id');
    }

    // The statuses this user has used
    public function statuses()
    {
        return $this->belongsToMany('App\Status');
    }

    // Updates they have given
    public function updates()
    {
        return $this->belongsToMany('App\Update');
    }

    // Uploads this user owns
    public function uploads()
    {
        return $this->hasMany('App\Upload');
    }

    // Users avatar image
    public function avatar()
    {
        return $this->belongsTo('App\Upload', 'avatar_id');
    }

    // Render label (Semantic UI framework)
    public function getLabelAttribute()
    {
        $status = $this->statuses->last();
        
        $label = '<a class="ui grey label" style="margin: 0.2em">';
        $label .= '<i class="inverted '.$status->color.' circle icon popup" data-position="top center" data-content="'.$status->name.'" data-variation="inverted small"></i>';
        $label .= e($this->first_name) . ' ' . e($this->last_name);
        $label .= '<div class="detail">' . $this->serial . '</div>';
        $label .= '</a>';

        return $label;
    }

    public function getSerialAttribute()
    {
        return $this->ref . ' ' . $this->networks->first()->code;
    }

    // Clicksend SMS Function
    public function sendSMS($message, $network)
    {
        if ($network->sms_limit != 0)
        {
            $messages = [
                [
                    "source" => "php",
                    "from" => $network->code,
                    "body" => str_limit($message, 157),
                    "to" => $this->sms,
                    "custom_string" => "Message: " . Carbon::now()->toDateTimeString()
                ],
            ];

            $sms_con = new SMS();
            $result = $sms_con->sendSms(['messages' => $messages]);

            $network->sms_limit = $network->sms_limit - 1;
            $network->save();

            return $result;
        }
        else{
            return [
                "status" => "fail",
                "error" => "network limit exceeded"
            ];
        }
    }

    // Send Email
    public function sendEmail($subject, $headline, $body, $priority = 3)
    {
        $data = [
            "subject" => $subject,
            "headline" => $headline,
            "body" => $body,
        ];
        
        Mail::send('email.default', $data, function ($message) use ($subject, $priority) {
            $message->subject($subject);
            $message->to($this->email, $this->first_name . ' ' . $this->last_name);
            $message->priority($priority);
        });   
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
                    $loc->note = $note;
                }
            $loc->save();

            // Attach user to new location
            $this->locations()->attach($loc->id);
        }
        else
        {
            // Assosiate with existing one
            $this->locations()->attach($locations->last()->id);
        }
    }

    // Set status
    public function setStatus($status_id)
    {
        $this->statuses()->attach($status_id);
    }
}
