<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\AttachedObject;
use Arcanedev\Stripe\Bases\ExternalAccount;
use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Resources\BitcoinReceiverInterface;

/**
 * Class BitcoinReceiver
 * @package Arcanedev\Stripe\Resources
 *
 * @property string         id
 * @property string         object
 * @property string         description
 * @property string         currency
 * @property int            amount
 * @property AttachedObject metadata
 * @property Collection     transactions
 */
class BitcoinReceiver extends ExternalAccount implements BitcoinReceiverInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @return string The instance URL for this resource. It needs to be special
     *    cased because it doesn't fit into the standard resource pattern.
     */
    public function instanceUrl()
    {
        $result = parent::instanceUrl();

        if ($result === null) {
            $extn   = urlencode(str_utf8($this['id']));
            $base   = self::classUrl();

            $result = "$base/$extn";
        }

        return $result;
    }

    /**
     * The class URL for this resource.
     * It needs to be special cased because it doesn't fit into the standard resource pattern.
     *
     * @param  string $class Ignored.
     *
     * @return string
     */
    public static function classUrl($class = '')
    {
        return '/v1/bitcoin/receivers';
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve Bitcoin Receiver
     *
     * @param  string      $id
     * @param  string|null $apiKey
     *
     * @return self
     */
    public static function retrieve($id, $apiKey = null)
    {
        return self::scopedRetrieve($id, $apiKey);
    }

    /**
     * List all Bitcoin Receivers
     *
     * @param  array|null  $params
     * @param  string|null $apiKey
     *
     * @return Collection|self[]
     */
    public static function all($params = [], $apiKey = null)
    {
        return self::scopedAll($params, $apiKey);
    }

    /**
     * Create Bitcoin Receiver Object
     *
     * @param  array|null  $params
     * @param  string|null $apiKey
     *
     * @return self|array
     */
    public static function create($params = [], $apiKey = null)
    {
        return self::scopedCreate($params, $apiKey);
    }
}
