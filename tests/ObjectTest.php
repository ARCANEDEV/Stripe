<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Object;

class ObjectTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const OBJECT_CLASS = 'Arcanedev\\Stripe\\Object';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Object */
    protected $object;

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
    /** @test */
    public function it_can_be_instantiate()
    {
        $object = new Object('object-id', 'my-api-key');
        $method = self::getObjectMethod('getApiKey');

        $this->assertInstanceOf(self::OBJECT_CLASS, $object);
        $this->assertEquals('object-id', $object->id);
        $this->assertEquals('my-api-key', $method->invoke($object));
    }

    /** @test */
    public function it_can_populate_object_from_array()
    {
        $object = new Object([
            'id'        => 'object-id',
            'param_one' => 'condition-1',
            'param_two' => 'condition-2',
        ]);

        $method = self::getObjectMethod('getRetrieveParams');

        $this->assertTrue($object->hasRetrieveParams());
        $this->assertEquals([
            'param_one' => 'condition-1',
            'param_two' => 'condition-2'
        ], $method->invoke($object));
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Stripe\Exceptions\ApiException
     */
    public function it_must_throw_api_exception_if_id_not_found_in_array()
    {
        new Object([]);
    }

    /** @test */
    public function it_can_access_object_like_array()
    {
        $s          = new Object;
        $s['foo']   = 'a';
        $this->assertEquals('a', $s['foo']);
        $this->assertTrue(isset($s['foo']));

        unset($s['foo']);
        $this->assertFalse(isset($s['foo']));
    }

    /** @test */
    public function it_can_access_with_normal_accessors()
    {
        $s      = new Object;
        $s->foo = 'a';
        $this->assertEquals('a', $s->foo);
        $this->assertTrue(isset($s->foo));
        unset($s->foo);
        $this->assertFalse(isset($s->foo));
    }

    /** @test */
    public function it_can_get_values_keys()
    {
        $s      = new Object;
        $s->foo = 'a';
        $this->assertEquals(['foo'], $s->keys());
    }

    /** @test */
    public function it_must_return_null_by_accessing_undefined_property()
    {
        $object = new Object;

        $object->foo = 'bar';
        unset($object->foo);

        $this->assertNull($object->foo);
    }

    /** @test */
    public function it_can_convert_object_to_string()
    {
        $object = new Object('foo');
        $this->assertEquals(
            "Arcanedev\\Stripe\\Object JSON: {\n    \"id\": \"foo\"\n}",
            (string) $object
        );
    }

    /** @test */
    public function it_can_convert_object_to_array()
    {
        $object = new Object('foo');
        $this->assertEquals(['id' => 'foo'], $object->toArray());
    }

    /** @test */
    public function it_can_serialize_parameters()
    {
        $object = new Object('object-id', 'my-api-key');
        $object->object        = 'type';
        $object->name          = 'name';
        $object['description'] = 'description';
        $method = self::getObjectMethod('serializeParameters');

        $this->assertEquals([
            'object'      => 'type',
            'name'        => 'name',
            'description' => 'description',
        ], $method->invoke($object));

        $object->metadata = [
            'date-format' => 'Y-m-d',
        ];

        $this->assertEquals([
            'object'      => 'type',
            'name'        => 'name',
            'description' => 'description',
            'metadata'    => $object->metadata
        ], $method->invoke($object));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Object Method
     *
     * @param string $method
     *
     * @return \ReflectionMethod
     */
    protected static function getObjectMethod($method)
    {
        return parent::getMethod(self::OBJECT_CLASS, $method);
    }
}
