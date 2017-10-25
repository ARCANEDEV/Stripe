<?php namespace Arcanedev\Stripe;

/**
 * Class     StripeOAuth
 *
 * @package  Arcanedev\Stripe
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class StripeOAuth
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Generates a URL to Stripe's OAuth form.
     *
     * @param  array|null  $params
     * @param  array|null  $options
     *
     * @return string
     */
    public static function authorizeUrl($params = null, $options = null)
    {
        if ( ! $params)
            $params = [];

        $params['client_id'] = self::getClientId($params);

        if ( ! array_key_exists('response_type', $params))
            $params['response_type'] = 'code';

        $base = ($options && array_key_exists('connect_base', $options))
            ? $options['connect_base']
            : Stripe::$connectBase;

        return $base.'/oauth/authorize?'.str_url_queries($params);
    }

    /**
     * Use an authorization code to connect an account to your platform and fetch the user's credentials.
     *
     * @param  array|null  $params
     * @param  array|null  $options
     *
     * @return \Arcanedev\Stripe\StripeObject
     */
    public static function token($params = null, $options = null)
    {
        $base = ($options && array_key_exists('connect_base', $options))
            ? $options['connect_base']
            : Stripe::$connectBase;

        /** @var  \Arcanedev\Stripe\Http\Response  $response */
        list($response, $apiKey) = Http\Requestor::make(null, $base)->request(
            'post',
            '/oauth/token',
            $params,
            null
        );

        return Utilities\Util::convertToStripeObject($response->getJson(), $options);
    }

    /**
     * Disconnects an account from your platform.
     *
     * @param  array|null  $params
     * @param  array|null  $options
     *
     * @return \Arcanedev\Stripe\StripeObject
     */
    public static function deauthorize($params = null, $options = null)
    {
        if ( ! $params)
            $params = [];

        $base = ($options && array_key_exists('connect_base', $options))
            ? $options['connect_base']
            : Stripe::$connectBase;

        $params['client_id'] = self::getClientId($params);

        list($response, $apiKey) = Http\Requestor::make(null, $base)->request(
            'post',
            '/oauth/deauthorize',
            $params,
            null
        );

        return Utilities\Util::convertToStripeObject($response->getJson(), $options);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    private static function getClientId($params = null)
    {
        $clientId = ($params && array_key_exists('client_id', $params)) ? $params['client_id'] : null;

        if ($clientId === null)
            $clientId = Stripe::getClientId();

        if ($clientId === null) {
            throw new Exceptions\AuthenticationException(
                'No client_id provided.  (HINT: set your client_id using '
                . '"Stripe::setClientId(<CLIENT-ID>)".  You can find your client_ids '
                . 'in your Stripe dashboard at '
                . 'https://dashboard.stripe.com/account/applications/settings, '
                . 'after registering your account as a platform. See '
                . 'https://stripe.com/docs/connect/standard-accounts for details, '
                . 'or email support@stripe.com if you have any questions.'
            );
        }

        return $clientId;
    }
}
