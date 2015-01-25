<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Object;
use ReflectionClass;

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
        $this->assertInstanceOf(self::OBJECT_CLASS, $object);

        $this->assertEquals('object-id', $object->id);
        $this->assertEquals('my-api-key', self::getMethod($object, 'getApiKey'));
    }

    /** @test */
    public function it_can_populate_object_from_array()
    {
        $object = new Object([
            'id'        => 'object-id',
            'param_one' => 'condition-1',
            'param_two' => 'condition-2',
        ]);

        $this->assertTrue($object->hasRetrieveParams());
        $this->assertEquals([
            'param_one' => 'condition-1',
            'param_two' => 'condition-2'
        ], self::getMethod($object, 'getRetrieveParams'));
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

        $this->assertEquals([
            'object'      => 'type',
            'name'        => 'name',
            'description' => 'description',
        ], self::getMethod($object, 'serializeParameters'));

        $object->metadata = [
            'date-format' => 'Y-m-d',
        ];

        $this->assertEquals([
            'object'      => 'type',
            'name'        => 'name',
            'description' => 'description',
            'metadata'    => $object->metadata
        ], self::getMethod($object, 'serializeParameters'));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param mixed  $object
     * @param string $name
     * @param array  $params
     *
     * @return \ReflectionMethod
     */
    protected static function getMethod($object, $name, $params = [])
    {
        $class = new ReflectionClass(new Object);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method->invoke($object, $params);
    }
}
