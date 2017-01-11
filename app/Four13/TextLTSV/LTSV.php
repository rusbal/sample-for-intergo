<?php

/**
 * Copied from Clover\Text with edits.
 * https://github.com/hiroy/clover-text-ltsv
 */

namespace Four13\TextLTSV;


class LTSV
{
    const DELIMITER = "\t";
    const LABEL_VALUE_DELIMITER = ':';

    protected $values = array();

    public function parseLine($line)
    {
        $line = trim($line);
        return str_getcsv($line, self::DELIMITER);
    }

    public function parseString($string)
    {
        $lines = explode("\n", $string);
        $values = array();

        foreach ($lines as $line) {
            if ($line != '') {
                $values[] = $this->parseLine($line);
            }
        }
        return $values;
    }

    public function parseFile($path, array $options = array())
    {
        if (!is_readable($path) || !is_file($path)) {
            throw new \InvalidArgumentException("{$path} is not readable.");
        }
        $lines = file($path, FILE_SKIP_EMPTY_LINES);
        $values = array();
        foreach ($lines as $line) {
            $values[] = $this->parseLine($line);
        }
        return $values;
    }

    public function getIteratorFromFile($path, array $options = array())
    {
        $values = $this->parseFile($path, $options);
        $arrayObject = new \ArrayObject($values);
        return $arrayObject->getIterator();
    }

    public function add($label, $value)
    {
        $this->values[$label] = $value;
        return $this;
    }

    public function toLine($doesAppendEOL = false)
    {
        $parts = array();
        foreach ($this->values as $label => $value) {
            $parts[] = $label . self::LABEL_VALUE_DELIMITER . $value;
        }
        $line = implode(self::DELIMITER, $parts);
        if ($doesAppendEOL) {
            $line .= PHP_EOL;
        }
        return $line;
    }

    public function __toString()
    {
        return $this->toLine();
    }
}
