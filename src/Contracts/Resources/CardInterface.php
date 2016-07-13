<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  CardInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface CardInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Save/Update a card.
     * @link   https://stripe.com/docs/api/php#update_card
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function save($options = null);

    /**
     * Delete a card.
     * @link   https://stripe.com/docs/api/php#delete_card
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self
     */
    public function delete($params = [], $options = null);
}
