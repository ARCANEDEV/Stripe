<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Contracts\Utilities\Request\HttpClientInterface;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resources\Account;
use Arcanedev\Stripe\Resources\BitcoinReceiver;
use Arcanedev\Stripe\Resources\Charge;
use Arcanedev\Stripe\Resources\Coupon;
use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Resources\Plan;
use Arcanedev\Stripe\Resources\Recipient;
use Arcanedev\Stripe\Resources\Transfer;
use Arcanedev\Stripe\Stripe;
use Arcanedev\Stripe\Utilities\Request\HttpClient;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Class StripeTestCase
 * @package Arcanedev\Stripe\Tests
 */
abstract class StripeTestCase extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const API_KEY = 'tGN0bIwXnHdwOa85VABjPdSn8nWY7G7I';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var string */
    protected $myApiKey     = 'my-secret-api-key';

    /** @var string */
    protected $myApiVersion = '';

    /** @var HttpClientInterface|ObjectProphecy */
    protected $mock;

    /** @var int */
    protected $call;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        self::init();
    }

    private function init()
    {
        $apiKey = getenv('STRIPE_API_KEY');

        if (! $apiKey) {
            $apiKey = self::API_KEY;
        }

        Stripe::setApiKey($apiKey);
        $this->myApiVersion = Stripe::VERSION;

        Requestor::setHttpClient(HttpClient::instance());
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Mock Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Mock request
     *
     * @param  string  $method
     * @param  string  $path
     * @param  array   $params
     * @param  array   $return
     * @param  int     $rcode
     */
    protected function mockRequest($method, $path, $params = [], $return = ['id' => 'myId'], $rcode = 200)
    {
        $mock = $this->setUpMockRequest();

        $mock->setApiKey(self::API_KEY)->willReturn($mock);
        $mock->request(strtolower($method), 'https://api.stripe.com' . $path, $params, [])->willReturn([
            json_encode($return),
            $rcode,
            []
        ]);
    }

    /**
     * Setup mock Request
     *
     * @return HttpClientInterface|ObjectProphecy
     */
    protected function setUpMockRequest()
    {
        if ( ! $this->mock) {
            $this->mock = $this->prophesize(
                'Arcanedev\\Stripe\\Contracts\\Utilities\\Request\\HttpClientInterface'
            );

            Requestor::setHttpClient($this->mock->reveal());
        }

        return $this->mock;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Helpers Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a valid test charge.
     *
     * @param  array $attributes
     *
     * @return Charge
     */
    protected static function createTestCharge(array $attributes = [])
    {
        return Charge::create($attributes + [
            'amount'        => 2000,
            'currency'      => 'usd',
            'description'   => 'Charge for test@example.com',
            'card'          => static::getValidCardData(),
        ]);
    }

    /**
     * Create a valid test customer.
     *
     * @param  array $attributes
     *
     * @return Customer
     */
    protected static function createTestCustomer(array $attributes = [])
    {
        return Customer::create($attributes + [
            'card' => static::getValidCardData(),
        ]);
    }

    /**
     * Create a test account
     *
     * @param  array $attributes
     *
     * @return Account
     */
    protected static function createTestAccount(array $attributes = [])
    {
        return Account::create(
            $attributes + [
                'managed' => false,
                'country' => 'US',
                'email'   => self::generateRandomEmail(),
            ]
        );
    }

    /**
     * Create a valid test recipient
     *
     * @param  array $attributes
     *
     * @return Recipient
     */
    protected static function createTestRecipient(array $attributes = [])
    {
        return Recipient::create($attributes + [
            'name'          => 'PHP Test',
            'type'          => 'individual',
            'tax_id'        => '000000000',
            'bank_account'  => [
                'country'           => 'US',
                'routing_number'    => '110000000',
                'account_number'    => '000123456789'
            ],
        ]);
    }

    /**
     * Create Test Bitcoin Receiver
     *
     * @param  string  $email
     *
     * @return BitcoinReceiver
     */
    protected function createTestBitcoinReceiver($email = 'do+fill_now@stripe.com')
    {
        return BitcoinReceiver::create([
            'amount'      => 100,
            'currency'    => 'usd',
            'description' => 'some details',
            'email'       => $email
        ]);
    }

    /**
     * Verify that a plan with a given ID exists, or create a new one if it does not.
     *
     * @return Plan
     */
    protected static function retrieveOrCreatePlan()
    {
        $id = 'gold-' . self::generateRandomString(20);

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
     * Verify that a coupon with a given ID exists, or create a new one if it does not.
     *
     * @param  string $id
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

    /**
     * Create transfer for tests
     *
     * @param  array $attributes
     *
     * @return Transfer|array
     */
    protected static function createTestTransfer(array $attributes = [])
    {
        $recipient = self::createTestRecipient();

        return Transfer::create(
            $attributes + [
                'amount'      => 2000,
                'currency'    => 'usd',
                'description' => 'Transfer to test@example.com',
                'recipient'   => $recipient->id
            ]
        );
    }

    /**
     * Get a valid card data
     *
     * @param  string|null $cvc
     *
     * @return array
     */
    protected static function getValidCardData($cvc = null)
    {
        $card = [
            'number'    => '4242424242424242',
            'exp_month' => date('n'),
            'exp_year'  => date('Y') + 1,
        ];

        if ($cvc !== null) {
            $card['cvc'] = $cvc;
        }

        return $card;
    }
}
