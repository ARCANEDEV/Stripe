<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Http\RequestOptions;
use Arcanedev\Stripe\Resources\Source;
use Arcanedev\Stripe\Resources\SourceTransaction;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     SourceTransactionTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SourceTransactionTest extends StripeTestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_list()
    {
        $this->mockRequest(
            'GET',
            '/v1/sources/src_foo/source_transactions',
            [],
            [
                'object'   => 'list',
                'url'      => '/v1/sources/src_foo/source_transactions',
                'data'     => [
                    [
                        'id'     => 'srctxn_bar',
                        'object' => 'source_transaction',
                    ],
                ],
                'has_more' => false,
            ]
        );

        $source = Source::scopedConstructFrom(
            ['id' => 'src_foo', 'object' => 'source'],
            new RequestOptions
        );

        $transactions = $source->sourceTransactions();

        $this->assertSame($transactions->url, '/v1/sources/src_foo/source_transactions');
        $this->assertCount(1, $transactions->data);

        $transaction = $transactions->data[0];

        $this->assertInstanceOf(SourceTransaction::class, $transaction);
        $this->assertSame('srctxn_bar', $transaction->id);
    }
}
