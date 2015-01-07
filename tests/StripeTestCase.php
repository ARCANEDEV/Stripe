<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Resources\BitcoinReceiver;
use Arcanedev\Stripe\Stripe;

use Arcanedev\Stripe\Resources\Charge;
use Arcanedev\Stripe\Resources\Coupon;
use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Resources\Plan;
use Arcanedev\Stripe\Resources\Recipient;

use Arcanedev\Stripe\Exceptions\InvalidRequestException;

abstract class StripeTestCase extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    const API_KEY = "tGN0bIwXnHdwOa85VABjPdSn8nWY7G7I";

    /** @var mixed */
    protected $object;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $apiKey = getenv('STRIPE_API_KEY');

        if (! $apiKey) {
            $apiKey = self::API_KEY;
        }

        Stripe::setApiKey($apiKey);
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->object);
    }

    protected function assertStripeInstance($expected, $actual, $message = '')
    {
        $expected = 'Arcanedev\\Stripe\\' . $expected;

        $this->assertInstanceOf($expected, $actual, $message);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Tests Functions
     | ------------------------------------------------------------------------------------------------
     */

    /**
     * Create a valid test charge.
     *
     * @param array $attributes
     *
     * @return Charge
     */
    protected static function createTestCharge(array $attributes = [])
    {
        return Charge::create($attributes + [
            "amount"        => 2000,
            "currency"      => "usd",
            "description"   => "Charge for test@example.com",
            'card' => [
                'number'    => '4242424242424242',
                'exp_month' => 5,
                'exp_year'  => date('Y') + 3,
            ],
        ]);
    }

    /**
     * Create a valid test customer.
     *
     * @param array $attributes
     *
     * @return Customer
     */
    protected static function createTestCustomer(array $attributes = [])
    {
        return Customer::create($attributes + [
            'card' => [
                'number'    => '4242424242424242',
                'exp_month' => 5,
                'exp_year'  => date('Y') + 3,
            ],
        ]);
    }

    /**
     * Create a valid test recipient
     *
     * @param array $attributes
     *
     * @return Recipient
     */
    protected static function createTestRecipient(array $attributes = [])
    {
        return Recipient::create($attributes + [
            'name' => 'PHP Test',
            'type' => 'individual',
            'tax_id' => '000000000',
            'bank_account' => [
                'country' => 'US',
                'routing_number' => '110000000',
                'account_number' => '000123456789'
            ],
        ]);
    }

    /**
     * Create Test Bitcoin Receiver
     *
     * @param string $email
     *
     * @return BitcoinReceiver
     */
    protected function createTestBitcoinReceiver($email)
    {
        return BitcoinReceiver::create([
            'amount'      => 100,
            'currency'    => 'usd',
            'description' => 'some details',
            'email'       => $email
        ]);
    }

    /**
     * Verify that a plan with a given ID exists, or create a new one if it does
     * not.
     *
     * @param $id
     *
     * @return Plan
     */
    protected static function retrieveOrCreatePlan()
    {
        $id = 'gold-' . self::randomString();

        try {
            return Plan::retrieve($id);
        }
        catch (InvalidRequestException $exception) {
            return Plan::create([
                'id'        => $id,
                'amount'    => 100,
                'currency'  => 'usd',
                'interval'  => 'month',
                'name'      => 'Gold Test Plan',
            ]);
        }
    }

    /**
     * Verify that a coupon with a given ID exists, or create a new one if it does
     * not.
     *
     * @param $id
     *
     * @return Coupon
     */
    protected static function retrieveOrCreateCoupon($id)
    {
        try {
            return Coupon::retrieve($id);
        }
        catch (InvalidRequestException $exception) {
            return Coupon::create([
                'id'          => $id,
                'duration'    => 'forever',
                'percent_off' => 25,
            ]);
        }
    }
}
