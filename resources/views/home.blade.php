@extends('layouts.app')

@section('title')
<i class="fa fa-home"></i> <small>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</small> / My Stuff
@endsection

@section('tabs')
<a href="{{url('/incidents')}}" class="active item">
    <i class="fa fa-sign-in"></i> Incidents
</a>
<a href="{{url('/networks')}}" class="item">
    <i class="fa fa-user-plus"></i> Networks
</a>
@endsection

@section('content')
<div class="ui centered divided grid">
    <div class="fourteen wide column">
        <p>
            List of active incidents you are assigned to.
        </p>

        @if (Auth::user()->incidents->count() == 0)
        <div class="ui message" style="text-align: center">
            You are not assigned to any incidents.
            <div class="divider"></div>
        </div>
        @else
        <table class="ui table">
            <thead>
                <tr>
                    <th><i class="fa fa-hashtag"></i></th>
                    <th>Type</th>
                    <th>Grade</th>
                    <th>Location</th>
                    <th>Due <small>(D:H:M)</small></th>
                </tr>
            </thead>
            <tbody>
            @foreach (Auth::user()->incidents as $incident)
                <tr>
                    <td>
                        <a href="{{ url('/network/'.$incident->network->id) }}" class="hover-popup ui basic label" data-content="{{ $incident->network->name }}" data-variation="basic" data-position="right center">
                            {{ $incident->network->code }}
                        </a>
                        <a href="{{ url('/incident/'.$incident->id) }}" class="hover-popup ui basic label" data-content="{{ $incident->dets }}" data-variation="basic" data-position="right center">
                            {{ $incident->set_date }} / {{ $incident->ref }}
                        </a>
                    </td>
                    <td><div class="ui label">{{ $incident->type->name }}</div></td>
                    <td><div class="ui {{$incident->grade->color}} label">{{ $incident->grade->name }}</div></td>
                    <td>{{ $incident->location->formatted_address }}</td>
                    <td>
                    @if ($incident->is_overdue)
                        <div class="ui red basic label">
                            {{ $incident->due_in }}
                        </div>
                    @else
                        <div class="ui blue basic label">
                            {{ $incident->due_in }}
                        </div>
                    @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @endif

        <div class="ui divider"></div>

        <p style="text-align: center">
            In the 'Due' column, <span class="ui small blue basic label">blue times</span> show the amount of time until the next update is
            and <span class="ui small red basic label">red times</span> show how far overdue an incident is (i.e. how
            long since it should have been updated).
        </p>

        <p style="text-align: center">
            You can create an incident by going to one of your <a href="{{url('/networks')}}">Networks</a> and adding it there.
        </p>  

        <p style="text-align: center">
            This page will automatically update every 20 seconds.
        </p>

    </div>
</div>
@endsection
