<?php namespace Arcanedev\Stripe\Utilities;

use GuzzleHttp\Client;
use GuzzleHttp\Message\FutureResponse;
use GuzzleHttp\Message\ResponseInterface;

class GuzzleClient
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    private $client;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct()
    {
        $this->client = new Client;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Request Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string $url
     * @param array  $params
     *
     * @return ResponseInterface|mixed|null
     */
    public function get($url, $params = [])
    {
        $response = $this->client->get($url, [
            'query' => $params
        ]);

        return $response;
    }

    /**
     * @param string $url
     * @param array  $params
     *
     * @return ResponseInterface|mixed|null
     */
    public function post($url, $params = [])
    {
        $response = $this->client->post($url, [
            'query' => $params
        ]);

        return $response;
    }

    /**
     * @param string $url
     * @param array  $params
     *
     * @return ResponseInterface|mixed|null
     */
    public function delete($url, $params = [])
    {
        $response = $this->client->delete($url, [
            'query' => $params
        ]);

        return $response;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string $method
     * @param string $url
     * @param array  $params
     */
    public function request($method, $url, $params)
    {

    }
}
