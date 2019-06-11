<?php namespace BlackboardLearn\Model;


use BlackboardLearn\Exception\IllegalJsonSerializationStateException;
use BlackboardLearn\Utils\InitWithStdClass;
use JsonSerializable;

class Term implements JsonSerializable, InitWithStdClass
{
    /** @var string $id - The primary ID of the term. */
    protected $id;
    /** @var string $externalId - An externally-defined unique ID for the term. Formerly known as 'sourcedidId'. */
    protected $externalId;
    /** @var string $dataSourceId - The ID of the data source associated with this term. This may optionally be the data source's externalId using the syntax "externalId:math101". */
    protected $dataSourceId;
    /** @var string $name - The name of the term. */
    protected $name;
    /** @var string $description - The description of the term. This field supports BbML */
    protected $description;
    /** @var Availability $availability - Settings controlling availability of the term to students. */
    protected $availability;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Term
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     * @return Term
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataSourceId()
    {
        return $this->dataSourceId;
    }

    /**
     * @param string $dataSourceId
     * @return Term
     */
    public function setDataSourceId($dataSourceId)
    {
        $this->dataSourceId = $dataSourceId;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Term
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Term
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Availability
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * @param Availability $availability
     * @return Term
     */
    public function setAvailability(Availability $availability)
    {
        $this->availability = $availability;
        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     * @throws IllegalJsonSerializationStateException
     */
    public function jsonSerialize()
    {
        if(!isset($this->externalId, $this->name)) {
            throw new IllegalJsonSerializationStateException('You should at least fill in the name and the external id!');
        }

        $jsonRepresentation = new \stdClass;
        $jsonRepresentation->name = $this->getName();
        $jsonRepresentation->externalId = $this->getExternalId();

        if($this->description) {
            $jsonRepresentation->description = $this->getDescription();
        }

        if($this->dataSourceId) {
            $jsonRepresentation->dataSourceId = $this->getDataSourceId();
        }

        if($this->id) {
            $jsonRepresentation->id = $this->getId();
        }

        if($this->availability) {
            $jsonRepresentation->availability = $this->getAvailability();
        }

        return $jsonRepresentation;

    }

    /**
     * @param \stdClass $stdObj
     * @return Term
     */
    public static function initWithStdClass(\stdClass $stdObj)
    {
        $term = new Term();
        if($stdObj->id) {
            $term->setId($stdObj->id);
        }

        if($stdObj->externalId) {
            $term->setExternalId($stdObj->externalId);
        }

        if($stdObj->dataSourceId) {
            $term->setDataSourceId($stdObj->dataSourceId);
        }

        if($stdObj->name) {
            $term->setName($stdObj->name);
        }

        if($stdObj->availability) {
            $availability = Availability::initWithStdClass($stdObj->availability);
            $term->setAvailability($availability);
        }

        if($stdObj->description) {
            $term->setDescription($stdObj->description);
        }

        return $term;
    }
}