<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Contracts\ListObjectInterface;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Utilities\Util;

/**
 * List Object
 *
 * @property string object
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
     * @param array|null $params
     *
     * @throws ApiException
     *
     * @return array|Object
     */
    public function all($params = null)
    {
        list($url, $params)      = $this->extractPathAndUpdateParams($params);

        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->get($url, $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    /**
     * @param array|null $params
     *
     * @throws ApiException
     *
     * @return array|Object
     */
    public function create($params = null)
    {
        list($url, $params) = $this->extractPathAndUpdateParams($params);

        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->post($url, $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    /**
     * @param string     $id
     * @param array|null $params
     *
     * @throws ApiException
     *
     * @return array|Object
     */
    public function retrieve($id, $params = null)
    {
        list($url, $params) = $this->extractPathAndUpdateParams($params);

        $id        = str_utf8($id);
        $extn      = urlencode($id);

        list($response, $apiKey) = Requestor::make($this->apiKey)
            ->get("$url/$extn", $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function extractPathAndUpdateParams($params)
    {
        $url = parse_url($this->url);

        if (! isset($url['path'])) {
            throw new ApiException("Could not parse list url into parts: $url");
        }

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
