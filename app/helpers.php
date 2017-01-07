<?php

function flash($message, $style = 'info') {
    session()->flash('message', $message);
    session()->flash('message_style', $style);
}

function amazonOfferLink($asin) {
    return "https://www.amazon.com/gp/offer-listing/$asin";
}
