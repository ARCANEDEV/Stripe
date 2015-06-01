<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\CollectionInterface;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Utilities\Util;

/**
 * Class Collection
 * @package Arcanedev\Stripe
 *
 * @property string object
 * @property array  data
 * @property int    total_count
 * @property bool   has_more
 * @property string url
 */
class Collection extends Resource implements CollectionInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List Function
     *
     * @param  array             $params
     * @param  array|string|null $options
     *
     * @throws ApiException
     *
     * @return Collection|array
     */
    public function all($params = [], $options = null)
    {
        return $this->requestAndConvertToStripeObject('get', $params, $options);
        //list($url, $params)    = $this->extractPathAndUpdateParams($params);
        //list($response, $opts) = $this->request('get', $url, $params, $options);
        //
        //return Util::convertToStripeObject($response, $opts);
    }

    /**
     * Create Function
     *
     * @param  array             $params
     * @param  array|string|null $options
     *
     * @throws ApiException
     *
     * @return \Arcanedev\Stripe\Object|\Arcanedev\Stripe\Resource|array
     */
    public function create($params = [], $options = null)
    {
        return $this->requestAndConvertToStripeObject('post', $params, $options);
        //list($url, $params)    = $this->extractPathAndUpdateParams($params);
        //list($response, $opts) = $this->request('post', $url, $params);
        //
        //return Util::convertToStripeObject($response, $opts);
    }

    /**
     * Retrieve Function
     *
     * @param  string            $id
     * @param  array             $params
     * @param  array|string|null $options
     *
     * @throws ApiException
     *
     * @return \Arcanedev\Stripe\Object|\Arcanedev\Stripe\Resource|array
     */
    public function retrieve($id, $params = [], $options = null)
    {
        list($url, $params)    = $this->extractPathAndUpdateParams($params);
        $extn                  = urlencode(str_utf8($id));
        list($response, $opts) = $this->request('get', "$url/$extn", $params);

        return Util::convertToStripeObject($response, $opts);
    }

    /**
     * Make a request and convert to Stripe object
     *
     * @param  string            $method
     * @param  array             $params
     * @param  array|string|null $options
     *
     * @return Collection|\Arcanedev\Stripe\Object|\Arcanedev\Stripe\Resource|array|
     */
    private function requestAndConvertToStripeObject($method, $params, $options)
    {
        list($url, $params)    = $this->extractPathAndUpdateParams($params);
        list($response, $opts) = $this->request($method, $url, $params, $options);

        return Util::convertToStripeObject($response, $opts);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if Object is list
     *
     * @return bool
     */
    public function isList()
    {
        return $this->object === 'list';
    }

    /**
     * Check if URL has path
     *
     * @param  array $url
     *
     * @throws ApiException
     */
    private function checkPath(array $url)
    {
        if (! isset($url['path']) or empty($url['path'])) {
            throw new ApiException(
                'Could not parse list url into parts: ' . $this->url
            );
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get items Count
     *
     * @return int
     */
    public function count()
    {
        return ($this->isList() and isset($this->total_count))
            ? $this->total_count
            : 0;
    }

    /**
     * Extract Path And Update Parameters
     *
     * @param  $params
     *
     * @throws ApiException
     *
     * @return array
     */
    private function extractPathAndUpdateParams($params)
    {
        $url = parse_url($this->url);

        if ($url === false) {
            $url = [];
        }

        $this->checkPath($url);

        if (isset($url['query'])) {
            // If the URL contains a query param, parse it out into $params so they
            // don't interact weirdly with each other.
            $query  = [];
            parse_str($url['query'], $query);

            $params = array_merge($params ?: [], $query);
        }

        return [$url['path'], $params];
    }
}
