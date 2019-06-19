<?php namespace BlackboardLearn\Utils;


class OffsetParameter extends NumericQueryParameter
{
    public function __construct($value)
    {
        parent::__construct('offset', $value);
    }
}