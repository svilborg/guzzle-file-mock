<?php
namespace GuzzleHttpMock;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttpMock\Serializer\JsonSerializer;
use GuzzleHttpMock\Serializer\Serializable;

class GuzzleFileMock extends GuzzleClient
{

    /**
     * File extension
     *
     * @var string
     */
    private $ext = "json";

    /**
     * Cache path
     *
     * @var string
     */
    private $path = "";

    /**
     *
     * @var Serializable
     */
    private $serializer = null;

    /**
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        if (isset($config["file_mock"])) {
            $this->path = $config["file_mock"];
        } else {
            $this->path = realpath(__DIR__ . '/../../../../tests/snapshots/');
        }

        if (isset($config["file_mock_serializer"])) {
            $class = $config["file_mock_serializer"];
            $this->serializer = new $class();
        } else {
            $this->serializer = new JsonSerializer();
        }

        if (isset($config["file_mock_ext"])) {
            $this->ext = $config["file_mock_ext"];
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see \GuzzleHttp\Client::get($uri, $options)
     */
    public function get(string $path = '/', array $options = [])
    {
        $key = $this->getKey(__FUNCTION__, $path, $options);

        $response = $this->snapshot($key, function () use ($path, $options) {
            return $this->encodeResponse(parent::get($path, $options));
        });

        return $this->decodeResponse($response);
    }

    /**
     *
     * {@inheritdoc}
     * @see \GuzzleHttp\Client::post($uri, $options)
     */
    public function post(string $path = '/', array $options = [])
    {
        $key = $this->getKey(__FUNCTION__, $path, $options);

        $response = $this->snapshot($key, function () use ($path, $options) {
            return $this->encodeResponse(parent::post($path, $options));
        });

        return $this->decodeResponse($response);
    }

    /**
     *
     * {@inheritdoc}
     * @see \GuzzleHttp\Client::put($uri, $options)
     */
    public function put(string $path = '/', array $options = [])
    {
        $key = $this->getKey(__FUNCTION__, $path, $options);

        $response = $this->snapshot($key, function () use ($path, $options) {
            return $this->encodeResponse(parent::put($path, $options));
        });

        return $this->decodeResponse($response);
    }

    /**
     *
     * {@inheritdoc}
     * @see \GuzzleHttp\Client::delete($uri, $options)
     */
    public function delete(string $path = '/', array $options = [])
    {
        $key = $this->getKey(__FUNCTION__, $path, $options);

        $response = $this->snapshot($key, function () use ($path, $options) {
            return $this->encodeResponse(parent::delete($path, $options));
        });

        return $this->decodeResponse($response);
    }

    /**
     *
     * {@inheritdoc}
     * @see \GuzzleHttp\Client::patch($uri, $options)
     */
    public function patch(string $path = '/', array $options = [])
    {
        $key = $this->getKey(__FUNCTION__, $path, $options);

        $response = $this->snapshot($key, function () use ($path, $options) {
            return $this->encodeResponse(parent::delete($path, $options));
        });

        return $this->decodeResponse($response);
    }

    /**
     *
     * {@inheritdoc}
     * @see \GuzzleHttp\Client::head($uri, $options)
     */
    public function head(string $path = '/', array $options = [])
    {
        $key = $this->getKey(__FUNCTION__, $path, $options);

        $response = $this->snapshot($key, function () use ($path, $options) {
            return $this->encodeResponse(parent::delete($path, $options));
        });

        return $this->decodeResponse($response);
    }

    /**
     *
     * @param string $path
     * @param array $options
     * @return string
     */
    private function getKey($method = "get", $path, $options)
    {
        $key = $method. "_" . md5($path . '?' . http_build_query($options));

        return $key;
    }

    /**
     *
     * @param string $key
     * @param callable $fn
     * @return string
     */
    private function snapshot($key, callable $fn)
    {
        $filename = $this->path . $key . "." . $this->ext;

        if (! file_exists($filename)) {

            $data = call_user_func($fn);

            file_put_contents($filename, $data);
        }

        return file_get_contents($filename);
    }

    /**
     *
     * @param GuzzleResponse $response
     * @return string
     */
    private function encodeResponse(GuzzleResponse $response)
    {
        $data = [
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => (string) $response->getBody()
        ];

        return $this->serializer->serialize($data);
    }

    /**
     *
     * @param string $snapshot
     * @return \GuzzleHttp\Psr7\Response
     */
    private function decodeResponse($snapshot)
    {
        $response = $this->serializer->unserialize($snapshot);
        return new GuzzleResponse($response['status'], $response['headers'], $response['body']);
    }
}