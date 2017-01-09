<?php

namespace Four13\AmazonMws;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;


/**
 * @see \Illuminate\Config\Repository
 */
class Config extends Facade
{
    private static $specialCase = [
        'get' => [
            'amazon-mws.store'
        ]
    ];

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'config';
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public static function __callStatic($method, $args)
    {
        /**
         * See if this is a special case where we want to handle it differently.
         * If not, we execute the Illuminate\Support\Facades\Facade normally
         * which we overrode in app/config.php.
         *
         *  'Config' => Illuminate\Support\Facades\Config::class, <-- COMMENTED OUT (overriden)
         *  'Config' => Four13\AmazonMws\Config::class, <-- INSERTED
         */
        if (self::isSpecialCase($method, $args)) {
            return self::specialCase($method, $args);
        }

        $instance = static::getFacadeRoot();

        if (! $instance) {
            throw new RuntimeException('A facade root has not been set.');
        }

        return $instance->$method(...$args);
    }

    public static function specialCase($method, $args)
    {
        if ($method == 'get') {
            if ($args[0] == 'amazon-mws.store') {
                return Auth::user()->amazonMws->storeConfiguration();
            }
        }

        throw new \Exception(__CLASS__ . " :: Special case not handled (method: $method; params: " . implode(',', $args) . ")");
    }

    /**
     * Private functions
     */

    /**
     * Checks if this call is defined in $specialCase
     *
     * @param $method
     * @param $args
     * @return bool
     */
    private static function isSpecialCase($method, $args)
    {
        if (array_key_exists($method, self::$specialCase)) {
            $specialCaseParams = self::$specialCase[$method];
            return (in_array($args[0], $specialCaseParams));
        }
        return false;
    }
}
