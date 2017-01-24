@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h1>Secure Payment via Stripe</h1>
                <img class="full-width" src="https://stripe.com/img/v3/home/social.png" alt="Stripe">

                <form action="/my/subscription" method="POST" style="display:none">
                    {{ csrf_field() }}
                    <script
                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="{{ env('STRIPE_KEY') }}"
                            data-amount="{{ $amount * 100 }}"
                            data-name="SKUBright"
                            data-description="Monthly Subscription: {{ strtoupper($plan) }}"
                            data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                            data-locale="auto">
                    </script>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('script')
<script>

$(function(){
    $('.stripe-button-el').click()
})

</script>
@endsection
