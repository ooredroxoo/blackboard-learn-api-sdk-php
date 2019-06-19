<?php


namespace BlackboardLearn\Utils;


use BlackboardLearn\Exception\InvalidArgumentException;

class NumericQueryParameter extends QueryParameter
{
    public function __construct($name, $value)
    {
        if(!is_numeric($value)) {
            throw new InvalidArgumentException("NumericQueryParameter value should be a number!");
        }

        parent::__construct($name, $value);
    }
}