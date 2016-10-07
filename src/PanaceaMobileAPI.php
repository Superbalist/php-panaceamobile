<?php

namespace Superbalist\PanaceaMobile;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class PanaceaMobileAPI
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $uri = 'http://api.panaceamobile.com';

    /**
     * @var string
     */
    protected $userAgent = 'superbalist/php-panaceamobile';

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @param Client $client
     * @param string $username
     * @param string $password
     */
    public function __construct(Client $client, $username = null, $password = null)
    {
        $this->client = $client;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Set the uri.
     *
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }
    /**
     * Return the uri.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set the username.
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Return the username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the password.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Return the password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the user-agent HTTP header.
     *
     * @param string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }
    /**
     * Return the user-agent HTTP header.
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Return the full uri to an API end-point.
     *
     * @param string $endpoint
     * @return string
     */
    public function makeBaseUri($endpoint)
    {
        return rtrim($this->uri, '/') . '/' . trim($endpoint, '/');
    }

    /**
     * Return an array of headers to send with every HTTP request.
     *
     * @return array
     */
    public function getGlobalHeaders()
    {
        return [
            'User-Agent' => $this->userAgent,
        ];
    }

    /**
     * Create an HTTP request object.
     *
     * @param string $method
     * @param string $endpoint
     * @param mixed $body
     * @param array $headers
     * @return Request
     */
    protected function createRequest($method, $endpoint, $body = null, array $headers = [])
    {
        $uri = $this->makeBaseUri($endpoint);
        $headers = array_merge($this->getGlobalHeaders(), $headers);
        return new Request($method, $uri, $headers, $body);
    }

    /**
     * Make an HTTP GET request.
     *
     * **Example**
     *
     * ```php
     * $response = $client->get('message_send', ['to' => '+27000000000', 'text' => 'Hello World', 'from' => '+27000000000']);
     * ```
     *
     * @param string $action
     * @param array $query
     * @return mixed
     */
    public function get($action, array $query = [])
    {
        $query['action'] = $action;
        $query['username'] = $this->username;
        $query['password'] = $this->password;

        $endpoint = 'json';

        if (!empty($query)) {
            $endpoint .= '?' . http_build_query($query);
        }

        $request = $this->createRequest('GET', $endpoint);
        return $this->sendRequest($request);
    }

    /**
     * Send an HTTP request.
     *
     * The return value is based on the `Content-Type` header returned by the remote server.
     *
     * * JSON type responses are decoded to an `array`.
     * * XML type responses are decoded to a `\SimpleXMLElement` object.
     * * All other values are converted to a `string`.
     *
     * @param RequestInterface $request
     * @return mixed
     * @throws \Exception
     */
    protected function sendRequest(RequestInterface $request)
    {
        $response = $this->client->send($request);
        $json = json_decode($response->getBody(), true);

        if (isset($json['status']) && $json['status'] !== 1) {
            throw new \Exception($json['details']);
        }

        return $json;
    }

    /**
     * Send an SMS.
     *
     * **Example**
     *
     * ```php
     * $index = $client->sendMessage('+27000000000', 'This is my message content!');
     * ```
     *
     * @param string $to
     * @param string $message
     * @param string $from
     * @param int $reportMask
     * @param string $reportUrl
     * @param string $charset
     * @param int $dataCoding
     * @param int $messageClass
     * @param bool $autoDetectEncoding
     * @return mixed
     */
    public function sendMessage(
        $to,
        $message,
        $from = null,
        $reportMask = 19,
        $reportUrl = null,
        $charset = null,
        $dataCoding = null,
        $messageClass = -1,
        $autoDetectEncoding = false
    ) {
        $data = [
            'to' => $to,
            'text' => $message,
            'from' => $from,
            'report_mask' => $reportMask,
            'report_url' => $reportUrl,
            'charset' => $charset,
            'data_coding' => $dataCoding,
            'message_class' => $messageClass,
            'auto_detect_encoding' => $autoDetectEncoding ? 1 : 0,
        ];
        return $this->get('message_send', $data);
    }
}
