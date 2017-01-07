<?php

namespace App;


/**
 * This class creates objects that acts and responds but does nothing.
 * It's purpose is to make things work as usual thus preventing errors.
 * This is to make more elegant code by eliminating logic conditionals
 * that checks if objects are null.
 *
 * Class NullObject
 * @package App
 */
class NullObject
{
    static function create()
    {
        return new static;
    }

    public function __call($name, $arguments)
    {
    }

    static function __callStatic($name, $arguments)
    {
    }

    public function __get($name)
    {
    }

    public function __set($name, $value)
    {
    }
}