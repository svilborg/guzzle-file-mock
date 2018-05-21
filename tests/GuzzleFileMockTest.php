<?php
namespace Tests\Unit;

use GuzzleHttpMock\GuzzleFileMock;
use PHPUnit\Framework\TestCase;
use function GuzzleHttp\json_decode;

class GuzzleFileMockTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGet()
    {
        $c = new GuzzleFileMock();
        $response = $c->get("users", [
            'base_uri' => 'https://jsonplaceholder.typicode.com/',
            'file_mock' => __DIR__ . '/snapshots/'
        ]);

        $this->assertNotEmpty($response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertNotEmpty($response->getHeaders());
    }

    public function testGetParams()
    {
        $c = new GuzzleFileMock();
        $response = $c->get("users/1", [
            'base_uri' => 'https://jsonplaceholder.typicode.com/',
            'file_mock' => __DIR__ . '/snapshots/'
        ]);

        $this->assertNotEmpty($response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertNotEmpty($response->getHeaders());
    }
}