<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Contracts\Http\Curl\HttpClient as HttpClientContract;
use Arcanedev\Stripe\Exceptions\InvalidRequestException;
use Arcanedev\Stripe\Http\Curl\HttpClient;
use Arcanedev\Stripe\Http\Requestor;
use Arcanedev\Stripe\Resources\Account;
use Arcanedev\Stripe\Resources\Charge;
use Arcanedev\Stripe\Resources\Coupon;
use Arcanedev\Stripe\Resources\Customer;
use Arcanedev\Stripe\Resources\Plan;
use Arcanedev\Stripe\Resources\Recipient;
use Arcanedev\Stripe\Resources\Transfer;
use Arcanedev\Stripe\Stripe;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Class     StripeTestCase
 *
 * @package  Arcanedev\Stripe\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class StripeTestCase extends TestCase
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    const API_KEY = 'tGN0bIwXnHdwOa85VABjPdSn8nWY7G7I';

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var string */
    protected $myApiKey = 'my-secret-api-key';

    /** @var string */
    protected $myApiVersion = '';

    /** @var HttpClientContract|ObjectProphecy */
    protected $mock;

    /** @var int */
    protected $call;

    /** @var int */
    protected $timeout = 120;

    /* -----------------------------------------------------------------
     |  Setup Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        self::init();
    }

    private function init()
    {
        static::authorizeFromEnv();

        $this->myApiVersion = Stripe::VERSION;

        Requestor::setHttpClient(
            HttpClient::instance()->setTimeout($this->timeout)
        );

        // Peg the API version so that it can be varied independently of the one set on the test account.
        Stripe::setApiVersion('2017-04-06');
    }

    protected static function authorizeFromEnv()
    {
        $apiKey = getenv('STRIPE_API_KEY');

        if (is_null($apiKey)) $apiKey = self::API_KEY;

        Stripe::setApiKey($apiKey);
    }

    /* -----------------------------------------------------------------
     |  Mocks
     | -----------------------------------------------------------------
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
        $mock->request(strtolower($method), 'https://api.stripe.com' . $path, $params, [], false)
            ->willReturn([json_encode($return), $rcode, []]);
    }

    /**
     * Setup mock Request
     *
     * @return HttpClientContract|ObjectProphecy
     */
    protected function setUpMockRequest()
    {
        if (!$this->mock) {
            $this->mock = $this->prophesize(HttpClientContract::class);

            Requestor::setHttpClient($this->mock->reveal());
        }

        return $this->mock;
    }

    /* -----------------------------------------------------------------
     |  Helpers Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a valid test charge.
     *
     * @param  array $attributes
     *
     * @return \Arcanedev\Stripe\Resources\Charge
     */
    protected static function createTestCharge(array $attributes = [])
    {
        return Charge::create($attributes + [
            'amount'      => 2000,
            'currency'    => 'usd',
            'description' => 'Charge for test@example.com',
            'card'        => 'tok_visa',
        ]);
    }

    /**
     * Create a valid test customer.
     *
     * @param  array $attributes
     *
     * @return \Arcanedev\Stripe\Resources\Customer
     */
    protected static function createTestCustomer(array $attributes = [])
    {
        return Customer::create($attributes + [
            'card' => 'tok_visa',
        ]);
    }

    /**
     * Create a test account
     *
     * @param  array $attributes
     *
     * @return \Arcanedev\Stripe\Resources\Account
     */
    protected static function createTestAccount(array $attributes = [])
    {
        return Account::create($attributes + [
            'managed' => false,
            'country' => 'US',
            'email' => self::generateRandomEmail(),
        ]);
    }

    /**
     * Create a valid test recipient
     *
     * @param  array $attributes
     *
     * @return \Arcanedev\Stripe\Resources\Recipient
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
                'account_number' => '000123456789',
            ],
        ]);
    }

    /**
     * Verify that a plan with a given ID exists, or create a new one if it does not.
     *
     * @param  string|null $id
     *
     * @return \Arcanedev\Stripe\Resources\Plan
     */
    protected static function retrieveOrCreatePlan($id = null)
    {
        if (is_null($id))
            $id = 'gold-'.self::generateRandomString(20);

        try {
            return Plan::retrieve($id);
        }
        catch (InvalidRequestException $exception) {
            return Plan::create([
                'id' => $id,
                'amount' => 100,
                'currency' => 'usd',
                'interval' => 'month',
                'name' => 'Gold Test Plan',
            ]);
        }
    }

    /**
     * Verify that a coupon with a given ID exists, or create a new one if it does not.
     *
     * @param  string $id
     *
     * @return \Arcanedev\Stripe\Resources\Coupon
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
     * @param  array              $attributes
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Resources\Transfer|array
     */
    protected static function createTestTransfer(array $attributes = [], $options = null)
    {
        return Transfer::create($attributes + [
            'amount'      => 2000,
            'currency'    => 'usd',
            'description' => 'Transfer to test@example.com',
            'recipient'   => self::createTestRecipient()->id
        ], $options);
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

        if (!is_null($cvc)) $card['cvc'] = $cvc;

        return $card;
    }

    /**
     * Create a test account.
     *
     * @param  array  $attributes
     *
     * @return \Arcanedev\Stripe\Resources\Account
     */
    protected static function createTestManagedAccount(array $attributes = [])
    {
        return Account::create($attributes + [
            'managed'          => true,
            'country'          => 'US',
            'external_account' => [
                'object'         => 'bank_account',
                'country'        => 'US',
                'currency'       => 'usd',
                'routing_number' => '110000000',
                'account_number' => '000123456789'
            ],
            'legal_entity'     => [
                'type'               => 'individual',
                'personal_id_number' => '000000000',
                'dob'                => ['year' => '1980', 'month' => '01', 'day' => '01'],
                'first_name'         => 'John',
                'last_name'          => 'Doe',
                'address'            => [
                    'line1'          => '1234 Main Street',
                    'postal_code'    => '94110',
                    'city'           => 'San Francisco'
                ],
                'personal_address'   => [
                    'line1'          => '1234 Main Street',
                    'postal_code'    => '94110',
                    'city'           => 'San Francisco'
                ]
            ],
            'tos_acceptance'   => [
                'date' => time(),
                'ip' => '127.0.0.1',
            ],
        ]);
    }
}
