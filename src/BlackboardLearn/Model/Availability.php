<?php namespace BlackboardLearn\Model;


use BlackboardLearn\Exception\InvalidArgumentException;
use JsonSerializable;

class Availability implements JsonSerializable
{
    public const AVAILABILITY_AVAILABLE = 'Yes';
    public const AVAILABILITY_UNAVAILABLE = 'No';

    /** @var string $available */
    protected $available;

    /**
     * Availability constructor.
     * @param string $available
     * @throws InvalidArgumentException
     */
    public function __construct($available)
    {
        if($available !== 'Yes' && $available !== 'No') {
            throw new InvalidArgumentException('Availability should be initialized only with yes or no as values.');
        }

        $this->available = $available;
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return $this->available === 'Yes';
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $jsonRepresentation = new \stdClass;
        $jsonRepresentation->available = $this->available;
        return $jsonRepresentation;
    }
}