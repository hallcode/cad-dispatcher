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
                @if (Auth::user()->can('view', $network))
                <span class="isMember">You are a member.</span>
                @endif
            </div>
        </div>
        <div class="extra content">
            <span class="right floated">
            @if (Auth::user()->cannot('view', $network) && Auth::user()->cannot('accept', $network))
                <a class="ui tiny button" href="{{ route('n.join', ['n' => $network->code]) }}">Join</a>
            @elseif (Auth::user()->can('accept', $network))
                <a class="ui tiny primary button" href="{{ route('n.accept', ['n' => $network->code]) }}">Accept Invitation</a>
            @else
                <a class="ui tiny secondary button" href="{{ route('n.show', ['n' => $network->code]) }}">View</a>
            @endif
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