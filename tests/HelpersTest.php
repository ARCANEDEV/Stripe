<?php namespace Arcanedev\Stripe\Tests;

/**
 * Class     HelpersTest
 *
 * @package  Arcanedev\Stripe\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class HelpersTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test STRING Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_parse_url_with_queries()
    {
        $baseUrl = 'http://www.api.com';

        $this->assertEquals($baseUrl, str_parse_url($baseUrl));
        $this->assertEquals($baseUrl, str_parse_url($baseUrl, []));

        $url = str_parse_url($baseUrl, [
            'my'    => 'value',
            'that'  => [
                'your' => 'example'
            ],
            'bar'   => 1,
            'baz'   => null
        ]);

        $this->assertEquals($baseUrl . '?my=value&that%5Byour%5D=example&bar=1', $url);
    }

    /** @test */
    public function it_can_encode_queries()
    {
        $this->assertEquals('?my=value', str_url_queries('?my=value'));

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

    /** @test */
    public function it_can_convert_to_utf8()
    {
        // UTF-8 string
        $this->assertEquals("\xc3\xa9", str_utf8("\xc3\xa9"));

        // Latin-1 string
        $this->assertEquals("\xc3\xa9", str_utf8("\xe9"));

        // Not a string
        $this->assertEquals(true, str_utf8(true));
    }

    /** @test */
    public function it_can_split_camel_case()
    {
        $this->assertNull(str_split_camelcase(null));
        $this->assertEquals('Camel Case Class', str_split_camelcase('CamelCaseClass'));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test ARRAY Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_check_is_multi_dimensional_array()
    {
        $this->assertFalse(is_multi_dim_array(null));
        $this->assertTrue(is_multi_dim_array([
            'foo' => 'foo',
            'bar' => 'bar',
            'baz' => [
                'hello' => 'world'
            ],
        ]));
    }

    /** @test */
    public function it_can_check_is_associative_array()
    {
        $this->assertFalse(is_assoc_array(null));
        $this->assertTrue(is_assoc_array([
            'foo' => 'foo',
            'bar' => 'bar',
            'baz' => 'baz',
        ]));
    }

    /** @test */
    public function it_can_check_is_indexed_array()
    {
        $this->assertFalse(is_assoc_array(['foo', 'bar', 'baz']));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Validation Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_check_is_valid_url()
    {
        $this->assertFalse(validate_url(null));
        $this->assertFalse(validate_url(''));
        $this->assertFalse(validate_url('www.api.com'));

        $this->assertTrue(validate_url('http://www.arcanedev.net'));
        $this->assertTrue(validate_url('http://www.api.com/v1/object?id=1'));
    }

    /** @test */
    public function it_can_check_is_valid_version()
    {
        $this->assertFalse(validate_version(null));
        $this->assertFalse(validate_version(''));
        $this->assertFalse(validate_version('x.x.x'));

        $this->assertTrue(validate_version('1.0.0'));
        $this->assertTrue(validate_version('2.1.123'));
    }

    /** @test */
    public function it_can_check_is_valid_boolean()
    {
        array_map(function($true) {
            $this->assertTrue(validate_bool($true));
        }, [true, 1, '1', 'on', 'On', 'ON', 'yes', 'Yes', 'YEs', 'YES']);

        array_map(function($false) {
            $this->assertFalse(validate_bool($false));
        }, [null, false, 0, '0', 'Off', 'OFf', 'OFf', 'no', 'No', 'NO', array()]);
    }
}
