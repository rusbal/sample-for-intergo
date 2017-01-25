<?php

function flash($message, $style = 'info') {
    session()->flash('message', $message);
    session()->flash('message_style', $style);
}

function amazonOfferLink($asin) {
    return "https://www.amazon.com/gp/offer-listing/$asin";
}

function issetAmazonMwsForUser($user = null) {
    if (! Auth::check()) {
        return false;
    }

    $user = $user ?: Auth::user();
    return $user->amazonMws()->exists();
}

function noListingForUser($user = null) {
    if (! Auth::check()) {
        return false;
    }

    $user = $user ?: Auth::user();
    return ! $user->amazonMerchantListing()->exists();
}

function monitoredListingCount($user = null) {
    if (! Auth::check()) {
        return 0;
    }

    $user = $user ?: Auth::user();
    return $user->monitoredListingCount();
}

function javascriptVariables() {
    $user = Auth::user();
    $subscriptionPlan = $user ? subscriptionPlan($user) : null;

    $data = [
        'csrfToken'        => csrf_token(),
        'userId'           => $user ? $user->id : null,
        'subscriptionPlan' => $subscriptionPlan,
        'isSubscribed'     => $subscriptionPlan ? true : false,
        'isNotSubscribed'  => $subscriptionPlan ? false : true,
        'issetAmazonMws'   => $user ? issetAmazonMwsForUser($user) : false,
    ];

    return json_encode($data, JSON_PRETTY_PRINT);
}

function subscriptionPlan($user = null)
{
    if (!Auth::check()) {
        return null;
    }

    /**
     * @var $user App\User
     */
    $user = $user ?: Auth::user();

    if ($subs = $user->getSubscription()) {
        return $subs->stripe_plan;
    }

    return null;
}