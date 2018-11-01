<?php
namespace GuzzleHttpMock;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttpMock\Serializer\JsonSerializer;
use GuzzleHttpMock\Serializer\Serializable;
use Psr\Http\Message\RequestInterface;

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
     * @see \GuzzleHttp\Client::send()
     */
    public function send(RequestInterface $request, array $options = [])
    {
        $key = $this->getKey($request->getMethod(), $request->getUri()
            ->getPath(), $options);

        $response = $this->snapshot($key, function () use ($request, $options) {
            return $this->encodeResponse(parent::send($request, $options));
        });

        return $this->decodeResponse($response);
    }

    /**
     *
     * {@inheritdoc}
     * @see \GuzzleHttp\Client::request()
     */
    public function request($method, $uri = '', array $options = [])
    {
        $key = $this->getKey($method, $uri, $options);

        $response = $this->snapshot($key, function () use ($method, $uri, $options) {
            return $this->encodeResponse(parent::request($method, $uri, $options));
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
        $key = strtolower($method) . "_" . md5($path . '?' . http_build_query($options));

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