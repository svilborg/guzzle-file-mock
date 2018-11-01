<?php
namespace Tests\Unit;

use GuzzleHttpMock\GuzzleFileMock;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class GuzzleFileMockTest extends TestCase
{

    private $mockDir = '';

    protected function setUp()
    {
        $this->mockDir = __DIR__ . '/snapshots/';
    }

    public function testGet()
    {
        $response = $this->getClient()->get("users");

        $this->assertNotEmpty($response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertNotEmpty($response->getHeaders());

        $this->verifyUserResponse($response->getBody()
            ->__toString());

        $this->assertFileExists($this->mockDir . 'get_9517dfe800665059cd6d7e52d9ee31cd.json');
    }

    public function testGetParams()
    {
        $response = $this->getClient()->get("users/1");

        $this->assertNotEmpty($response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertNotEmpty($response->getHeaders());

        $this->verifyUserResponse($response->getBody()
            ->__toString());

        $this->assertFileExists($this->mockDir . 'get_90dd03490b7f865c8b59aafca7d3ec28.json');
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

        $this->assertFileExists($this->mockDir . 'post_137ebb3105fab7b259f32e91905d5d08.json');
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

        $this->assertFileExists($this->mockDir . 'put_de7e9042964c72ccdf8e2fc3e9f6e889.json');
    }

    public function testDelete()
    {
        $response = $this->getClient()->delete("users/1", []);

        $this->assertNotEmpty($response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertNotEmpty($response->getHeaders());

        $this->assertFileExists($this->mockDir . 'delete_90dd03490b7f865c8b59aafca7d3ec28.json');
    }

    public function testPatch()
    {
        $response = $this->getClient()->patch("users/1", []);

        $this->assertNotEmpty($response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertNotEmpty($response->getHeaders());

        $this->assertFileExists($this->mockDir . 'patch_90dd03490b7f865c8b59aafca7d3ec28.json');
    }

    public function testHead()
    {
        $response = $this->getClient()->head("users/1", []);

        $this->assertNotEmpty($response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertNotEmpty($response->getHeaders());

        $this->assertFileExists($this->mockDir . 'head_90dd03490b7f865c8b59aafca7d3ec28.json');
    }

    public function testSendRequest()
    {
        $request = new Request("GET", "https://jsonplaceholder.typicode.com/users/1");
        $response = $this->getClient()->send($request, []);

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

        $this->verifyUserResponse($response->getBody()
            ->__toString());
    }

    private function verifyUserResponse($response)
    {
        $this->assertContains("name", $response);
        $this->assertContains("username", $response);
        $this->assertContains("address", $response);
        $this->assertContains("phone", $response);
        $this->assertContains("company", $response);
    }

    private function getClient()
    {
        return new GuzzleFileMock([
            'file_mock' => $this->mockDir,
            'base_uri' => 'https://jsonplaceholder.typicode.com/'
        ]);
    }

    private function getClientWithSerializer()
    {
        return new GuzzleFileMock([
            'file_mock' => $this->mockDir,
            'file_mock_ext' => 'txt',
            'file_mock_serializer' => '\GuzzleHttpMock\Serializer\PhpSerializer',
            'base_uri' => 'https://jsonplaceholder.typicode.com/'
        ]);
    }
}