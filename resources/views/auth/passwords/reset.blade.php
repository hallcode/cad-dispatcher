@extends('layouts.app')

@section('title')
<i class="fa fa-lock"></i> Chose a new password
@endsection

@section('content')
<div class="ui centered grid">
    <div class="ten wide column">
        <p>
            Okay we've got you; enter a new password below. Chose wisely.
        </p>

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="field {{ $errors->has('email') ? 'error' : '' }}">
                <label>E-Mail Address</label>
                <input id="email" type="email" name="email" value="{{ $email or old('email') }}">
                @if ($errors->has('email'))
                    <div class="ui negative message">
                        {{ $errors->first('email') }}
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
                    <i class="fa fa-refresh"></i> Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
