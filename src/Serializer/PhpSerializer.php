<?php
namespace GuzzleHttpMock\Serializer;

class PhpSerializer implements Serializable
{

    public function serialize($value)
    {
        return serialize($value);
    }

    public function unserialize($value)
    {
        return unserialize($value);
    }
}