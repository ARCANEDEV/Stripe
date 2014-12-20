<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Exceptions\ApiErrorException;

/**
 * @property string|null url
 */
class ObjectList extends Object
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function all($params = null)
    {
        list($url, $params) = $this->extractPathAndUpdateParams($params);

        $requestor              = new Requestor($this->apiKey);
        list($response, $apiKey) = $requestor->get($url, $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    public function create($params = null)
    {
        list($url, $params) = $this->extractPathAndUpdateParams($params);

        $requestor               = new Requestor($this->apiKey);
        list($response, $apiKey) = $requestor->post($url, $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    /**
     * @param string     $id
     * @param array|null $params
     *
     * @throws ApiErrorException
     *
     * @return array|Object
     */
    public function retrieve($id, $params = null)
    {
        list($url, $params) = $this->extractPathAndUpdateParams($params);

        $requestor  = new Requestor($this->apiKey);
        $id         = Requestor::utf8($id);
        $extn       = urlencode($id);

        list($response, $apiKey) = $requestor->get("$url/$extn", $params);

        return Util::convertToStripeObject($response, $apiKey);
    }

    private function extractPathAndUpdateParams($params)
    {
        $url = parse_url($this->url);

        if ( ! isset($url['path']) ) {
            throw new ApiErrorException("Could not parse list url into parts: $url");
        }

        if ( isset($url['query']) ) {
            // If the URL contains a query param, parse it out into $params so they
            // don't interact weirdly with each other.
            $query  = [];
            parse_str($url['query'], $query);

            // TODO: Change to ?: operator
            $params = array_merge($params ? $params : [], $query);
        }

        return [$url['path'], $params];
    }
}
