<?php namespace Arcanedev\Stripe\Tests;

class HelpersTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanEncodeQueries()
    {
        $this->assertEquals(
            'my=value&that%5Byour%5D=example&bar=1',
            str_url_queries([
                'my'    => 'value',
                'that'  => [
                    'your' => 'example'
                ],
                'bar'   => 1,
                'baz'   => null
            ])
        );

        $this->assertEquals(
            'that%5Byour%5D=example',
            str_url_queries([
                'that' => [
                    'your'  => 'example',
                    'foo'   => null,
                ],
            ])
        );

        $this->assertEquals(
            'that=example&foo%5B%5D=bar&foo%5B%5D=baz',
            str_url_queries([
                [
                    'that' => 'example',
                    'foo' => [
                        'bar', 'baz'
                    ]
                ]
            ])
        );

        $this->assertEquals(
            'my=value&that%5Byour%5D%5B%5D=cheese&that%5Byour%5D%5B%5D=whiz&bar=1',
            str_url_queries([
                'my' => 'value',
                'that' => [
                    'your' => ['cheese', 'whiz', null]
                ],
                'bar' => 1,
                'baz' => null
            ])
        );
    }

    /**
     * @test
     */
    public function testCanConvertToUtf8()
    {
        // UTF-8 string
        $this->assertEquals("\xc3\xa9", str_utf8("\xc3\xa9"));

        // Latin-1 string
        $this->assertEquals("\xc3\xa9", str_utf8("\xe9"));

        // Not a string
        $this->assertEquals(true, str_utf8(true));
    }
}
