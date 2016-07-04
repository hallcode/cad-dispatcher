@extends('layouts.sidebar')

@section('title')
All Networks
@endsection

@section('buttons')
<a class="ui secondary button" href="{{ route('n.create') }}"><i class="fa fa-plus"></i>New Network</a>
@endsection

@section('page-content')
@if ($networks->count() != 0)
<div class="ui three stackable doubling cards">
    @foreach ($networks as $network)
    <div class="ui {{ $network->color }} card">
        <div class="content">
            <div class="header">{{ $network->name }}</div>
            <div class="meta">
                <span class="public">
                @if ($network->public)
                    Public Network
                @else
                    <i class="fa fa-lock"></i>
                    Private Network
                @endif
                </span>
                <span class="right floated users"><i class="fa fa-user"></i> {{$network->users->count()}} Users</span>
            </div>
        </div>
        <div class="content">
            <p>{{ str_limit($network->description, 200) }}</p>
            <div class="meta">
                @if ($network->users->contains(Auth::user()->id))
                <span class="isMember">You are a member.</span>
                @endif
            </div>
        </div>
        <div class="extra content">
            <span class="right floated">
            @if ($network->users->contains(Auth::user()->id) == false)
                <a class="ui tiny button" href="{{ route('n.join', ['n' => $network->code]) }}">Join</a>
            @endif
                <a class="ui tiny secondary button" href="{{ route('n.show', ['n' => $network->code]) }}">View</a>
            </span>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="ui secondary segment">
There are no networks to show.
</div>
@endif

@endsection