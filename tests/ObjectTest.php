<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Object;
use ReflectionClass;

class ObjectTest extends StripeTestCase
{
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
    /**
     * @test
     */
    public function testCanBeInstantiate()
    {
        $object = new Object('object-id', 'my-api-key');
        $this->assertInstanceOf('Arcanedev\\Stripe\\Object', $object);

        $this->assertEquals('object-id', $object->id);
        $this->assertEquals('my-api-key', self::getMethod($object, 'getApiKey'));
    }

    /**
     * @test
     */
    public function testCanPopulateFromArray()
    {
        $object = new Object([
            'id'        => 'object-id',
            'param_one' => 'condition-1',
            'param_two' => 'condition-2',
        ]);

        $this->assertEquals(2, self::getMethod($object, 'retrieveParamsCount'));
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
    public function testMustThrowApiExceptionIfIdNotFoundInArray()
    {
        new Object([]);
    }

    /**
     * @test
     */
    public function testArrayAccessorsSemantics()
    {
        $s          = new Object;
        $s['foo']   = 'a';
        $this->assertEquals('a', $s['foo']);
        $this->assertTrue(isset($s['foo']));

        unset($s['foo']);
        $this->assertFalse(isset($s['foo']));
    }

    /**
     * @test
     */
    public function testNormalAccessorsSemantics()
    {
        $s      = new Object;
        $s->foo = 'a';
        $this->assertEquals('a', $s->foo);
        $this->assertTrue(isset($s->foo));
        unset($s->foo);
        $this->assertFalse(isset($s->foo));
    }

    /**
     * @test
     */
    public function testArrayAccessorsMatchNormalAccessors()
    {
        $s        = new Object;
        $s->foo   = 'a';
        $this->assertEquals('a', $s['foo']);

        $s['bar'] = 'b';
        $this->assertEquals('b', $s->bar);
    }

    /**
     * @test
     */
    public function testKeys()
    {
        $s      = new Object;
        $s->foo = 'a';
        $this->assertEquals(['foo'], $s->keys());
    }

    /**
     * @test
     */
    public function testMustReturnNullAccessingUndefinedProperty()
    {
        $object = new Object;
        $this->assertNull($object->foo);

        $object->foo = 'bar';
        unset($object->foo);

        $this->assertNull($object->foo);
    }

    /**
     * @test
     */
    public function testCanConvertObjectToString()
    {
        $object = new Object('foo');
        $this->assertEquals(
            "Arcanedev\\Stripe\\Object JSON: {\n    \"id\": \"foo\"\n}",
            (string) $object
        );
    }

    /**
     * @test
     */
    public function testCanConvertObjectToArray()
    {
        $object = new Object('foo');
        $this->assertEquals(['id' => 'foo'], $object->toArray());
    }

    /**
     * @test
     */
    public function testCanSerializeParameters()
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
        // 'Arcanedev\\Stripe\\Object'
        $class = new ReflectionClass(new Object);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method->invoke($object, $params);
    }
}
