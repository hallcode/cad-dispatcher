@extends('layouts.app')

@section('title')
<i class="fa fa-plus"></i>{{ $network->name }}: New Incident
@endsection

@section('content')
<div class="ui container" style="margin-bottom: 2rem">Use this form to create a new incident on the {{ $network->name }} network.</div>

<form class="ui form segments" role="form" method="POST" action="{{ route('incident.store', ['network' => $network->code]) }}">
{{ csrf_field() }}

<div class="ui segment">
    
        <div class="ui stackable divided grid">
            <div class="eight wide column">
                <h4>Details</h4>
                <p>
                    All fields in this section are required.
                </p>

                <div class="field {{ $errors->has('eventType') ? 'error' : '' }}">
                    <label>Type</label>
                    <p>Select what this incident is.</p>
                    <div class="ui fluid search selection dropdown">
                        <input id="eventType" name="eventType" type="hidden">
                        <i class="dropdown icon"></i>
                        <div class="default text">Select Type</div>
                        <div class="menu">
                            @foreach ($network->types as $type)
                                <div class="item" data-value="{{ $type->id }}">
                                    {{ $type->name }}                    
                                </div>
                            @endforeach
                            @foreach ($default_types as $type)
                                <div class="item" data-value="{{ $type->id }}">
                                    {{ $type->name }}                    
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if ($errors->has('eventType'))
                        <div class="ui negative message">
                            {{ $errors->first('eventType') }}
                        </div>
                    @endif
                </div>

                <div class="field {{ $errors->has('grade') ? 'error' : '' }}">
                    <label>Grade</label>
                    <p>Select the grade for this incident (i.e. it's priority)</p>
                    <div class="ui fluid search selection dropdown">
                        <input id="grade" name="grade" type="hidden">
                        <i class="dropdown icon"></i>
                        <div class="default text">Select Grade</div>
                        <div class="menu">
                            @foreach ($network->grades->sortBy('response_time') as $grade)
                                <div class="item" data-value="{{ $grade->id }}">
                                    <div class="ui small {{ $grade->color }} label">{{ $grade->name }}</div>                    
                                </div>
                            @endforeach
                            @foreach ($default_grades->sortBy('response_time') as $grade)
                                <div class="item" data-value="{{ $grade->id }}">
                                    <div class="ui small {{ $grade->color }} label">{{ $grade->name }}</div>                    
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if ($errors->has('grade'))
                        <div class="ui negative message">
                            {{ $errors->first('grade') }}
                        </div>
                    @endif
                </div>

                <div class="field {{ $errors->has('users') ? 'error' : '' }}">
                    <label>Users</label>
                    <p>Select the user(s) you wish to assign to this incident.</p>
                    <div class="ui fluid multiple search selection dropdown">
                        <input id="users" name="users" type="hidden" multiple>
                        <i class="dropdown icon"></i>
                        <div class="default text">Select User(s)</div>
                        <div class="menu">
                            @foreach ($network->users as $user)
                                <div class="item" data-value="{{ $user->id }}">
                                    {{ $user->first_name }} {{ strtoupper($user->last_name) }} {{ $user->serial }}
                                    <div class="ui {{ $user->statuses->last()->color }} label">{{ $user->statuses->last()->name }}</div>                    
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if ($errors->has('users'))
                        <div class="ui negative message">
                            {{ $errors->first('users') }}
                        </div>
                    @endif
                </div>

                <div class="field {{ $errors->has('dets') ? 'error' : '' }}">
                    <label>Situation Details</label>
                    <p>Enter a detailed and specific description of the situation. Please include as much information as possible.</p>
                    <textarea class="ui input" id="dets" name="dets"></textarea>
                    @if ($errors->has('dets'))
                        <div class="ui negative message">
                            {{ $errors->first('dets') }}
                        </div>
                    @endif
                </div>

            </div>
            <div class="eight wide column">
                <h4>Date</h4>
                <div class="field {{ $errors->has('date') ? 'error' : '' }}">
                    <label>Adjust Date</label>
                    <p>If you wish, you can backdate this incident, or set it to a date and time in the future. Leave blank to set the incident date to the current time.</p>
                    <input class="ui input datetimepicker" type="text" id="date" name="date" value="{{ old('date') }}">
                    @if ($errors->has('date'))
                        <div class="ui negative message">
                            {{ $errors->first('date') }}
                        </div>
                    @endif
                </div>

                <div class="ui divider"></div>
                <h4>Location</h4>
                <div class="field {{ $errors->has('localSearch') ? 'error' : '' }}">
                    <label>Search for a location</label>
                    <div class="ui action input">
                        <input id="localSearch" type="text" name="localSearch" value="{{ old('localSearch') }}" required>
                        <a class="ui button" onclick="searchFor()">Search</a>
                    </div>
                    @if ($errors->has('name'))
                        <div class="ui negative message">
                            {{ $errors->first('localSearch') }}
                        </div>
                    @endif
                </div>

                <div class="ui middle aligned divided list" id="local_list">
                    
                </div>

                <input id="formatted_address" type="hidden" name="formatted_address" value="{{ old('formatted_address') }}">
                <input id="type" type="hidden" name="type" value="{{ old('type') }}">
                <input id="lat" type="hidden" name="lat" value="{{ old('lat') }}">
                <input id="lng" type="hidden" name="lng" value="{{ old('lng') }}">

                <div class="field {{ $errors->has('location_note') ? 'error' : '' }}">
                    <label>Location Note</label>
                    <p>Enter any notes which may help users find the right location.</p>
                    <textarea class="ui input" id="location_note" name="location_note">{{ old('location_note') }}</textarea>
                    @if ($errors->has('location_note'))
                        <div class="ui negative message">
                            {{ $errors->first('location_note') }}
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>

    <div class="ui right aligned segment">
        <a href="{{ route('network.show', ['network' => $network->code]) }}" class="ui button">Cancel</a>
        <button class="ui primary button">Save</button>
    </div>

</form>

<script>
var locations = {};
function searchFor()
{
    $.get('https://maps.googleapis.com/maps/api/geocode/json?address='+ $('#localSearch').val() +'&key=AIzaSyBEZQ9Q0ojDWkP0bRVus_zpD7MUoH-clgE')
    .success(function(data){
        locations = Object.keys(data.results);
        $('#local_list').html('');
        locations.forEach(function(key) {
            $('#local_list').append(
                '<div id="loc_'+key+'" class="item" onclick="selectLocation(\'#loc_'+key+'\')"><div class="ui radio checkbox"><input type="radio" name="location" value="'+key+'"><label>'+data.results[key].formatted_address+'</label></div></div>'
            );
            $('.ui.checkbox').checkbox();
            $('#loc_'+key).data('formatted_address', data.results[key].formatted_address);
            $('#loc_'+key).data('type', data.results[key].types[0]);
            $('#loc_'+key).data('lat', data.results[key].geometry.location.lat);
            $('#loc_'+key).data('lng', data.results[key].geometry.location.lng);
        });
    });
}

function selectLocation(id)
{
    $('#formatted_address').val($(id).data('formatted_address'));
    $('#type').val($(id).data('type'));
    $('#lat').val($(id).data('lat'));
    $('#lng').val($(id).data('lng'));
}
</script>
@endsection