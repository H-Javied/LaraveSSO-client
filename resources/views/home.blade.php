@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ __('You are logged in!') }}
                    <br>
                    <a href="{{route('blogs')}}">Check blogs</a>
                    <br>
                    <a href="{{route('betaTester')}}">Developer?</a> Help us with our beta testing.
                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                    @endif
                    @if ($errors->any())
                    @foreach ($errors->all() as $error)
                    <div style="color:red;">{{$error}}</div>
                    @endforeach
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection