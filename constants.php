<?php

/* ------------------------------------------------------------------------------------------------
 |  Constants
 | ------------------------------------------------------------------------------------------------
 */
if ( ! defined('CURLE_SSL_CACERT_BADFILE')) {
    define('CURLE_SSL_CACERT_BADFILE', 77);
}

// Explicitly set a TLS version for cURL to use now that we're starting to block 1.0 and 1.1 requests.
//
// If users are on OpenSSL >= 1.0.1, we know that they support TLS 1.2, so set that explicitly because
// on some older Linux distros, clients may default to TLS 1.0 even when they have TLS 1.2 available.
//
// For users on much older versions of OpenSSL, set a valid range of TLS 1.0 to 1.2 (CURL_SSLVERSION_TLSv1).
// Note that this may result in their requests being blocked unless they're specially flagged into
// being able to use an old TLS version.
//
// Note: The int on the right is pulled from the source of OpenSSL 1.0.1a.

if ( ! defined('CURL_SSLVERSION_TLSv1_2')) {
    // Note the value 6 comes from its position in the enum that defines it in cURL's source code.
    define('CURL_SSLVERSION_TLSv1_2', 6); // constant not defined in PHP < 5.5
}

if ( ! defined('CURL_SSLVERSION_TLSv1')) {
    define('CURL_SSLVERSION_TLSv1', 1); // constant not defined in PHP < 5.5
}
