<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersFeesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersFeesTable Test Case
 */
class UsersFeesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersFeesTable
     */
    public $UsersFees;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users_fees',
        'app.users',
        'app.team_members',
        'app.teams',
        'app.events',
        'app.places',
        'app.fees',
        'app.images'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UsersFees') ? [] : ['className' => UsersFeesTable::class];
        $this->UsersFees = TableRegistry::get('UsersFees', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsersFees);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
