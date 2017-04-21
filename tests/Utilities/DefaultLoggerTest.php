<?php namespace Arcanedev\Stripe\Tests\Utilities;

use Arcanedev\Stripe\Tests\TestCase;
use Arcanedev\Stripe\Utilities\DefaultLogger;

/**
 * Class     DefaultLoggerTest
 *
 * @package  Arcanedev\Stripe\Tests\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DefaultLoggerTest extends TestCase
{
    /** @var  \Arcanedev\Stripe\Utilities\DefaultLogger */
    private $logger;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    protected function setUp()
    {
        parent::setUp();

        $this->logger = new DefaultLogger;
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_log_error()
    {
        $this->logger->error('Error message');
    }

}
