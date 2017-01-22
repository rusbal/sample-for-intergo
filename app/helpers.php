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

function javascriptVariables() {
    return json_encode([
        'csrfToken' => csrf_token(),
        'userId' => Auth::id(),
        'issetAmazonMws' => issetAmazonMwsForUser(),
    ], JSON_PRETTY_PRINT);
}