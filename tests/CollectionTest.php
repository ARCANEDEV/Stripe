<?php namespace Arcanedev\Stripe\Tests;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Http\RequestOptions;

/**
 * Class     CollectionTest
 *
 * @package  Arcanedev\Stripe\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CollectionTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_auto_paging_one_page()
    {
        /** @var Collection $collection */
        $collection = Collection::scopedConstructFrom(
            Collection::class,
            $this->pageableModelResponse(['pm_123', 'pm_124'], false),
            new RequestOptions
        );

        $seen = [];

        foreach ($collection->autoPagingIterator() as $item) {
            array_push($seen, $item['id']);
        }

        $this->assertSame(['pm_123', 'pm_124'], $seen);
    }

    /** @test */
    public function it_can_auto_paging_three_pages()
    {
        /** @var Collection $collection */
        $collection = Collection::scopedConstructFrom(
            Collection::class,
            $this->pageableModelResponse(['pm_123', 'pm_124'], true),
            new RequestOptions
        );

        $collection->setRequestParams(['foo' => 'bar']);

        $this->mockRequest(
            'GET',
            '/v1/pageablemodels',
            [
                'foo'            => 'bar',
                'starting_after' => 'pm_124',
            ],
            $this->pageableModelResponse(['pm_125', 'pm_126'], true)
        );

        $this->mockRequest(
            'GET',
            '/v1/pageablemodels',
            [
                'foo'            => 'bar',
                'starting_after' => 'pm_126',
            ],
            $this->pageableModelResponse(['pm_127'], false)
        );

        $seen = [];

        foreach ($collection->autoPagingIterator() as $item) {
            array_push($seen, $item['id']);
        }

        $this->assertSame(['pm_123', 'pm_124', 'pm_125', 'pm_126', 'pm_127'], $seen);
    }

    /** @test */
    public function it_can_convert_iterator_to_array()
    {
        $collection = Collection::scopedConstructFrom(
            Collection::class,
            $this->pageableModelResponse(['pm_123', 'pm_124'], true),
            new RequestOptions
        );

        $this->mockRequest(
            'GET',
            '/v1/pageablemodels',
            ['starting_after' => 'pm_124'],
            $this->pageableModelResponse(['pm_125', 'pm_126'], true)
        );

        $this->mockRequest(
            'GET',
            '/v1/pageablemodels',
            ['starting_after' => 'pm_126'],
            $this->pageableModelResponse(['pm_127'], false)
        );

        $seen = [];

        foreach (iterator_to_array($collection->autoPagingIterator()) as $item) {
            array_push($seen, $item['id']);
        }

        $this->assertSame($seen, ['pm_123', 'pm_124', 'pm_125', 'pm_126', 'pm_127']);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param  array  $ids
     * @param  bool   $hasMore
     *
     * @return array
     */
    private function pageableModelResponse(array $ids, $hasMore)
    {
        $data = [];

        foreach ($ids as $id) {
            array_push($data, [
                'id'     => $id,
                'object' => 'pageablemodel',
            ]);
        }

        return [
            'object'   => 'list',
            'url'      => '/v1/pageablemodels',
            'data'     => $data,
            'has_more' => $hasMore,
        ];
    }
}
