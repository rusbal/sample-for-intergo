<?php

namespace App;

use App\NullObject;

/**
 * This is a mock object for anlutro/laravel-settings which is not needed at the moment.
 * Settings class is called from Zaffar\AmazonMws AmazonCore.php
 *
 * Class Setting
 * @package App
 */
class Setting extends NullObject
{
    public static function get()
    {
        return 'placeholder_string_from_\App\Settings@get';
    }
}