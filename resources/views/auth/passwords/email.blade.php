@extends('layouts.app')

@section('title')
<i class="fa fa-lock"></i> Reset Password
@endsection

<!-- Main Content -->
@section('content')
<div class="ui centered grid">
    <div class="ten wide column">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <p>
            Enter your email address below and we'll send you a link which, when followed, will allow you to change
            your password.
        </p>

        <form class="ui form" role="form" method="POST" action="{{ url('/password/email') }}">
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

            <div class="field">
                <button type="submit" class="ui primary button">
                    <i class="fa fa-envelope"></i> Send Password Reset Link
                </button>
            </div>
        </form>
            </div>
        </div>
@endsection
