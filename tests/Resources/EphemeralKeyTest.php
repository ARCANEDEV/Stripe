<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\EphemeralKey;
use Arcanedev\Stripe\Stripe;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     EphemeralKeyTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class EphemeralKeyTest extends StripeTestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    private $oldApiVersion = null;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /** @before */
    public function setUpApiVersion()
    {
        $this->oldApiVersion = Stripe::getApiVersion();
    }

    /** @after */
    public function tearDownApiVersion()
    {
        Stripe::setApiVersion($this->oldApiVersion);
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_create()
    {
        $response = $this->ephemeralKeyResponse('cus_123');

        $this->mockCreate($response);

        $key = EphemeralKey::create(
            ['customer'       => 'cus_123'],
            ['stripe_version' => '2017-05-25']
        );

        $this->assertSame($response['id'], $key->id);
    }

    /**
     * @test
     *
     * @expectedException         \InvalidArgumentException
     * @expectedExceptionMessage  The `stripe_version` must be specified to create an ephemeral key
     */
    public function it_must_throw_en_exception_while_creating_without_global_api_version_and_param()
    {
        EphemeralKey::create(['customer' => 'cus_123']);
    }

    /**
     * @test
     *
     * @expectedException         \InvalidArgumentException
     * @expectedExceptionMessage  The `stripe_version` must be specified to create an ephemeral key
     */
    public function it_must_throw_an_exception_if_the_version_option_is_not_present_while_global_api_version_exists()
    {
        Stripe::setApiVersion('2017-06-05');

        EphemeralKey::create(['customer' => 'cus_123']);
    }

    /** @test */
    public function it_can_create_with_global_version_and_option_version()
    {
        Stripe::setApiVersion('2017-06-05');

        $response = $this->ephemeralKeyResponse('cus_123');
        $this->mockCreate($response);

        $key = EphemeralKey::create(
            ['customer' => 'cus_123'],
            ['stripe_version' => '2017-05-25']
        );

        $this->assertSame($response['id'], $key->id);
    }

    /** @test */
    public function it_can_delete()
    {
        $response = $this->ephemeralKeyResponse('cus_123');
        $this->mockCreate($response);
        $this->mockDelete($response);
        $key = EphemeralKey::create(
            ['customer' => 'cus_123'],
            ['stripe_version' => '2017-05-25']
        );
        $deleted = $key->delete();
        $this->assertSame($key->id, $deleted->id);
    }

    /* -----------------------------------------------------------------
     |  Other methods
     | -----------------------------------------------------------------
     */

    /**
     * Mock the create response.
     *
     * @param  array  $response
     */
    protected function mockCreate($response)
    {
        $this->mockRequest(
            'POST',
            '/v1/ephemeral_keys',
            ['customer' => $response['associated_objects'][0]['id']],
            $response
        );
    }

    /**
     * Mock the delete response.
     *
     * @param  array  $response
     */
    protected function mockDelete($response)
    {
        $this->mockRequest(
            'DELETE',
            "/v1/ephemeral_keys/{$response['id']}",
            [],
            $response
        );
    }

    /**
     * Mock request
     *
     * @param  string $method
     * @param  string $path
     * @param  array $params
     * @param  array $return
     * @param  int $rcode
     */
    protected function mockRequest($method, $path, $params = [], $return = ['id' => 'myId'], $rcode = 200)
    {
        $mock = $this->setUpMockRequest();

        $mock->setApiKey(self::API_KEY)->willReturn($mock);
        $mock->request(strtolower($method), 'https://api.stripe.com' . $path, $params, ['Stripe-Version' => '2017-05-25'], false)
            ->willReturn([json_encode($return), $rcode, []]);
    }

    protected function ephemeralKeyResponse($customer)
    {
        return [
            'id'                 => 'ephkey_123',
            'object'             => 'ephemeral_key',
            'associated_objects' => [
                [
                    'type' => 'customer',
                    'id'   => $customer
                ]
            ],
            'created'            => 1496957039,
            'expires'            => 1496960639,
            'livemode'           => false,
            'secret'             => 'ek_test_supersecretstring'
        ];
    }
}
