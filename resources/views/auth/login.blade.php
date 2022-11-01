@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <a href="{{ URL::route("sso-login") }}" class=" btn btn-block btn-danger btn-sm">SINGLE SIGN-ON</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection