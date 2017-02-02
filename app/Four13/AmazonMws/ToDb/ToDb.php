<?php

namespace Four13\AmazonMws\ToDb;


abstract class ToDb
{
    /**
     * Example filter:
     *
     *  'quantity' => [ 'index' => 5, 'condition' => '>', 'value' => 0 ]
     *
     * Where:
     *   'quantity'  = Not used in the code and doesn't mean anything except as human readable label
     *   'index'     = Index to array of data to be validated.  This is used to get the data to be validated.
     *   'condition' = Comparison operators
     *   'value'     = The value to be compared against
     */
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

    public function doesFirstLineContainLabels()
    {
        return isset($this->rows[0][0])
            && $this->rows[0][0] == self::IGNORE_FIRST_LINE_WITH_FIRST_COLUMN;
    }
}