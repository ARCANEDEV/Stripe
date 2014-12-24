<?php

/* ------------------------------------------------------------------------------------------------
 |  SYSTEM
 | ------------------------------------------------------------------------------------------------
 */
if (! function_exists('ca_certificates')) {
    function ca_certificates()
    {
        return __DIR__ . '/data/ca-certificates.crt';
    }
}

/* ------------------------------------------------------------------------------------------------
 |  STRINGS
 | ------------------------------------------------------------------------------------------------
 */
if (! function_exists('str_utf8')) {
    /**
     * Encoding string to UTF-8
     *
     * @param string $string
     *
     * @return string
     */
    function str_utf8($string)
    {
        if (
            is_string($string)
            and mb_detect_encoding($string, "UTF-8", true) != "UTF-8"
        ) {
            $string = utf8_encode($string);
        }

        return $string;
    }
}

if (! function_exists('str_parse_url')) {
    /**
     * Parse url with queries
     *
     * @param string $baseUrl
     * @param array  $queries
     *
     * @return string
     */
    function str_parse_url($baseUrl, array $queries = [])
    {
        if (! is_string($baseUrl) or empty($queries)) {
            return $baseUrl;
        }

        return $baseUrl . "?". str_url_queries($queries);
    }
}

if (! function_exists('str_url_queries')) {
    /**
     *  A query string, essentially.
     *
     * @param array       $queries An map of param keys to values.
     * @param string|null $prefix  (It doesn't look like we ever use $prefix...)
     *
     * @returns string
     */
    function str_url_queries($queries, $prefix = null)
    {
        if (! is_array($queries)) {
            return $queries;
        }

        $r = [];

        foreach ($queries as $k => $v) {
            if (is_null($v)) {
                continue;
            }

            if ($prefix) {
                $k = $prefix . (($k and ! is_int($k)) ? "[$k]" : "[]");
            }

            $r[] = is_array($v)
                ? str_url_queries($v, $k)
                : urlencode($k) . "=" . urlencode($v);
        }

        return implode("&", $r);
    }
}

if (! function_exists('str_split_camelcase')) {
    /**
     * Split Camel Case String
     *
     * @param string $string
     * @param string $glue
     *
     * @return string
     */
    function str_split_camelcase($string, $glue = ' ')
    {
        if (! is_string($string)) {
            return $string;
        }

        $string = preg_split('/(?<=\\w)(?=[A-Z])/', trim($string));

        return implode($glue, $string);
    }
}

/* ------------------------------------------------------------------------------------------------
 |  Array
 | ------------------------------------------------------------------------------------------------
 */
if (! function_exists('is_multi_dim_array')) {
    /**
     * Check if array is a multidimensional array
     *
     * @param array $array
     *
     * @return bool
     */
    function is_multi_dim_array($array)
    {
        if (! is_array($array)) {
            return false;
        }

        $array = array_filter($array, 'is_array');

        return (bool) count($array);
    }
}

if (! function_exists('is_assoc_array')) {
    /**
     * Check if array is an associative array
     *
     * @param array $array
     *
     * @return bool
     */
    function is_assoc_array($array)
    {
        if (! is_array($array)) {
            return false;
        }

        $array = array_filter(array_keys($array), 'is_string');

        return (bool) count($array);
    }
}

/* ------------------------------------------------------------------------------------------------
 |  Validations
 | ------------------------------------------------------------------------------------------------
 */
if (! function_exists('validate_url')) {
    /**
     * Check if url is valid
     * @param string $url
     *
     * @return bool
     */
    function validate_url($url)
    {
        return (bool) filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
    }
}

if (! function_exists('validate_version')) {
    /**
     * Check if version is valid
     *
     * @param string $version
     *
     * @return bool
     */
    function validate_version($version)
    {
        if (! is_string($version)) {
            return false;
        }

        // Format [x.x.x] - no beta & no release candidate
        preg_match("/(\d+.){2}\d+/", $version, $matches);

        return isset($matches[0]);
    }
}

if (! function_exists('validate_bool')) {
    /**
     * @param mixed $value
     *
     * @return bool
     */
    function validate_bool($value)
    {
        if (! is_bool($value)) {
            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        return (bool) $value;
    }
}

/* ------------------------------------------------------------------------------------------------
 |  MISC
 | ------------------------------------------------------------------------------------------------
 */
if (! function_exists('dd')) {
    /**
     * Dump & Die Function
     *
     * @codeCoverageIgnore
     */
    function dd()
    {
        array_map(function($x) {
            var_dump($x);
        }, func_get_args());
        die;
    }
}
