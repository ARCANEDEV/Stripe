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
    /** @var Response */
    private $response;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->response = new Response('response body', 200, [
            'Accept-Charset' => 'utf-8'
        ], [
            'status' => 'success'
        ]);
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->response);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf('Arcanedev\\Stripe\\Http\\Response', $this->response);
    }

    /** @test */
    public function it_can_get_body()
    {
        $this->assertEquals('response body', $this->response->getBody());
    }

    /** @test */
    public function it_can_get_status_code()
    {
        $this->assertEquals(200, $this->response->getCode());
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /** @test */
    public function it_can_get_headers()
    {
        $this->assertEquals(['Accept-Charset' => 'utf-8'], $this->response->getHeaders());
    }

    /** @test */
    public function it_can_get_json()
    {
        $this->assertEquals(['status' => 'success'], $this->response->getJson());
    }
}
