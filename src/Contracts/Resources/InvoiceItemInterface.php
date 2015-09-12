<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Collection;

/**
 * Interface  InvoiceItemInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface InvoiceItemInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve an Invoice Item.
     *
     * @link   https://stripe.com/docs/api/php#retrieve_invoiceitem
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($id, $options = null);

    /**
     * List all Invoice Items.
     * @link   https://stripe.com/docs/api/php#list_invoiceitems
     *
     * @param  array              $params
     * @param  array|string|null  $options
     *
     * @return Collection|array
     */
    public static function all($params = [], $options = null);

    /**
     * Create an Invoice Item.
     *
     * @link   https://stripe.com/docs/api/php#create_invoiceitem
     *
     * @param  array              $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null);

    /**
     * Update/Save an Invoice Item.
     *
     * @link   https://stripe.com/docs/api/php#update_invoiceitem
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * Delete an Invoice Item.
     *
     * @link   https://stripe.com/docs/api/php#delete_invoiceitem
     *
     * @param  array              $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = [], $options = null);
}
