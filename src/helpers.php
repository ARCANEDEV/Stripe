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