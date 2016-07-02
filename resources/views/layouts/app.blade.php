@extends('layouts.html')
@section('page')
<div id="headerBar">
    <div class="ui secondary inverted menu container">
        @if (Auth::guest() == false)
        <a href="{{url('/home')}}" class="header item">
            <i class="fa fa-crosshairs"></i>
            Dispatcher
        </a>
        <div class="item">
            <div class="ui icon input">
                <input type="text" placeholder="Search...">
                <i class="search link icon"></i>
            </div>
        </div>
        @else
        <a href="{{url('/')}}" class="header item">
            <i class="fa fa-crosshairs"></i>
            Dispatcher
        </a>
        @endif
        <div class="right menu">
            @if (Auth::guest())
            <a href="{{ url('/login') }}" class="ui item">Log In</a>
            <a href="{{ url('/register') }}" class="ui item">Sign Up</a>
            @else
            <a href="{{ route('me') }}" class="ui item">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} {{ Auth::user()->serial }}</a>
            <a href="{{ route('me.networks') }}" class="ui item">My Networks</a>
            <a href="{{ url('/logout') }}" class="ui item">Log Out</a>
            @endif
        </div>
    </div>
</div>

<div id="pageBar">
    <div class="ui container stackable grid">
        <div class="eight wide column">
            <h3 class="light">@yield('title')</h3>
            @yield('header_content')
        </div>
        <div class="eight wide right aligned column">
            <h2>
                @yield('buttons')
            </h2>
        </div>
            @yield('tabs')
    </div>
</div>

<div id="contentBar">
    <div class="ui container">
    @yield('content')
    </div>
</div>

<div id="footerBar" class="ui center aligned container">
    <div class="ui divider"></div>
    <p>
        <i class="fa fa-creative-commons"></i> Alex Hall {{ date('Y') }}. Source available on <a target="_blank" href="https://github.com/garlics93/cad-dispatcher">Github <i class="fa fa-github"></i></a>.
    </p>
    <p>
        Software and service is provided as-is, the owner takes no responsibility for any loss or damage caused in the course of its use.
    </p>
</div>

@endsection
