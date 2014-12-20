<?php namespace Arcanedev\Stripe;

use Arcanedev\Stripe\Exceptions\ApiConnectionErrorException;
use Arcanedev\Stripe\Exceptions\ApiErrorException;
use Arcanedev\Stripe\Exceptions\ApiKeyNotSetException;
use Arcanedev\Stripe\Exceptions\AuthenticationErrorException;
use Arcanedev\Stripe\Exceptions\CardErrorException;
use Arcanedev\Stripe\Exceptions\InvalidRequestErrorException;
use Arcanedev\Stripe\Exceptions\RateLimitErrorException;

class Requestor
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The API key that's to be used to make requests.
     *
     * @var string
     */
    public $apiKey;

    private static $preFlight;

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct($apiKey = null)
    {
        $this->_apiKey = $apiKey;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    private function getApiKey()
    {
        $apiKey = $this->_apiKey;

        if ( ! $apiKey ) {
            return Stripe::$apiKey;
        }

        return $apiKey;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @return array
     */
    private static function blacklistedCerts()
    {
        return [
            '05c0b3643694470a888c6e7feb5c9e24e823dc53',
            '5b7dc7fbc98d78bf76d4d4fa6f597a0c901fad5c',
        ];
    }

    /**
     * @param string $url The path to the API endpoint.
     *
     * @returns string The full path.
     */
    public static function apiUrl($url = '')
    {
        $apiBase = Stripe::$apiBase;

        return "$apiBase$url";
    }

    private static function encodeObjects($d)
    {
        if ($d instanceof Resource) {
            return self::utf8($d->id);
        }
        elseif ($d === true) {
            return 'true';
        }
        elseif ($d === false) {
            return 'false';
        }
        elseif (is_array($d)) {
            $res = [];

            foreach ($d as $k => $v) {
                $res[$k] = self::encodeObjects($v);
            }

            return $res;
        }
        else {
            return self::utf8($d);
        }
    }

    /**
     * @param string|mixed $value A string to UTF8-encode.
     *
     * @returns string|mixed The UTF8-encoded string, or the object passed in if it wasn't a string.
     */
    public static function utf8($value)
    {
        if (
            is_string($value)
            and mb_detect_encoding($value, "UTF-8", TRUE) != "UTF-8"
        ) {
            return utf8_encode($value);
        }

        return $value;
    }

    /**
     *  A query string, essentially.
     *
     * @param array $arr An map of param keys to values.
     * @param string|null $prefix (It doesn't look like we ever use $prefix...)
     *
     * @returns string
     */
    public static function encode($arr, $prefix = null)
    {
        if ( ! is_array($arr) ) {
            return $arr;
        }

        $r = [];

        foreach ($arr as $k => $v) {
            if ( is_null($v) ) {
                continue;
            }

            if ( $prefix ) {
                $k = ( $k and ! is_int($k) )
                    ? $prefix . "[$k]"
                    : $prefix . "[]";
            }

            $r[] = is_array($v)
                ? self::encode($v, $k, true)
                : urlencode($k) . "=" . urlencode($v);
        }

        return implode("&", $r);
    }

    /**
     * An array whose first element is the response and second element is the API key used to make the GET request.
     *
     * @param string $url
     * @param array|null $params
     *
     * @return array
     */
    public function get($url, $params = null)
    {
        return $this->request('get', $url, $params);
    }

    /**
     * An array whose first element is the response and second element is the API key used to make the GET request.
     *
     * @param string $url
     * @param array|null $params
     *
     * @return array
     */
    public function post($url, $params = null)
    {
        return $this->request('post', $url, $params);
    }

    /**
     * An array whose first element is the response and second element is the API key used to make the GET request.
     *
     * @param string $url
     * @param array|null $params
     *
     * @return array
     */
    public function delete($url, $params = null)
    {
        return $this->request('delete', $url, $params);
    }

    /**
     * An array whose first element is the response and second element is the API key used to make the request.
     *
     * @param string $method
     * @param string $url
     * @param array|null $params
     *
     * @return array
     */
    public function request($method, $url, $params = null)
    {
        if ( ! $params) {
            $params = [];
        }

        list($rbody, $rcode, $myApiKey) = $this->requestRaw($method, $url, $params);

        $resp = $this->interpretResponse($rbody, $rcode);

        return [$resp, $myApiKey];
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $params
     *
     * @throws ApiErrorException
     * @throws ApiKeyNotSetException
     *
     * @return array
     */
    private function requestRaw($method, $url, $params)
    {
        if ( ! $this->isApiKeySet() ) {
            throw new ApiKeyNotSetException;
        }

        $absUrl         = $this->apiUrl($url);
        $params         = self::encodeObjects($params);
        $langVersion    = phpversion();
        $uname          = php_uname();

        $ua = [
            'bindings_version'  => Stripe::VERSION,
            'lang'              => 'php',
            'lang_version'      => $langVersion,
            'publisher'         => 'stripe',
            'uname'             => $uname,
        ];

        $apiKey     = $this->getApiKey();
        $headers    = [
            'X-Stripe-Client-User-Agent: ' . json_encode($ua),
            'User-Agent: Stripe/v1 PhpBindings/' . Stripe::VERSION,
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/x-www-form-urlencoded',
        ];

        if ( Stripe::$apiVersion ) {
            $headers[] = 'Stripe-Version: ' . Stripe::$apiVersion;
        }

        list($rbody, $rcode) = $this->curlRequest($method, $absUrl, $headers, $params);

        return [$rbody, $rcode, $apiKey];
    }


    /**
     * @param $respBody
     * @param $respCode
     *
     * @throws ApiErrorException
     * @throws AuthenticationErrorException
     * @throws CardErrorException
     * @throws InvalidRequestErrorException
     * @throws RateLimitErrorException
     *
     * @return array
     */
    private function interpretResponse($respBody, $respCode)
    {
        try {
            $response = json_decode($respBody, true);
        }
        catch (\Exception $e) {
            throw new ApiErrorException(
                "Invalid response body from API: $respBody (HTTP response code was $respCode)",
                $respCode, 'api_error', null, $respBody
            );
        }

        if ($respCode < 200 or $respCode >= 300) {
            $this->handleApiError($respBody, $respCode, $response);
        }

        return $response;
    }

    /**
     * @param string $method
     * @param string $absUrl
     * @param array  $headers
     * @param array  $params
     *
     * @throws ApiConnectionError
     * @throws ApiErrorException
     *
     * @return array
     */
    private function curlRequest($method, $absUrl, $headers, $params)
    {

        if ( ! self::$preFlight ) {
            self::$preFlight = $this->checkSslCert($this->apiUrl());
        }

        $curl   = curl_init();
        $method = strtolower($method);
        $opts   = [];

        $this->checkMethod($method);

        if ( $method == 'post') {
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = self::encode($params);
        }
        elseif ($method == 'delete') {
            $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
            $absUrl = $this->parseAbsoluteUrl($absUrl, $params);
        }
        else {
            $opts[CURLOPT_HTTPGET] = 1;
            $absUrl = $this->parseAbsoluteUrl($absUrl, $params);
        }

        $absUrl                         = self::utf8($absUrl);
        $opts[CURLOPT_URL]              = $absUrl;
        $opts[CURLOPT_RETURNTRANSFER]   = true;
        $opts[CURLOPT_CONNECTTIMEOUT]   = 30;
        $opts[CURLOPT_TIMEOUT]          = 80;
        $opts[CURLOPT_RETURNTRANSFER]   = true;
        $opts[CURLOPT_HTTPHEADER]       = $headers;

        if ( ! Stripe::$verifySslCerts ) {
            $opts[CURLOPT_SSL_VERIFYPEER] = false;
        }

        curl_setopt_array($curl, $opts);
        $rbody = curl_exec($curl);

        if ( ! defined('CURLE_SSL_CACERT_BADFILE') ) {
            define('CURLE_SSL_CACERT_BADFILE', 77);  // constant not defined in PHP
        }

        $errno = curl_errno($curl);

        if (
            $errno == CURLE_SSL_CACERT or
            $errno == CURLE_SSL_PEER_CERTIFICATE or
            $errno == CURLE_SSL_CACERT_BADFILE
        ) {
            array_push(
                $headers,
                'X-Stripe-Client-Info: {"ca":"using Stripe-supplied CA bundle"}'
            );
            $cert = $this->caBundle();
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_CAINFO, $cert);
            $rbody = curl_exec($curl);
        }

        if ( $rbody === false ) {
            $errno = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
            $this->handleCurlError($errno, $message);
        }

        $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return [$rbody, $rcode];
    }

    /**
     * @param number $errno
     * @param string $message
     *
     * @throws ApiConnectionError
     */
    public function handleCurlError($errno, $message)
    {
        $apiBase = Stripe::$apiBase;

        switch ($errno) {
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = "Could not connect to Stripe ($apiBase).  Please check your "
                    . "internet connection and try again.  If this problem persists, "
                    . "you should check Stripe's service status at "
                    . "https://twitter.com/stripestatus, or";
                break;

            case CURLE_SSL_CACERT:
            case CURLE_SSL_PEER_CERTIFICATE:
                $msg = "Could not verify Stripe's SSL certificate.  Please make sure "
                    . "that your network is not intercepting certificates.  "
                    . "(Try going to $apiBase in your browser.)  "
                    . "If this problem persists,";
                break;

            default:
                $msg = "Unexpected error communicating with Stripe.  "
                    . "If this problem persists,";
        }

        $msg .= " let us know at support@stripe.com.";

        $msg .= "\n\n(Network error [errno $errno]: $message)";

        throw new ApiConnectionErrorException($msg);
    }

    /**
     * Preflight the SSL certificate presented by the backend. This isn't 100%
     * bulletproof, in that we're not actually validating the transport used to
     * communicate with Stripe, merely that the first attempt to does not use a
     * revoked certificate.
     *
     * Unfortunately the interface to OpenSSL doesn't make it easy to check the
     * certificate before sending potentially sensitive data on the wire. This
     * approach raises the bar for an attacker significantly.
     *
     * @param string $url
     *
     * @throws ApiConnectionError
     *
     * @return bool
     */
    private function checkSslCert($url)
    {
        if (
            ! function_exists('stream_context_get_params') or
            ! function_exists('stream_socket_enable_crypto')
        ) {
            error_log(
                'Warning: This version of PHP does not support checking SSL '.
                'certificates Stripe cannot guarantee that the server has a '.
                'certificate which is not blacklisted.'
            );

            return true;
        }

        $url    = parse_url($url);
        $port   = isset($url["port"]) ? $url["port"] : 443;
        $url    = "ssl://{$url["host"]}:{$port}";

        $sslContext = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true,
                'verify_peer'       => true,
                'cafile'            => $this->caBundle(),
            ]
        ]);

        $result = stream_socket_client(
            $url, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $sslContext
        );

        if (
            ($errno !== 0 && $errno !== NULL) or
            $result === false
        ) {
            $apiBase = Stripe::$apiBase;

            throw new ApiConnectionError(
                "Could not connect to Stripe ($apiBase).  Please check your ".
                "internet connection and try again.  If this problem persists, ".
                "you should check Stripe's service status at ".
                "https://twitter.com/stripestatus. Reason was: $errstr"
            );
        }

        $params = stream_context_get_params($result);

        $cert   = $params['options']['ssl']['peer_certificate'];

        openssl_x509_export($cert, $pemCert);

        if ( self::isBlackListed($pemCert) ) {
            throw new ApiConnectionError(
                'Invalid server certificate. You tried to connect to a server that '.
                'has a revoked SSL certificate, which means we cannot securely send '.
                'data to that server.  Please email support@stripe.com if you need '.
                'help connecting to the correct API server.'
            );
        }

        return true;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the API Key is set
     *
     * @return bool
     */
    private function isApiKeySet()
    {
        $apiKey = $this->getApiKey();

        return ! empty($apiKey);
    }

    /* Checks if a valid PEM encoded certificate is blacklisted

     * @return bool
     */
    public static function isBlackListed($cert)
    {
        $cert   = trim($cert);
        $lines  = explode("\n", $cert);

        // Kludgily remove the PEM padding
        array_shift($lines); array_pop($lines);

        $derCert        = base64_decode(implode("", $lines));
        $fingerprint    = sha1($derCert);

        return in_array($fingerprint, self::blacklistedCerts());
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string $respBody A JSON string.
     * @param int    $respCode
     * @param array  $response
     *
     * @throws ApiErrorException
     * @throws RateLimitErrorException
     * @throws InvalidRequestErrorException
     * @throws AuthenticationErrorException
     * @throws CardErrorException
     */
    public function handleApiError($respBody, $respCode, $response)
    {
        if ( ! is_array($response) or ! isset($response['error']) ) {
            throw new ApiErrorException(
                "Invalid response object from API: $respBody (HTTP response code was $respCode)",
                $respCode,
                $respBody,
                $response
            );
        }

        $error      = $response['error'];
        $type       = isset($error['type'])     ? $error['type'] : null;
        $message    = isset($error['message'])  ? $error['message'] : null;
        $stripeCode = isset($error['code'])     ? $error['code']    : null;

        $excluded   = array_flip(['type', 'message', 'code']);
        $params     = isset($error['param']) ? $error['param'] : array_diff_key($error, $excluded);

        switch ($respCode) {
            case 400:
                if ($stripeCode == 'rate_limit') {
                    throw new RateLimitErrorException(
                        $message, $respCode, $type, $stripeCode, $respBody, $response, $params
                    );
                }
                break;

            case 404:
                // If the error is caused by the user.
                throw new InvalidRequestErrorException(
                    $message, $respCode, $type, $stripeCode, $respBody, $response, $params
                );

            case 401:
                // If the error is caused by a lack of permissions.
                throw new AuthenticationErrorException(
                    $message, $respCode, $type, $stripeCode, $respBody, $response, $params
                );

            case 402:
                // If the error is the error code is 402 (payment required)
                throw new CardErrorException(
                    $message, $respCode, $type, $stripeCode, $respBody, $response, $params
                );

            default:
                // Otherwise...
                throw new ApiErrorException(
                    $message, $respCode, $type, $stripeCode, $respBody, $response, $params
                );
        }
    }

    /**
     * Get the certificates file path
     *
     * @return string
     */
    private function caBundle()
    {
        return __DIR__ . '/data/ca-certificates.crt';
    }

    /**
     * @param string $method
     *
     * @throws ApiErrorException
     */
    private function checkMethod($method)
    {
        if ( ! in_array($method, ['get', 'post', 'delete'])) {
            throw new ApiErrorException("Unrecognized method $method", 500);
        }
    }

    /**
     * @param $absUrl
     * @param $params
     *
     * @return string
     */
    private function parseAbsoluteUrl($absUrl, $params)
    {
        if ( count($params) > 0 ) {
            $encoded = self::encode($params);
            $absUrl = "$absUrl?$encoded";

            return $absUrl;
        }

        return $absUrl;
    }
}
