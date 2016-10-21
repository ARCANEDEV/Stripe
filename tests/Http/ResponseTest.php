<?php namespace Arcanedev\Stripe\Tests\Http;

use Arcanedev\Stripe\Http\Response;
use Arcanedev\Stripe\Tests\TestCase;

/**
 * Class     ResponseTest
 *
 * @package  Arcanedev\Stripe\Tests\Http
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ResponseTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\Stripe\Http\Response */
    private $response;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->response = new Response(
            'response body', 200, ['Accept-Charset' => 'utf-8'], ['status' => 'success']
        );
    }

    public function tearDown()
    {
        unset($this->response);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Response::class, $this->response);
    }

    /** @test */
    public function it_can_get_body()
    {
        $this->assertSame('response body', $this->response->getBody());
    }

    /** @test */
    public function it_can_get_status_code()
    {
        $this->assertSame(200, $this->response->getCode());
        $this->assertSame(200, $this->response->getStatusCode());
    }

    /** @test */
    public function it_can_get_headers()
    {
        $this->assertSame(['Accept-Charset' => 'utf-8'], $this->response->getHeaders());
    }

    /** @test */
    public function it_can_get_json()
    {
        $this->assertSame(['status' => 'success'], $this->response->getJson());
    }
}
