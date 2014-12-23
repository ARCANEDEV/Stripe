<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Card Object Interface
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
     * @return CardInterface
     */
    public function save();

    /**
     * Delete a card
     * @link https://stripe.com/docs/api/php#delete_card
     *
     * @param array $params
     *
     * @return CardInterface
     */
    public function delete($params = []);
}
