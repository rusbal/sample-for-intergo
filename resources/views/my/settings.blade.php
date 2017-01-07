@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">My Settings</div>

                    <div class="panel-body">

                        @if (session()->has('message'))
                            <div class="alert alert-{{ session('message_style') }}">{{ session('message') }}</div>
                        @endif

                        Settings
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
