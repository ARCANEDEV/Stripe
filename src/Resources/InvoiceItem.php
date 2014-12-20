<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Resource;

class InvoiceItem extends Resource
{
    /**
     * @param string      $id The ID of the invoice item to retrieve.
     * @param string|null $apiKey
     *
     * @return InvoiceItem
     */
    public static function retrieve($id, $apiKey = null)
    {
        $class = get_class();

        return self::scopedRetrieve($class, $id, $apiKey);
    }

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return array An array of Stripe_InvoiceItems.
     */
    public static function all($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedAll($class, $params, $apiKey);
    }

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return InvoiceItem The created invoice item.
     */
    public static function create($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedCreate($class, $params, $apiKey);
    }

    /**
     * @return InvoiceItem The saved invoice item.
     */
    public function save()
    {
        $class = get_class();

        return self::scopedSave($class);
    }

    /**
     * @param array|null $params
     *
     * @return InvoiceItem The deleted invoice item.
     */
    public function delete($params = null)
    {
        $class = get_class();

        return self::scopedDelete($class, $params);
    }
}
