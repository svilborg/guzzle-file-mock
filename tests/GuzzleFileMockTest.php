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
        $response = $this->getClient()->get("users");

        $this->assertNotEmpty($response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertNotEmpty($response->getHeaders());
    }

    public function testGetParams()
    {
        $response = $this->getClient()->get("users/1");

        $this->assertNotEmpty($response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertNotEmpty($response->getHeaders());
    }

    public function testPost()
    {
        $params = [
            "name" => "Test"
        ];

        $response = $this->getClient()->post("users", [
            "form_params" => $params
        ]);

        $this->assertNotEmpty($response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertNotEmpty($response->getHeaders());

        $this->assertContains("Test", $response->getBody()
            ->__toString());
    }

    private function getClient()
    {
        return new GuzzleFileMock([
            'file_mock' => __DIR__ . '/snapshots/',
            'base_uri' => 'https://jsonplaceholder.typicode.com/'
        ]);
    }
}