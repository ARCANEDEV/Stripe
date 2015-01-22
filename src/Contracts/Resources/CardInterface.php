<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Resources\Card;

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
     * @return Card
     */
    public function save();

    /**
     * Delete a card
     * @link https://stripe.com/docs/api/php#delete_card
     *
     * @param  array|null $params
     *
     * @return Card
     */
    public function delete($params = []);
}
