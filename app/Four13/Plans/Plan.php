<?php

namespace Four13\Plans;


class Plan
{
    static $allocation = [
        'free' => 1,
        'silver' => 10,
        'gold' => 50,
        'platinum' => 1000000,
    ];

    public static function isAllocationUsedUp($plan, $count)
    {
        return self::$allocation[$plan] <= $count;
    }

    public static function canMonitorMore($plan, $count)
    {
        return self::$allocation[$plan] > $count;
    }
}