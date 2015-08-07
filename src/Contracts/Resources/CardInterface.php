<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Resources\Card;

/**
 * Interface CardInterface
 * @package Arcanedev\Stripe\Contracts\Resources
 */
interface CardInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Save/Update a card
     * @link https://stripe.com/docs/api/php#update_card
     *
     * @param  array|string|null $options
     *
     * @return Card
     */
    public function save($options = null);

    /**
     * Delete a card
     * @link https://stripe.com/docs/api/php#delete_card
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Card
     */
    public function delete($params = [], $options = null);
}
