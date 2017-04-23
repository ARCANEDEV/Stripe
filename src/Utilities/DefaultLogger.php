<?php namespace Arcanedev\Stripe\Utilities;

use Psr\Log\LoggerInterface;

/**
 * Class     DefaultLogger
 *
 * @package  Arcanedev\Stripe\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DefaultLogger implements LoggerInterface
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * System is unusable.
     *
     * @param  string  $message
     * @param  array   $context
     */
    public function emergency($message, array $context = [])
    {
        // DO NOTHING
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param  string  $message
     * @param  array   $context
     */
    public function alert($message, array $context = [])
    {
        // DO NOTHING
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param  string  $message
     * @param  array   $context
     */
    public function critical($message, array $context = [])
    {
        // DO NOTHING
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param  string  $message
     * @param  array   $context
     */
    public function error($message, array $context = [])
    {
        if (count($context) > 0) {
            throw new \Exception(
                'DefaultLogger does not currently implement context. Please implement if you need it.'
            );
        }

        error_log($message);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param  string  $message
     * @param  array   $context
     */
    public function warning($message, array $context = [])
    {
        // DO NOTHING
    }

    /**
     * Normal but significant events.
     *
     * @param  string  $message
     * @param  array   $context
     */
    public function notice($message, array $context = array())
    {
        // DO NOTHING
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param  string  $message
     * @param  array   $context
     */
    public function info($message, array $context = [])
    {
        // DO NOTHING
    }

    /**
     * Detailed debug information.
     *
     * @param  string  $message
     * @param  array   $context
     */
    public function debug($message, array $context = [])
    {
        // DO NOTHING
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param  mixed   $level
     * @param  string  $message
     * @param  array   $context
     */
    public function log($level, $message, array $context = [])
    {
        // DO NOTHING
    }
}
