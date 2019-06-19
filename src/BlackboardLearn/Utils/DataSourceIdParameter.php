<?php


namespace BlackboardLearn\Utils;


class DataSourceIdParameter extends QueryParameter
{
    public function __construct($value)
    {
        parent::__construct('dataSourceId', $value);
    }
}