<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TeamMembersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TeamMembersTable Test Case
 */
class TeamMembersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TeamMembersTable
     */
    public $TeamMembers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.team_members',
        'app.users',
        'app.teams',
        'app.events',
        'app.places',
        'app.fees',
        'app.users_fees'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TeamMembers') ? [] : ['className' => TeamMembersTable::class];
        $this->TeamMembers = TableRegistry::get('TeamMembers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TeamMembers);

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
