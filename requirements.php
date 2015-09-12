<?php

/* ------------------------------------------------------------------------------------------------
 |  This snippet (and some of the curl code) due to the Facebook SDK.
 | ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists('curl_init')) {
    throw new Exception('Stripe needs the CURL PHP extension.');
}

if ( ! function_exists('json_decode')) {
    throw new Exception('Stripe needs the JSON PHP extension.');
}

if ( ! function_exists('mb_detect_encoding')) {
    throw new Exception('Stripe needs the Multibyte String PHP extension.');
}
