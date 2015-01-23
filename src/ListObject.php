<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\ListObjectInterface;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Utilities\Util;

/**
 * List Object
 *
 * @property string object
 * @property array  data
 * @property int    total_count
 * @property bool   has_more
 * @property string url
 */
class ListObject extends Object implements ListObjectInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * List Function
     *
     * @param  array $params
     *
     * @throws ApiException
     *
     * @return ListObject|array
     */
    public function all($params = [])
    {
        list($url, $params)      = $this->extractPathAndUpdateParams($params);

        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->get($url, $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    /**
     * Create Function
     *
     * @param  array $params
     *
     * @throws ApiException
     *
     * @return \Arcanedev\Stripe\Object|Resource|array
     */
    public function create($params = [])
    {
        list($url, $params) = $this->extractPathAndUpdateParams($params);

        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($url, $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    /**
     * Retrieve Function
     *
     * @param  string $id
     * @param  array  $params
     *
     * @throws ApiException
     *
     * @return \Arcanedev\Stripe\Object|Resource|array
     */
    public function retrieve($id, $params = [])
    {
        list($url, $params) = $this->extractPathAndUpdateParams($params);

        $id        = str_utf8($id);
        $extn      = urlencode($id);

        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->get("$url/$extn", $params);

        return Util::convertToStripeObject($response, $apiKey);
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
