<?php namespace Arcanedev\Stripe\Http\Curl;

use Arcanedev\Stripe\Contracts\Http\Curl\SslCheckerInterface;
use Arcanedev\Stripe\Exceptions\ApiConnectionException;

/**
 * Class     SslChecker
 *
 * @package  Arcanedev\Stripe\Http\Curl
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SslChecker implements SslCheckerInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var string */
    protected $url = '';

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set URL.
     *
     * @param  string  $url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $this->prepareUrl($url);

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
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
     * @param  string  $url
     *
     * @return bool
     */
    public function checkCert($url)
    {
        if ( ! $this->hasStreamExtensions()) {
            return $this->showStreamExtensionWarning();
        }

        $this->setUrl($url);

        list($result, $errorNo, $errorStr) = $this->streamSocketClient();

        $this->checkResult($result, $errorNo, $errorStr);

        $params = stream_context_get_params($result);
        $cert   = $params['options']['ssl']['peer_certificate'];

        openssl_x509_export($cert, $pemCert);

        $this->checkBlackList($pemCert);

        return true;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check black list.
     *
     * @param  string  $pemCert
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiConnectionException
     */
    public function checkBlackList($pemCert)
    {
        if ($this->isBlackListed($pemCert)) {
            throw new ApiConnectionException(
                'Invalid server certificate. You tried to connect to a server that '.
                'has a revoked SSL certificate, which means we cannot securely send '.
                'data to that server.  Please email support@stripe.com if you need '.
                'help connecting to the correct API server.'
            );
        }
    }

    /**
     * Checks if a valid PEM encoded certificate is blacklisted.
     *
     * @param  string  $cert
     *
     * @return bool
     */
    public function isBlackListed($cert)
    {
        $lines = explode("\n", trim($cert));

        // Kludgily remove the PEM padding
        array_shift($lines);
        array_pop($lines);

        $derCert     = base64_decode(implode('', $lines));
        $fingerprint = sha1($derCert);

        return in_array($fingerprint, [
            '05c0b3643694470a888c6e7feb5c9e24e823dc53',
            '5b7dc7fbc98d78bf76d4d4fa6f597a0c901fad5c',
        ]);
    }


    /**
     * Stream Extension exists - Return true if one of the extensions not found.
     *
     * @return bool
     */
    private function hasStreamExtensions()
    {
        return function_exists('stream_context_get_params') &&
               function_exists('stream_socket_enable_crypto');
    }

    /**
     * Check if has errors or empty result.
     *
     * @param  mixed     $result
     * @param  int|null  $errorNo
     * @param  string    $errorStr
     *
     * @throws \Arcanedev\Stripe\Exceptions\ApiConnectionException
     */
    private function checkResult($result, $errorNo, $errorStr)
    {
        if (
            ($errorNo !== 0 && $errorNo !== null) ||
            $result === false
        ) {
            $stripeStatus = 'https://twitter.com/stripestatus';

            throw new ApiConnectionException(
                'Could not connect to Stripe (' . $this->getUrl() . ').  Please check your internet connection and try again.  '.
                'If this problem persists, you should check Stripe\'s service status at ' . $stripeStatus . '.  '.
                'Reason was: ' . $errorStr
            );
        }
    }

    /**
     * Check if has SSL Errors
     *
     * @param  int  $errorNum
     *
     * @return bool
     */
    public static function hasCertErrors($errorNum)
    {
        return in_array($errorNum, [
            CURLE_SSL_CACERT,
            CURLE_SSL_PEER_CERTIFICATE,
            CURLE_SSL_CACERT_BADFILE
        ]);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Prepare SSL URL.
     *
     * @param  string  $url
     *
     * @return string
     */
    private function prepareUrl($url)
    {
        $url  = parse_url($url);
        $port = isset($url['port']) ? $url['port'] : 443;

        return "ssl://{$url['host']}:{$port}";
    }

    /**
     * Open a socket connection.
     *
     * @return array
     */
    private function streamSocketClient()
    {
        $result = stream_socket_client(
            $this->getUrl(),
            $errorNo,
            $errorStr,
            30,
            STREAM_CLIENT_CONNECT,
            stream_context_create([
                'ssl' => [
                    'capture_peer_cert' => true,
                    'verify_peer'       => true,
                    'cafile'            => self::caBundle(),
                ]
            ])
        );

        return [$result, $errorNo, $errorStr];
    }

    /**
     * Get the certificates file path.
     *
     * @return string
     */
    public static function caBundle()
    {
        $basePath = __DIR__ . '/../../..';

        return realpath($basePath . '/data/ca-certificates.crt');
    }

    /**
     * Show Stream Extension Warning (stream_socket_enable_crypto is not supported in HHVM).
     *
     * @return true
     */
    private function showStreamExtensionWarning()
    {
        $message = 'Warning: ' . (is_hhvm() ? 'The HHVM (HipHop VM)' : 'This version of PHP') .
            ' does not support checking SSL certificates Stripe cannot guarantee that the server has a ' .
            'certificate which is not blacklisted.';

        if ( ! is_testing()) {
            error_log($message);
        }

        return true;
    }
}
