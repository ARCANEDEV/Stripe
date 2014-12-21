<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface SubscriptionInterface
{
    /**
     * @param array|null $params
     *
     * @return SubscriptionInterface The deleted subscription.
     */
    public function cancel($params = null);

    /**
     * @return SubscriptionInterface The saved subscription.
     */
    public function save();

    /**
     * @return SubscriptionInterface The updated subscription.
     */
    public function deleteDiscount();
}
