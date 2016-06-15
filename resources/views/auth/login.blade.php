@extends('layouts.app')

@section('title')
<i class="fa fa-user"></i>
Login
@endsection

@section('tabs')
<a href="{{url('/login')}}" class="active item">
    <i class="fa fa-sign-in"></i> Login
</a>
<a href="{{url('/register')}}" class="item">
    <i class="fa fa-user-plus"></i> Sign Up
</a>
@endsection

@section('content')
<div class="ui stackable divided grid">
    <div class="six wide column">
        <form class="ui form" role="form" method="POST" action="{{ url('/login') }}">
        {{ csrf_field() }}
        	<div class="field {{ $errors->has('email') ? 'error' : '' }}">
            	<label>E-Mail Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}">
                @if ($errors->has('email'))
                    <div class="ui negative message">
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>
            
            <div class="field {{ $errors->has('password') ? 'error' : '' }}">
                <label for="password">Password</label>
                <input id="password" type="password" name="password">
                @if ($errors->has('password'))
                    <div class="ui negative message">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>

            <div class="inline field">
                <div class="ui checkbox">
                <input type="checkbox" name="remember"> 
                <label>Remember Me</label>
                </div>
            </div>

            <div class="field">
                <button type="submit" class="ui primary button">
                    <i class="fa fa-btn fa-sign-in"></i> Login
                </button>
                <a class="ui basic button" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
            </div>
        </form>
    </div>
    <div class="ten wide column">
        <p>
            If you already have an account, you can login over to the right there. Otherwise click the tab above to make an account.
        </p>
        <p>
            <b>Dispatcher</b> is a free to use and open source Computer Aided Dispatch (CAD) system. You can use it to manage anything from service / support requests, to pizza deliveries, or just as a glorified to-do list.
        </p>
        <p>
            We'll be adding Facebook and other social media login options soon.
        </p>
    </div>
</div>
@endsection
