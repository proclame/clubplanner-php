<?php

namespace Proclame\Clubplanner;

use Exception;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\BadResponseException;
use Proclame\Clubplanner\Exceptions\ClubplannerApiException;

class Connection
{
    protected string $apiKey;

    protected string $apiUrl;


    private $client;

    private function client()
    {
        if ($this->client) {
            return $this->client;
        }
        $this->client = new Client([
            'http_errors' => true,
            'expect' => false,
        ]);

        return $this->client;
    }
    public function connect()
    {
        $client = $this->client();
        return $client;
    }


    /**
     * @param string $method
     * @param string $endpoint
     * @param null $body
     * @param array $params
     * @param array $headers
     *
     * @return \GuzzleHttp\Psr7\Request
     * @throws \Proclame\Clubplanner\Exceptions\ClubplannerApiException
     */
    private function createRequest($method = 'GET', $endpoint, $body = null, array $params = [], array $headers = [])
    {
        // Add default json headers to the request
        $headers = array_merge($headers, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        // If we have a token, sign the request
        if (! empty($this->accessToken)) {
            $headers['Authorization'] = 'Bearer ' . $this->accessToken;
        }

        $params = array_merge($params, ['token' => $this->apiKey]);
        $endpoint .= '?' . http_build_query($params);

        // Create  & return the request
        return new Request($method, $endpoint, $headers, $body);
    }

    /**
     * @param string $url
     * @param array $params
     * @param bool $fetchAll
     * @return mixed
     * @throws ClubplannerApiException
     */
    public function get($url, array $params = [])
    {
        try {
            $request = $this->createRequest('GET', $this->formatUrl($url, 'get'), null, $params);
            $response = $this->client()->send($request);
            $json = $this->parseResponse($response);

            return $json;
        } catch (Exception $e) {
            throw $this->parseExceptionForErrorMessages($e);
        }
    }

    /**
     * @param string $url
     * @param string $body
     *
     * @return mixed
     * @throws ClubplannerApiException
     */
    public function post($url, $body)
    {
        try {
            $request = $this->createRequest('POST', $this->formatUrl($url, 'post'), $body);
            $response = $this->client()->send($request);

            return $this->parseResponse($response);
        } catch (Exception $e) {
            throw $this->parseExceptionForErrorMessages($e);
        }
    }

    public function setApiKey(string $apiKey) : void
    {
        $this->apiKey = $apiKey;
    }

    public function setApiUrl(string $apiUrl) : void
    {
        $this->apiUrl = $apiUrl;
    }


    /**
     * @param Response $response
     * @return mixed
     * @throws ClubplannerApiException
     */
    private function parseResponse(Response $response)
    {
        try {
            Psr7\rewind_body($response);
            $json = json_decode($response->getBody()->getContents(), true);

            return $json;
        } catch (\RuntimeException $e) {
            throw new ClubplannerApiException($e->getMessage());
        }
    }

    /**
     * Parse the response in the Exception to return the Exact error messages.
     *
     * @param Exception $exception
     *
     * @return \Proclame\Clubplanner\Exceptions\ClubplannerApiException
     *
     * @throws \Proclame\Clubplanner\Exceptions\Api\TooManyRequestsException
     */
    private function parseExceptionForErrorMessages(Exception $exception) : Exception
    {
        if (! $exception instanceof BadResponseException) {
            return new ClubplannerApiException($exception->getMessage(), 0, $exception);
        }

        $response = $exception->getResponse();

        if (null === $response) {
            return new ClubplannerApiException('Response is NULL.', 0, $exception);
        }

        Psr7\rewind_body($response);
        $responseBody = $response->getBody()->getContents();
        $decodedResponseBody = json_decode($responseBody, true);

        if (null !== $decodedResponseBody && isset($decodedResponseBody['error']['message']['value'])) {
            $errorMessage = $decodedResponseBody['error']['message']['value'];
        } else {
            $errorMessage = $responseBody;
        }

        return new ClubplannerApiException('Error ' . $response->getStatusCode() . ': ' . $errorMessage, $response->getStatusCode(), $exception);
    }


    /**
     * @param string $endpoint
     * @return string
     */
    private function formatUrl(string $endpoint) : string
    {
        return 'https://' . $this->apiUrl . '/api/' . $endpoint;
    }
}
