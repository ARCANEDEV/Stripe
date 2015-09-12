<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Account;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     AccountTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class AccountTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const ACCOUNT_CLASS = 'Arcanedev\\Stripe\\Resources\\Account';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Account */
    private $account;

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

        unset($this->account);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_retrieve()
    {
        $response = $this->managedAccountResponse('acct_ABC');
        $this->mockRequest('GET', '/v1/account', [], $response);

        $this->account = Account::retrieve();
        $this->assertEquals($this->account->id, 'acct_ABC');
    }

    /** @test */
    public function it_can_retrieve_by_Id()
    {
        $response = $this->managedAccountResponse('acct_DEF');
        $this->mockRequest('GET', '/v1/accounts/acct_DEF', [], $response);

        $this->account = Account::retrieve('acct_DEF');

        $this->assertEquals($this->account->id, 'acct_DEF');
    }

    public function testCreate()
    {
        $response = $this->managedAccountResponse('acct_ABC');
        $this->mockRequest('POST', '/v1/accounts', ['managed' => 'true'], $response);

        $account = Account::create(['managed' => true]);

        $this->assertEquals($account->id, 'acct_ABC');
    }

    /** @test */
    public function it_can_update_legal_entity()
    {
        $response = $this->managedAccountResponse('acct_ABC');
        $this->mockRequest('POST', '/v1/accounts', ['managed' => 'true'], $response);

        $response['legal_entity']['first_name'] = 'Bob';
        $params = [
            'legal_entity' => [
                'first_name' => 'Bob',
            ],
        ];

        $this->mockRequest('POST', '/v1/accounts/acct_ABC', $params, $response);

        $this->account = Account::create(['managed' => true]);
        $this->account->legal_entity->first_name = 'Bob';
        $this->account->save();

        $this->assertEquals('Bob', $this->account->legal_entity->first_name);
    }

    /** @test */
    public function it_can_update_additional_owners()
    {
        $response = $this->managedAccountResponse('acct_ABC');
        $this->mockRequest('POST', '/v1/accounts', ['managed' => 'true'], $response);

        $response['legal_entity']['additional_owners'] = [
            [
                'first_name'    => 'Bob',
                'last_name'     => null,
                'address'       => [
                    'line1'         => null,
                    'line2'         => null,
                    'city'          => null,
                    'state'         => null,
                    'postal_code'   => null,
                    'country'       => null,
                ],
                'verification'  => [
                    'status'        => 'unverified',
                    'document'      => null,
                    'details'       => null,
                ]
            ]
        ];
        $params = [
            'legal_entity' => [
                'additional_owners' => [
                    ['first_name' => 'Bob'],
                ],
            ],
        ];

        $this->mockRequest('POST', '/v1/accounts/acct_ABC', $params, $response);

        $response['legal_entity']['additional_owners'][0]['last_name'] = 'Smith';

        $params = [
            'legal_entity' => [
                'additional_owners' => [
                    ['last_name' => 'Smith'],
                ],
            ],
        ];

        $this->mockRequest('POST', '/v1/accounts/acct_ABC', $params, $response);

        $response['legal_entity']['additional_owners'][0]['last_name'] = 'Johnson';
        $params = [
            'legal_entity' => [
                'additional_owners' => [
                    ['last_name' => 'Johnson'],
                ],
            ],
        ];

        $this->mockRequest('POST', '/v1/accounts/acct_ABC', $params, $response);

        $response['legal_entity']['additional_owners'][0]['verification']['document'] = 'file_123';
        $params = [
            'legal_entity' => [
                'additional_owners' => [
                    [
                        'verification' => ['document' => 'file_123'],
                    ],
                ],
            ],
        ];

        $this->mockRequest('POST', '/v1/accounts/acct_ABC', $params, $response);

        $response['legal_entity']['additional_owners'][1] = [
            'first_name' => 'Jane',
            'last_name'  => 'Doe'
        ];
        $params = [
            'legal_entity' => [
                'additional_owners' => [
                    1 => ['first_name' => 'Jane'],
                ],
            ],
        ];

        $this->mockRequest('POST', '/v1/accounts/acct_ABC', $params, $response);

        $this->account = Account::create(['managed' => true]);
        $this->account->legal_entity->additional_owners = [
            ['first_name' => 'Bob'],
        ];

        $this->account->save();

        $this->assertEquals(1, count($this->account->legal_entity->additional_owners));
        $this->assertEquals('Bob', $this->account->legal_entity->additional_owners[0]->first_name);

        $this->account->legal_entity->additional_owners[0]->last_name = 'Smith';
        $this->account->save();

        $this->assertEquals(1, count($this->account->legal_entity->additional_owners));
        $this->assertEquals('Smith', $this->account->legal_entity->additional_owners[0]->last_name);

        $this->account['legal_entity']['additional_owners'][0]['last_name'] = 'Johnson';
        $this->account->save();

        $this->assertEquals(1, count($this->account->legal_entity->additional_owners));
        $this->assertEquals('Johnson', $this->account->legal_entity->additional_owners[0]->last_name);

        $this->account->legal_entity->additional_owners[0]->verification->document = 'file_123';
        $this->account->save();

        $this->assertEquals('file_123', $this->account->legal_entity->additional_owners[0]->verification->document);

        $this->account->legal_entity->additional_owners[1] = array('first_name' => 'Jane');
        $this->account->save();

        $this->assertEquals('Jane', $this->account->legal_entity->additional_owners[1]->first_name);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get an account response
     *
     * @param  string  $id
     *
     * @return array
     */
    private function managedAccountResponse($id)
    {
        return [
            'id'            => $id,
            'currencies_supported' => [
                'usd', 'aed', 'afn', '...',
            ],
            'object'        => 'account',
            'business_name' => 'Stripe.com',
            'bank_accounts' => [
                'object'        => 'list',
                'total_count'   => 0,
                'has_more'      => false,
                'url'           => '/v1/accounts/' . $id . '/bank_accounts',
                'data'          => [],
            ],
            'verification'  => [
                'fields_needed' => [
                    'product_description',
                    'business_url',
                    'support_phone',
                    'bank_account',
                    'tos_acceptance.ip',
                    'tos_acceptance.date',
                ],
                'due_by'        => null,
                'contacted'     => false,
            ],
            'tos_acceptance' => [
                'ip'         => null,
                'date'       => null,
                'user_agent' => null,
            ],
            'legal_entity' => [
                'type'          => null,
                'business_name' => null,
                'address'       => [
                    'line1'       => null,
                    'line2'       => null,
                    'city'        => null,
                    'state'       => null,
                    'postal_code' => null,
                    'country'     => 'US',
                ],
                'first_name'        => null,
                'last_name'         => null,
                'additional_owners' => null,
                'verification'      => [
                    'status'    => 'unverified',
                    'document'  => null,
                    'details'   => null,
                ],
            ],
        ];
    }
}
