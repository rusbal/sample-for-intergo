<?php

function flash($message, $style = 'info') {
    session()->flash('message', $message);
    session()->flash('message_style', $style);
}

function pluralize($noun, $count, $pluralForm = null) {
    $pluralForm = $pluralForm ?: "{$noun}s";

    $count = (int) $count;

    if ($count > 1) {
        return "$count $pluralForm";
    }

    return "$count $noun";
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
        'userPlanStats'    => userPlanStats($user),
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

function isUserSubscribed($user = null)
{
    if (!Auth::check()) {
        return null;
    }

    /**
     * @var $user App\User
     */
    $user = $user ?: Auth::user();

    return $user->isSubscribed();
}

function userPlanStats($user = null)
{
    if (!Auth::check()) {
        return null;
    }

    /**
     * @var $user App\User
     */
    $user = $user ?: Auth::user();

    return $user->planStats();
}

/**
 * @param string      $singleDateCaption
 * @param Carbon      $startDate
 * @param string|null $datePeriodCaption
 * @param Carbon|null $endDate
 * @return string
 */
function reportTitle($singleDateCaption, $startDate, $datePeriodCaption = null, $endDate = null)
{
    if ($endDate && $endDate != $startDate) {
        $isSameYear  = $startDate->format('Y') === $endDate->format('Y');
        $isSameMonth = $startDate->format('M') === $endDate->format('M');

        if ($isSameYear) {
            if ($isSameMonth) {
                $start = $startDate->format('M j');
                $end   = $endDate->format('j, Y');

                return "Revenue <span class='date'>$start-$end</span>";

            } else {
                $start = $startDate->format('M d');
                $end   = $endDate->format('M j, Y');
            }
        } else {
            $start = $startDate->format('M j, Y');
            $end   = $endDate->format('M j, Y');
        }

        return "$datePeriodCaption <span class='date'>$start - $end</span>";
    }

    return "$singleDateCaption <span class='date'>" . $startDate->format('M j, Y') . "</span>";
}
