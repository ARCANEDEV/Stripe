<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Coupon;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     CouponTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CouponTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Coupon */
    private $coupon;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->coupon = new Coupon;
    }

    public function tearDown()
    {
        unset($this->coupon);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\Coupon', $this->coupon);
    }

    /** @test */
    public function it_can_list_all()
    {
        $coupons = Coupon::all();

        $this->assertTrue($coupons->isList());
        $this->assertSame('/v1/coupons', $coupons->url);
    }

    /** @test */
    public function it_can_create_and_save()
    {
        $couponId     = $this->getCouponId();
        $this->coupon = $this->createTestCoupon($couponId);

        $this->assertSame($couponId, $this->coupon->id);
        $this->assertSame(25,        $this->coupon->percent_off);

        $this->coupon->metadata['foo'] = 'bar';
        $this->coupon->save();

        $stripeCoupon = Coupon::retrieve($couponId);
        $this->assertEquals($stripeCoupon->metadata, $this->coupon->metadata);
    }

    /** @test */
    public function it_can_update()
    {
        $couponId     = $this->getCouponId();
        $this->coupon = $this->createTestCoupon($couponId);

        $this->assertSame($couponId, $this->coupon->id);
        $this->assertSame(25,        $this->coupon->percent_off);

        $this->coupon = Coupon::update($couponId, [
            'metadata' => ['foo' => 'bar'],
        ]);

        $stripeCoupon = Coupon::retrieve($couponId);
        $this->assertEquals($stripeCoupon->metadata, $this->coupon->metadata);
    }

    /** @test */
    public function it_can_delete()
    {
        $couponId     = $this->getCouponId();
        $this->coupon = $this->createTestCoupon($couponId);
        $customer     = self::createTestCustomer([
            'coupon' => $couponId
        ]);

        $this->assertTrue(isset($customer->discount));
        $this->assertTrue(isset($customer->discount->coupon));
        $this->assertSame($couponId, $customer->discount->coupon->id);

        $customer->deleteDiscount();
        $this->assertFalse(isset($customer->discount));

        $this->coupon->delete();
        $this->assertTrue($this->coupon->deleted);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a random coupon id.
     *
     * @return string
     */
    protected function getCouponId()
    {
        return 'test_coupon-' . self::generateRandomString(20);
    }

    /**
     * Create a dummy coupon.
     *
     * @param  string  $couponId
     * @param  array   $params
     *
     * @return \Arcanedev\Stripe\Resources\Coupon|array
     */
    protected function createTestCoupon($couponId, array $params = [])
    {
        return Coupon::create(array_merge([
            'id'                 => $couponId,
            'percent_off'        => 25,
            'duration'           => 'repeating',
            'duration_in_months' => 5,
        ], $params));
    }
}
