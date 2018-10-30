<?php
namespace Tests\Unit;

use GuzzleHttpMock\GuzzleFileMock;
use PHPUnit\Framework\TestCase;
use function GuzzleHttp\json_decode;

class GuzzleFileMockTest extends TestCase
{

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

    public function testPut()
    {
        $params = [
            "name" => "Test"
        ];

        $response = $this->getClient()->put("users/1", [
            "form_params" => $params
        ]);

        $this->assertNotEmpty($response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertNotEmpty($response->getHeaders());

        $this->assertContains("Test", $response->getBody()
            ->__toString());
    }

    public function testDelete()
    {
        $response = $this->getClient()->delete("users/1", []);

        $this->assertNotEmpty($response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertNotEmpty($response->getHeaders());

        $this->assertContains("1", $response->getBody()
            ->__toString());
    }

    public function testGetWithPhpSerializer()
    {
        $response = $this->getClientWithSerializer()->get("users");

        $this->assertNotEmpty($response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertNotEmpty($response->getHeaders());
    }

    private function getClient()
    {
        return new GuzzleFileMock([
            'file_mock' => __DIR__ . '/snapshots/',
            'base_uri' => 'https://jsonplaceholder.typicode.com/'
        ]);
    }

    private function getClientWithSerializer()
    {
        return new GuzzleFileMock([
            'file_mock' => __DIR__ . '/snapshots/',
            'file_mock_ext' => 'txt',
            'file_mock_serializer' => '\GuzzleHttpMock\Serializer\PhpSerializer',
            'base_uri' => 'https://jsonplaceholder.typicode.com/'
        ]);
    }
}