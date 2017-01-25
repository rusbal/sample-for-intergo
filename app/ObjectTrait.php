<?php

namespace App;


/**
 * This trait is included on classes where NullObject is used as substitute.
 *
 * Class ObjectTrait
 * @package App
 */
trait ObjectTrait
{
    /**
     * isNull() is defined on NullObject but must be present in classes where NullObject is used as substitute.
     *
     * @return bool
     */
    public function isNull() {
        return false;
    }

    /**
     * Catches static calls and will instantiate an object and calls the method if it starts with underscore.
     *
     * Example:
     *     \Four13\Stripe\Plan::_getPlans();
     *
     *   because _getPlans() starts with "_", then the following will be executed:
     *
     *     (new \Four13\Stripe\Plan)->getPlans();
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments)
    {
        if ($name[0] == '_') {
            $name = trim($name, '_');
            $plan = new static;
            return $plan->$name($arguments);
        }

        throw new \Exception(__CLASS__ . "@$name method does not exist.");
    }
}