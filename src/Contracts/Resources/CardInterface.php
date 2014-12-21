<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface CardInterface
{
    /**
     * @param array|null $params
     *
     * @return CardInterface The deleted card.
     */
    public function delete($params = null);

    /**
     * @return CardInterface The saved card.
     */
    public function save();
}
