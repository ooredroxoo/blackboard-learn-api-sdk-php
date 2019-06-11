<?php namespace BlackboardLearn\Utils;


class LimitParameter extends NumericQueryParameter
{
    public function __construct($value)
    {
        parent::__construct('limit', $value);
    }
}