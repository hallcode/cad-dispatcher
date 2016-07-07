@extends('layouts.app')

@section('title')
<i class="fa fa-lock"></i>
New Account
@endsection

@section('tabs')
<div class="ui tabular menu" style="border-bottom: none; margin-top: 0;">
    <a href="{{url('/login')}}" class="item">
        <i class="fa fa-sign-in"></i> Login
    </a>
    <a href="{{url('/register')}}" class="active item">
        <i class="fa fa-user-plus"></i> Sign Up
    </a>
</div>
@endsection

@section('content')
<div class="ui stackable divided grid">
    <div class="six wide column">
    <p>All fields are required.</p>
        <form class="ui form" role="form" method="POST" action="{{ url('/register') }}">
        {{ csrf_field() }}

            <div class="field {{ $errors->has('first_name') ? 'error' : '' }}">
                <label>First Name</label>
                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}">
                @if ($errors->has('name'))
                    <div class="ui negative message">
                        {{ $errors->first('first_name') }}
                    </div>
                @endif
            </div>

            <div class="field {{ $errors->has('last_name') ? 'error' : '' }}">
                <label>Last Name</label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}">
                @if ($errors->has('last_name'))
                    <div class="ui negative message">
                        {{ $errors->first('last_name') }}
                    </div>
                @endif
            </div>

            <div class="field {{ $errors->has('email') ? 'error' : '' }}">
                <label>E-Mail Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}">
                @if ($errors->has('email'))
                    <div class="ui negative message">
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>

            <div class="field {{ $errors->has('sms') ? 'error' : '' }}">
                <label>Mobile Phone Number</label>
                <input id="sms" type="text" name="sms" value="{{ old('sms') }}">
                @if ($errors->has('sms'))
                    <div class="ui negative message">
                        {{ $errors->first('sms') }}
                    </div>
                @endif
            </div>

            <div class="field {{ $errors->has('password') ? 'error' : '' }}">
                <label>Password</label>
                <input id="password" type="password" name="password">
                @if ($errors->has('password'))
                    <div class="ui negative message">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>

            <div class="field {{ $errors->has('password_confirmation') ? 'error' : '' }}">
                <label>Confirm Password</label>
                <input id="password-confirm" type="password" name="password_confirmation">
                @if ($errors->has('password_confirmation'))
                    <div class="ui negative message">
                        {{ $errors->first('password_confirmation') }}
                    </div>
                @endif
            </div>

            <div class="field">
                <button type="submit" class="ui primary button">
                    <i class="fa fa-btn fa-user"></i> Register
                </button>
            </div>
        </form>
    </div>
    <div class="ten wide column">
        <p>
            Use the form over on the left to create a new account, then you can start managing incidents right away... how exciting!
            If you already have an account, you can log in by switching over on the tabs above.
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
