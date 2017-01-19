<?php

namespace App\Http\Controllers\Api;

use App\AmazonMerchantListing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AmazonMerchantListing  $amazonMerchantListing
     * @return \Illuminate\Http\Response
     */
    public function show(AmazonMerchantListing $amazonMerchantListing)
    {
        dd($amazonMerchantListing);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AmazonMerchantListing  $amazonMerchantListing
     * @return \Illuminate\Http\Response
     */
    public function edit(AmazonMerchantListing $amazonMerchantListing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AmazonMerchantListing  $amazonMerchantListing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AmazonMerchantListing $amazonMerchantListing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AmazonMerchantListing  $amazonMerchantListing
     * @return \Illuminate\Http\Response
     */
    public function destroy(AmazonMerchantListing $amazonMerchantListing)
    {
        //
    }
}
