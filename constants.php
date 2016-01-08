<?php

/* ------------------------------------------------------------------------------------------------
 |  Constants
 | ------------------------------------------------------------------------------------------------
 */
if ( ! defined('CURLE_SSL_CACERT_BADFILE')) {
    define('CURLE_SSL_CACERT_BADFILE', 77);
}

// Opt into TLS 1.x support on older versions of curl.
// This causes some curl versions, notably on RedHat,
// to upgrade the connection to TLS 1.2, from the default TLS 1.0.
if ( ! defined('CURL_SSLVERSION_TLSv1')) {
    define('CURL_SSLVERSION_TLSv1', 1); // constant not defined in PHP < 5.5
}
