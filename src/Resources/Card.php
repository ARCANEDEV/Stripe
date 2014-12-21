<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\CardInterface;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;

/**
 * @property string id
 * @property string name
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

        if ( ! $id ) {
            $class = get_class($this);

            throw new InvalidRequestException(
                "Could not determine which URL to request: $class instance " . "has invalid ID: $id", null
            );
        }

        if ( isset($this['customer']) ) {
            $parent = $this['customer'];
            $base   = self::classUrl(self::CUSTOMER_CLASS);
        }
        elseif ( isset($this['recipient']) ) {

            $parent = $this['recipient'];
            $base   = self::classUrl(self::RECIPIENT_CLASS);
        }
        else {
            return null;
        }

        $parent = Requestor::utf8($parent);
        $id     = Requestor::utf8($id);

        $parentExtn = urlencode($parent);
        $extn       = urlencode($id);

        return "$base/$parentExtn/cards/$extn";
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param array|null $params
     *
     * @return Card The deleted card.
     */
    public function delete($params = null)
    {
        $class = get_class();

        return self::scopedDelete($class, $params);
    }

    /**
     * @return Card The saved card.
     */
    public function save()
    {
        $class = get_class();

        return self::scopedSave($class);
    }
}
