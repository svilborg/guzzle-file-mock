<?php
namespace GuzzleHttpMock\Serializer;

interface Serializable
{

    public function serialize($value);

    public function unserialize($value);
}