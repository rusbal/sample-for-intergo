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
}