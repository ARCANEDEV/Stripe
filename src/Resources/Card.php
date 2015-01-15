<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Exceptions\InvalidRequestException;

use Arcanedev\Stripe\Resource;
use Arcanedev\Stripe\Contracts\Resources\CardInterface;

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
     * The instance URL for this resource. It needs to be special cased
     * because it doesn't fit into the standard resource pattern.
     *
     * @throws InvalidRequestException
     *
     * @return string
     */
    public function instanceUrl()
    {
        // TODO: Refactor this method
        $id = $this['id'];

        if (! $id) {
            $class = get_class($this);
            $msg   = "Could not determine which URL to request: $class instance " . "has invalid ID: $id";

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

        $parentExtn = urlencode(str_utf8($parent));
        $extn       = urlencode(str_utf8($id));

        return "$base/$parentExtn/cards/$extn";
    }

    /**
     * Construct Card from array values
     *
     * @param array $values
     * @param null  $apiKey
     *
     * @return Card
     */
    //public static function constructFrom($values, $apiKey = null)
    //{
    //    return self::scopedConstructFrom(get_class(), $values, $apiKey);
    //}

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
        return self::scopedSave();
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
        return self::scopedDelete($params);
    }
}
