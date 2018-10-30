<?php
namespace GuzzleHttpMock\Serializer;

class JsonSerializer implements Serializable
{

    public function serialize($value)
    {
        return json_encode($value);
    }

    public function unserialize($value)
    {
        return json_decode($value, true);
    }
}