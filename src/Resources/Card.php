<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\CardInterface;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;

/**
 * Card Object
 * @link https://stripe.com/docs/api/php#card_object
 *
 * @property string id
 * @property string object // "card"
 * @property string brand
 * @property int    exp_month
 * @property int    exp_year
 * @property string fingerprint
 * @property string funding
 * @property string last4
 * @property string address_city
 * @property string address_country
 * @property string address_line1
 * @property string address_line1_check
 * @property string address_line2
 * @property string address_state
 * @property string address_zip
 * @property string address_zip_check
 * @property string country
 * @property string customer
 * @property string cvc_check
 * @property string dynamic_last4
 * @property string name
 * @property string recipient
 */
class Card extends Resource implements CardInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    const CUSTOMER_CLASS    = 'Arcanedev\\Stripe\\Resources\\Customer';
    const RECIPIENT_CLASS   = 'Arcanedev\\Stripe\\Resources\\Recipient';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param array $values
     * @param null  $apiKey
     *
     * @return Object
     */
    public static function constructFrom($values, $apiKey = null)
    {
        $class = get_class();

        return self::scopedConstructFrom($class, $values, $apiKey);
    }

    /**
     * @throws InvalidRequestException
     *
     * @return string The instance URL for this resource. It needs to be special
     *         cased because it doesn't fit into the standard resource pattern.
     */
    public function instanceUrl()
    {
        // TODO: Refactor this method
        $id = $this['id'];

        if (! $id) {
            $class = get_class($this);
            $msg = "Could not determine which URL to request: $class instance " . "has invalid ID: $id";

            throw new InvalidRequestException($msg, null);
        }

        if (isset($this['customer'])) {
            $parent = $this['customer'];
            $base   = self::classUrl(self::CUSTOMER_CLASS);
        }
        elseif (isset($this['recipient'])) {
            $parent = $this['recipient'];
            $base   = self::classUrl(self::RECIPIENT_CLASS);
        }
        else {
            return null;
        }

        $parentExtn = urlencode(Requestor::utf8($parent));
        $extn       = urlencode(Requestor::utf8($id));

        return "$base/$parentExtn/cards/$extn";
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Save/Update a card
     * @link https://stripe.com/docs/api/php#update_card
     *
     * @return Card
     */
    public function save()
    {
        $class = get_class();

        return self::scopedSave($class);
    }

    /**
     * Delete a card
     * @link https://stripe.com/docs/api/php#delete_card
     *
     * @param array $params
     *
     * @return Card
     */
    public function delete($params = [])
    {
        $class = get_class();

        return self::scopedDelete($class, $params);
    }
}
