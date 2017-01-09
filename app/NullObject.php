<?php

namespace App;

use Countable;


/**
 * This class creates objects that acts and responds but does nothing.
 * It's purpose is to make things work as usual thus preventing errors.
 * This is to make more elegant code by eliminating logic conditionals
 * that checks if objects are null.
 *
 * Class NullObject
 * @package App
 */
class NullObject implements Countable
{
    static function create()
    {
        return new static;
    }

    public function isNull()
    {
        return true;
    }

    public function __call($name, $arguments)
    {
        return $this;
    }

    static function __callStatic($name, $arguments)
    {
        return new static;
    }

    public function __get($name)
    {
    }

    public function __set($name, $value)
    {
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return 0;
    }
}