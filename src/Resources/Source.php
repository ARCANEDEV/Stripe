<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\Source as SourceContract;
use Arcanedev\Stripe\Exceptions\ApiException;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\StripeResource;
use Arcanedev\Stripe\Utilities\Util;

/**
 * Class     Source
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  string  type
 */
class Source extends StripeResource implements SourceContract
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * List all Sources.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }

    /**
     * Retrieve a Source.
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null)
    {
        return self::scopedRetrieve($id, $options);
    }

    /**
     * Create a Source.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function create($params = null, $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * Verify the bank account.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function verify($params = null, $options = null)
    {
        list($response, $opts) = $this->request('post', $this->instanceUrl() . '/verify', $params, $options);
        $this->refreshFrom($response, $opts);

        return $this;
    }

    /**
     * Update a source.
     *
     * @param  string             $id
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function update($id, $params = null, $options = null)
    {
        return self::scopedUpdate($id, $params, $options);
    }

    /**
     * Save a source.
     *
     * @param array|string|null $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return $this->scopedSave($options);
    }

    /**
     * Detach a source.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiException
     * @throws \Arcanedev\Stripe\Exceptions\InvalidRequestException
     */
    public function detach($params = null, $options = null)
    {
        static::checkArguments($params, $options);

        $id = $this['id'];
        if ( ! $id) {
            $class = get_class($this);

            throw new InvalidRequestException(
                "Could not determine which URL to request: {$class} instance has invalid ID: {$id}", null
            );
        }

        if ($this['customer']) {
            $base       = Customer::classUrl();
            $parentExtn = urlencode(str_utf8($this['customer']));
            $extn       = urlencode(str_utf8($id));

            list($response, $opts) = $this->request('delete', "{$base}/{$parentExtn}/sources/{$extn}", $params, $options);
            $this->refreshFrom($response, $opts);

            return $this;
        }

        throw new ApiException(
            'This source object does not appear to be currently attached to a customer object.'
        );
    }

    /**
     * Delete a source.
     *
     * @deprecated Use the `detach` method instead.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = null, $options = null)
    {
        return $this->detach($params, $options);
    }

    /**
     * Get the source transactions.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection
     */
    public function sourceTransactions($params = null, $options = null)
    {
        list($response, $opts) = static::staticRequest(
            'get', $this->instanceUrl().'/source_transactions', $params, $options
        );

        /** @var  \Arcanedev\Stripe\Http\Response  $response */
        return Util::convertToStripeObject($response->getJson(), $opts)
                   ->setLastResponse($response);
    }
}
