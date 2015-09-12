<?php namespace Arcanedev\Stripe\Bases;

use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     ExternalAccount
 *
 * @package  Arcanedev\Stripe\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class ExternalAccount extends StripeResource
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get The instance URL for this resource.
     * It needs to be special cased because it doesn't fit into the standard resource pattern.
     *
     * @throws InvalidRequestException
     *
     * @return string
     */
    public function instanceUrl()
    {
        $id = $this['id'];

        if ( ! $id) {
            $class = get_class($this);
            $msg   = "Could not determine which URL to request: $class instance has invalid ID: $id";

            throw new InvalidRequestException($msg, null);
        }

        if ($this['account']) {
            $parent = $this['account'];
            $class  = 'Arcanedev\\Stripe\\Resources\\Account';
            $path   = 'external_accounts';
        }
        elseif ($this['customer']) {
            $parent = $this['customer'];
            $class  = 'Arcanedev\\Stripe\\Resources\\Customer';
            $path   = 'sources';
        }
        elseif ($this['recipient']) {
            $parent = $this['recipient'];
            $class  = 'Arcanedev\\Stripe\\Resources\\Recipient';
            $path   = 'cards';
        }
        else {
            return null;
        }

        $base       = self::classUrl($class);
        $parentExtn = urlencode(str_utf8($parent));
        $extn       = str_utf8($id);

        return "$base/$parentExtn/$path/$extn";
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Delete an external account.
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = null, $options = null)
    {
        return $this->scopedDelete($params, $options);
    }

    /**
     * Save an external account.
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null)
    {
        return $this->scopedSave($options);
    }
}
