<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Peron\AmazonMws\AmazonInventoryList;

class DashboardController extends Controller
{
    public function index()
    {
        $store = Auth::user()->amazonMws->storeConfiguration();
        $products = $store;

        $inventory = $this->getAmazonSupply();

        return view('my.dashboard', compact('inventory', 'products'));
    }

    private function getAmazonSupply()
    {
        try {
            $obj = new AmazonInventoryList("gregStore"); //store name matches the array key in the config file
            $obj->setUseToken(); //tells the object to automatically use tokens right away
            $obj->setStartTime("- 24 hours");
            $obj->fetchInventoryList(); //this is what actually sends the request
            return $obj->getSupply();
        } catch (Exception $ex) {
            echo 'There was a problem with the Amazon library. Error: '.$ex->getMessage();
        }
    }
}
