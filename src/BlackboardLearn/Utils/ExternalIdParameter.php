<?php


namespace BlackboardLearn\Utils;


class ExternalIdParameter extends QueryParameter
{
    public function __construct($value)
    {
        parent::__construct('externalId', $value);
    }
}