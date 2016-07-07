@extends('layouts.app')


@section('content')
<div class="ui stackable container grid">
    <div class="computer only three wide column">
        <div class="ui fluid text vertical menu">
            @yield('custom-sidebar')
            <div class="header item">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} {{ Auth::user()->serial }}</div>
            <!-- <a class="item" href="{{ route('me') }}">
                <i class="fa fa-user"></i>
                Profile
            </a> -->
            <a class="item" href="{{ route('me.update') }}">
                <i class="fa fa-retweet"></i>
                Update
            </a>
            <a class="item" href="{{ route('me.incidents') }}">
                <i class="fa fa-flag"></i>
                Incidents
            </a>
            <a class="item" href="{{ route('me.networks') }}">
                <i class="fa fa-users"></i>
                Networks
            </a>

            <div class="header item">Networks</div>
            <a class="item" href="{{ route('n.index') }}">
                All Networks
            </a>
            @foreach (Auth::user()->networks as $network)
                <a class="item" href="{{ route('n.show', ['n' => $network->code]) }}">
                    <i class="ui {{ $network->color }} square icon"></i>
                    {{ $network->name }}
                </a>
            @endforeach
        </div>
    </div>
    <div class="thirteen wide column">
    @yield('page-content')
    </div>
</div>
@endsection