<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\StripeObject;

/**
 * Class     StripeObjectTest
 *
 * @package  Arcanedev\Stripe\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class StripeObjectTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiate()
    {
        $object = new StripeObject('object-id', 'my-api-key');

        $this->assertInstanceOf(StripeObject::class, $object);
        $this->assertSame('object-id', $object->id);
    }

    /** @test */
    public function it_can_populate_object_from_array()
    {
        $object = new StripeObject([
            'id'        => 'object-id',
            'param_one' => 'condition-1',
            'param_two' => 'condition-2',
        ]);

        $method = self::getObjectMethod('getRetrieveParams');

        $this->assertTrue($object->hasRetrieveParams());
        $this->assertSame([
            'param_one' => 'condition-1',
            'param_two' => 'condition-2',
        ], $method->invoke($object));
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     */
    public function it_must_throw_api_exception_if_id_not_found_in_array()
    {
        new StripeObject([]);
    }

    /** @test */
    public function it_can_access_object_like_array()
    {
        $object        = new StripeObject;
        $object['foo'] = 'a';

        $this->assertArrayHasKey('foo', $object);
        $this->assertSame('a', $object['foo']);

        unset($object['foo']);

        $this->assertArrayNotHasKey('foo', $object);
    }

    /** @test */
    public function it_can_access_with_normal_accessors()
    {
        $object      = new StripeObject;
        $object->foo = 'a';

        $this->assertNotNull(isset($object->foo));
        $this->assertSame('a', $object->foo);

        unset($object->foo);

        $this->assertNull($object->foo);
    }

    /** @test */
    public function it_can_get_values_keys()
    {
        $object      = new StripeObject;
        $object->foo = 'a';

        $this->assertSame(['foo'], $object->keys());
    }

    /** @test */
    public function it_must_return_null_by_accessing_undefined_property()
    {
        $object = new StripeObject;

        $object->foo = 'bar';
        unset($object->foo);

        $this->assertNull($object->foo);
    }

    /** @test */
    public function it_can_convert_object_to_string()
    {
        $object = new StripeObject('foo');

        $this->assertSame(
            "Arcanedev\\Stripe\\StripeObject JSON: {\n    \"id\": \"foo\"\n}",
            (string) $object
        );
    }

    /** @test */
    public function it_can_convert_object_to_array()
    {
        $object = new StripeObject('foo');
        $this->assertSame(['id' => 'foo'], $object->toArray());
    }

    /** @test */
    public function it_can_serialize_parameters()
    {
        $object = new StripeObject('object-id', 'my-api-key');
        $object->object        = 'type';
        $object->name          = 'name';
        $object['description'] = 'description';
        $method = self::getObjectMethod('serializeParameters');

        $this->assertSame([
            'object'      => 'type',
            'name'        => 'name',
            'description' => 'description',
        ], $method->invoke($object));

        $object->metadata = [
            'date-format' => 'Y-m-d',
        ];

        $this->assertSame([
            'object'      => 'type',
            'name'        => 'name',
            'description' => 'description',
            'metadata'    => $object->metadata,
        ], $method->invoke($object));
    }

    /** @test */
    public function it_can_encode_to_json()
    {
        $object      = new StripeObject;
        $object->foo = 'a';

        $this->assertJson($json = json_encode($object));
        $this->assertSame('{"foo":"a"}', $json);
    }

    /** @test */
    public function it_can_replace_new_nested_updatable_attributes()
    {
        $object = new StripeObject;

        $object->metadata = ['foo'];

        $this->assertSame(['foo'], $object->metadata);

        $object->metadata = ['bar', 'baz'];

        $this->assertSame(['bar', 'baz'], $object->metadata);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get StripeObject Method
     *
     * @param  string  $method
     *
     * @return \ReflectionMethod
     */
    protected static function getObjectMethod($method)
    {
        return parent::getMethod(StripeObject::class, $method);
    }
}
