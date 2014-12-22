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
 |  STRINGS
 | ------------------------------------------------------------------------------------------------
 */
if (! function_exists('splitCamelCase')) {
    /**
     * Split Camel Case String
     *
     * @param string $string
     * @param string $glue
     *
     * @return string
     */
    function splitCamelCase($string, $glue = ' ')
    {
        $string = preg_split('/(?<=\\w)(?=[A-Z])/', $string);

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
 |  MISC
 | ------------------------------------------------------------------------------------------------
 */
if (! function_exists('dd')) {
    /**
     * Dump & Die Function
     */
    function dd()
    {
        array_map(function($x) {
            var_dump($x);
        }, func_get_args());
        die;
    }
}
