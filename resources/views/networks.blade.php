@extends('layouts.app')

@section('title')
<i class="fa fa-home"></i> <small>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</small> / My Stuff
@endsection

@section('buttons')
<a class="ui secondary button"><i class="fa fa-plus"></i> Create Network</a>
@endsection

@section('tabs')
<a href="{{url('/incidents')}}" class="item">
    <i class="fa fa-sign-in"></i> Incidents
</a>
<a href="{{url('/networks')}}" class="active item">
    <i class="fa fa-user-plus"></i> Networks
</a>
<a href="{{url('/map')}}" class="item">
    <i class="fa fa-map-o"></i> Map
</a>
@endsection

@section('content')
<div class="ui centered divided grid">
    <div class="fourteen wide column">
        <p>
            List of all the networks you are part of.
        </p>

        @if (Auth::user()->networks->count() == 0)
        <div class="ui message" style="text-align: center">
            You are not a member of any networks.
            <div class="divider"></div>
        </div>
        @else
        <table class="ui table">
            <thead>
                <tr>
                    <th><i class="fa fa-at"></i></th>
                    <th>Description</th>
                    <th>Users</th>
                    <th>Active Incidents</th>
                </tr>
            </thead>
            <tbody>
            @foreach (Auth::user()->networks as $network)
                <tr>
                    <td>
                        <a href="{{ url('/network/'.$network->id) }}" class="ui basic {{ $network->color }} label">{{ $network->name }}</a>
                        <a href="{{ url('/network/'.$network->id) }}" class="ui basic label">
                            {{ $network->code }}
                        </a>
                        @if ($network->public == false)
                        <i class="fa fa-lock"></i>
                        @endif
                    </td>
                    <td>{{ $network->description }}</td>
                    <td>{{ $network->users->count() }}</td>
                    <td>{{ $network->incidents->count() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @endif

        <div class="ui divider"></div>

        <p style="text-align: center">
            <i class="fa fa-lock"></i> Private network
        </p>  

    </div>
</div>
@endsection
