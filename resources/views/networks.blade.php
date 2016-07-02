@extends('layouts.sidebar')

@section('title')
<i class="fa fa-home"></i> <small>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</small> / My Stuff
@endsection

@section('tabs')
<div class="ui tabular menu" style="border-bottom: none; margin-top: 0;">
    <a href="{{ route('me.incidents') }}" class="item">
        <i class="fa fa-flag"></i> Incidents
    </a>
    <a href="{{ route('me.networks') }}" class="active item">
        <i class="fa fa-users"></i> Networks
    </a>
</div>
@endsection

@section('page-content')
<div class="ui centered divided grid">
    <div class="sixteen wide column">
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
                        @if ($network->public == false)
                        <i class="fa fa-lock"></i>
                        @endif
                        <a href="{{ route('network.show', ['network'=>$network->code]) }}">{{ $network->name }}</a> / 
                        {{ $network->code }}
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
