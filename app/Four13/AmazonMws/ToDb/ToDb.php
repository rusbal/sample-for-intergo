<?php

namespace Four13\AmazonMws\ToDb;


abstract class ToDb
{
    protected $filters = [];

    abstract public function saveToDb($fileContents);

    protected function isValid($row)
    {
        foreach ($this->filters as $filter) {
            $condition = $filter['condition'];
            $value = $filter['value'];
            $index = $filter['index'];

            $checkAgainstValue = $row[$index];

            $valid = eval("return '$checkAgainstValue' $condition '$value';");

            if (! $valid) {
                return false;
            }
        }

        return true;
    }
}