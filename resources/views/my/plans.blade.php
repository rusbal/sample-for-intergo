@extends('layouts.app')

@section('content')
    <div class="container subscription-plans">

        <h1>Select Your Subscription Type</h1>

        <div class="m-t-40">
            <subscription-plan plan="{{ subscriptionPlan() }}" init-message="{{ session('message') }}"></subscription-plan>
        </div>

    </div>
@endsection
