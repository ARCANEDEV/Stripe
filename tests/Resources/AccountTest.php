<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\Account;
use Arcanedev\Stripe\Stripe;
use Arcanedev\Stripe\Tests\StripeTestCase;

/**
 * Class     AccountTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class AccountTest extends StripeTestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Stripe\Resources\Account */
    private $account;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function tearDown()
    {
        parent::tearDown();

        unset($this->account);
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_retrieve()
    {
        $response = $this->managedAccountResponse('acct_ABC');
        $this->mockRequest('GET', '/v1/account', [], $response);

        $this->account = Account::retrieve();
        $this->assertSame($this->account->id, 'acct_ABC');
    }

    /** @test */
    public function it_can_get_all()
    {
        $response = $this->managedAccountsListResponse('acct_ABC');
        $this->mockRequest('GET', '/v1/accounts', [], $response);

        $accounts = Account::all();

        $this->assertTrue($accounts->isList());
        $this->assertSame(1, $accounts->count());

        $this->account = $accounts->data[0];

        $this->assertSame('acct_ABC', $this->account->id);
    }

    /** @test */
    public function it_can_retrieve_by_Id()
    {
        $response = $this->managedAccountResponse('acct_DEF');
        $this->mockRequest('GET', '/v1/accounts/acct_DEF', [], $response);

        $this->account = Account::retrieve('acct_DEF');

        $this->assertSame($this->account->id, 'acct_DEF');
    }

    /** @test */
    public function it_can_create()
    {
        $response = $this->managedAccountResponse('acct_ABC');
        $this->mockRequest('POST', '/v1/accounts', ['managed' => 'true'], $response);

        $account = Account::create(['managed' => true]);

        $this->assertSame($account->id, 'acct_ABC');
    }

    /** @test */
    public function it_can_save_legal_entity()
    {
        $response = $this->managedAccountResponse('acct_ABC');
        $this->mockRequest('POST', '/v1/accounts', ['managed' => 'true'], $response);

        $response['legal_entity']['first_name'] = 'Bob';
        $params = [
            'legal_entity' => ['first_name' => 'Bob'],
        ];

        $this->mockRequest('POST', '/v1/accounts/acct_ABC', $params, $response);

        $this->account = Account::create(['managed' => true]);
        $this->account->legal_entity->first_name = 'Bob';
        $this->account->save();

        $this->assertSame('Bob', $this->account->legal_entity->first_name);
    }

    /** @test */
    public function it_can_update_legal_entity()
    {
        $response = $this->managedAccountResponse('acct_ABC');
        $this->mockRequest('POST', '/v1/accounts', ['managed' => 'true'], $response);

        $response['legal_entity']['first_name'] = 'Bob';
        $params = [
            'legal_entity' => ['first_name' => 'Bob'],
        ];

        $this->mockRequest('POST', '/v1/accounts/acct_ABC', $params, $response);

        $this->account = Account::create(['managed' => true]);
        $this->account = Account::update($this->account->id, [
            'legal_entity' => ['first_name' => 'Bob'],
        ]);

        $this->assertSame('Bob', $this->account->legal_entity->first_name);
    }

    /** @test */
    public function it_can_create_additional_owners()
    {
        $request  = [
            'managed'      => true,
            'country'      => 'GB',
            'legal_entity' => [
                'additional_owners' => [
                    0 => [
                        'dob' => [
                            'day'   => 12,
                            'month' => 5,
                            'year'  => 1970,
                        ],
                        'first_name' => 'John',
                        'last_name'  => 'Doe',
                    ],
                    1 => [
                        'dob' => [
                            'day'   => 8,
                            'month' => 4,
                            'year'  => 1979,
                        ],
                        'first_name' => 'Jane',
                        'last_name'  => 'Doe',
                    ],
                ],
            ],
        ];

        $account  = Account::create($request);
        $response = $account->toArray(true);
        $respAO   = $response['legal_entity']['additional_owners'];

        foreach ($request['legal_entity']['additional_owners'] as $index => $owner) {
            $this->assertSame($owner['dob'],        $respAO[$index]['dob']);
            $this->assertSame($owner['first_name'], $respAO[$index]['first_name']);
        }
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

        $this->assertSame(1, count($this->account->legal_entity->additional_owners));
        $this->assertSame('Bob', $this->account->legal_entity->additional_owners[0]->first_name);

        $this->account->legal_entity->additional_owners[0]->last_name = 'Smith';
        $this->account->save();

        $this->assertSame(1, count($this->account->legal_entity->additional_owners));
        $this->assertSame('Smith', $this->account->legal_entity->additional_owners[0]->last_name);

        $this->account['legal_entity']['additional_owners'][0]['last_name'] = 'Johnson';
        $this->account->save();

        $this->assertSame(1, count($this->account->legal_entity->additional_owners));
        $this->assertSame('Johnson', $this->account->legal_entity->additional_owners[0]->last_name);

        $this->account->legal_entity->additional_owners[0]->verification->document = 'file_123';
        $this->account->save();

        $this->assertSame('file_123', $this->account->legal_entity->additional_owners[0]->verification->document);

        $this->account->legal_entity->additional_owners[1] = array('first_name' => 'Jane');
        $this->account->save();

        $this->assertSame(2, count($this->account->legal_entity->additional_owners));
        $this->assertSame('Jane', $this->account->legal_entity->additional_owners[1]->first_name);
    }

    /** @test */
    public function it_can_delete()
    {
        $account = self::createTestAccount();

        $this->mockRequest(
            'DELETE',
            '/v1/accounts/' . $account->id,
            [],
            $this->deletedAccountResponse('acct_ABC')
        );

        $deleted = $account->delete();
        $this->assertSame($deleted->id, $account->id);
        $this->assertTrue($deleted->deleted);
    }

    /** @test */
    public function it_can_reject_an_account()
    {
        $account = self::createTestAccount();

        $this->mockRequest(
            'POST',
            '/v1/accounts/' . $account->id . '/reject',
            ['reason' => 'fraud'],
            $this->deletedAccountResponse('acct_ABC')
        );

        $rejected = $account->reject(['reason' => 'fraud']);
        $this->assertSame($rejected->id, $account->id);
    }

    /** @test */
    public function it_can_create_login_link_object()
    {
        $accountId = 'acct_EXPRESS';

        $this->mockRequest('GET', "/v1/accounts/$accountId", [], [
            'id'          => $accountId,
            'object'      => 'account',
            'login_links' => [
                'object'   => 'list',
                'data'     => [],
                'has_more' => false,
                'url'      =>  "/v1/accounts/$accountId/login_links"
            ]
        ]);
        $this->mockRequest('POST', "/v1/accounts/$accountId/login_links", [], [
            'object'  => 'login_link',
            'created' => 1493820886,
            'url'     => "https://connect.stripe.com/$accountId/AAAAAAAA"
        ]);

        $account   = Account::retrieve($accountId);
        $loginLink = $account->login_links->create();

        $this->assertSame('login_link', $loginLink->object);
        $this->assertInstanceOf(\Arcanedev\Stripe\Resources\LoginLink::class, $loginLink);
    }

    /** @test */
    public function it_can_deauthorize()
    {
        Stripe::setClientId('ca_test');

        $accountId = 'acct_test_deauth';

        $this->mockRequest('GET', "/v1/accounts/$accountId", [], [
            'id'     => $accountId,
            'object' => 'account',
        ]);

        $this->mockRequest(
            'POST',
            '/oauth/deauthorize',
            ['client_id' => 'ca_test', 'stripe_user_id' => $accountId],
            ['stripe_user_id' => $accountId],
            200,
            Stripe::$connectBase
        );

        $response = Account::retrieve($accountId)->deauthorize();

        $this->assertSame($accountId, $response->stripe_user_id);

        Stripe::setClientId(null);
    }

    /** @test */
    public function it_can_create_external_account()
    {
        $this->mockRequest(
            'POST',
            '/v1/accounts/acct_123/external_accounts',
            ['source' => 'btok_123'],
            ['id' => 'ba_123', 'object' => 'bank_account']
        );

        $externalAccount = Account::createExternalAccount('acct_123', ['source' => 'btok_123']);

        $this->assertSame('ba_123', $externalAccount->id);
        $this->assertSame('bank_account', $externalAccount->object);
    }

    /** @test */
    public function it_can_retrieve_external_account()
    {
        $this->mockRequest(
            'GET',
            '/v1/accounts/acct_123/external_accounts/ba_123',
            [],
            ['id' => 'ba_123', 'object' => 'bank_account']
        );

        $externalAccount = Account::retrieveExternalAccount('acct_123', 'ba_123');

        $this->assertSame('ba_123', $externalAccount->id);
        $this->assertSame('bank_account', $externalAccount->object);
    }

    /** @test */
    public function it_can_update_external_account()
    {
        $this->mockRequest(
            'POST',
            '/v1/accounts/acct_123/external_accounts/ba_123',
            ['metadata' => ['foo' => 'bar']],
            ['id' => 'ba_123', 'object' => 'bank_account']
        );

        $externalAccount = Account::updateExternalAccount(
            'acct_123',
            'ba_123',
            ['metadata' => ['foo' => 'bar']]
        );

        $this->assertSame('ba_123', $externalAccount->id);
        $this->assertSame('bank_account', $externalAccount->object);
    }

    /** @test */
    public function it_can_delete_external_account()
    {
        $this->mockRequest(
            'DELETE',
            '/v1/accounts/acct_123/external_accounts/ba_123',
            [],
            ['id' => 'ba_123', 'deleted' => true]
        );

        $externalAccount = Account::deleteExternalAccount('acct_123', 'ba_123');

        $this->assertSame('ba_123', $externalAccount->id);
        $this->assertSame(true, $externalAccount->deleted);
    }

    /** @test */
    public function it_can_get_all_external_accounts()
    {
        $this->mockRequest(
            'GET',
            '/v1/accounts/acct_123/external_accounts',
            [],
            ['object' => 'list', 'data' => []]
        );

        $externalAccounts = Account::allExternalAccounts('acct_123');

        $this->assertSame('list', $externalAccounts->object);
        $this->assertEmpty($externalAccounts->data);
    }

    /** @test */
    public function it_can_create_login_link()
    {
        $this->mockRequest(
            'POST',
            '/v1/accounts/acct_123/login_links',
            [],
            ['object' => 'login_link', 'url' => 'https://example.com']
        );

        $loginLink = Account::createLoginLink('acct_123');

        $this->assertSame('login_link', $loginLink->object);
        $this->assertSame('https://example.com', $loginLink->url);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
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
            'id'                   => $id,
            'currencies_supported' => [
                'usd', 'aed', 'afn', '...',
            ],
            'object'               => 'account',
            'business_name'        => 'Stripe.com',
            'bank_accounts'        => [
                'object'        => 'list',
                'total_count'   => 0,
                'has_more'      => false,
                'url'           => '/v1/accounts/' . $id . '/bank_accounts',
                'data'          => [],
            ],
            'verification'         => [
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
            'tos_acceptance'       => [
                'ip'         => null,
                'date'       => null,
                'user_agent' => null,
            ],
            'legal_entity'         => [
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

    /**
     * Get a accounts list response
     *
     * @param  string  $id
     *
     * @return array
     */
    private function managedAccountsListResponse($id)
    {
        $accounts = [
            $this->managedAccountResponse($id),
        ];

        return [
            'object'      => 'list',
            'url'         => '/v1/accounts',
            'has_more'    => false,
            'total_count' => count($accounts),
            'data'        => $accounts
        ];
    }

    /**
     * Get deleted account response
     *
     * @param  string  $id
     *
     * @return array
     */
    private function deletedAccountResponse($id)
    {
        return [
            'id'      => $id,
            'deleted' => true
        ];
    }
}
