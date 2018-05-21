<?php
namespace GuzzleHttpMock;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

class GuzzleFileMock extends GuzzleClient
{

    private $path = "";

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        if (isset($config["file_mock"])) {
            $this->path = $config["file_mock"];
        } else {
            $this->path = __DIR__ . '/../../../../tests/snapshots/';
        }
    }

    public function get(string $path = '/', array $options = [])
    {
        $key = $this->getKey($path, $options);

        $response = $this->snapshot($key, function () use ($path, $options) {
            return $this->encodeResponse(parent::get($path, $options));
        });

        return $this->decodeResponse($response);
    }

    public function post(string $path = '/', array $options = [])
    {
        $key = $this->getKey($path, $options);

        $response = $this->snapshot($key, function () use ($path, $options) {
            return $this->encodeResponse(parent::post($path, $options));
        });

        return $this->decodeResponse($response);
    }


    public function put(string $path = '/', array $options = [])
    {
        $key = $this->getKey($path, $options);

        $response = $this->snapshot($key, function () use ($path, $options) {
            return $this->encodeResponse(parent::put($path, $options));
        });

        return $this->decodeResponse($response);
    }

    public function delete(string $path = '/', array $options = [])
    {
        $key = $this->getKey($path, $options);

        $response = $this->snapshot($key, function () use ($path, $options) {
            return $this->encodeResponse(parent::delete($path, $options));
        });

        return $this->decodeResponse($response);
    }

    private function getKey($path, $options) {
        $key = md5($path . '?' . http_build_query($options));

        return $key;
    }

    private function snapshot($key, callable $fn)
    {
        $filename = $this->path . $key . ".json";

        if (! file_exists($filename)) {

            $data = call_user_func($fn);

            file_put_contents($filename, $data);
        }

        return file_get_contents($filename);
    }

    private function encodeResponse(GuzzleResponse $response)
    {
        $data = [
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => (string) $response->getBody()
        ];
        return (string) json_encode($data);
    }

    private function decodeResponse($snapshot)
    {
        $response = json_decode($snapshot, true);
        return new GuzzleResponse($response['status'], $response['headers'], $response['body']);
    }
}