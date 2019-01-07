<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FeesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FeesTable Test Case
 */
class FeesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FeesTable
     */
    public $Fees;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.fees',
        'app.teams',
        'app.users',
        'app.images',
        'app.team_members',
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
        $config = TableRegistry::exists('Fees') ? [] : ['className' => FeesTable::class];
        $this->Fees = TableRegistry::get('Fees', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Fees);

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
