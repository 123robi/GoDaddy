<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PlacesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PlacesTable Test Case
 */
class PlacesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PlacesTable
     */
    public $Places;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.places',
        'app.teams',
        'app.users',
        'app.images',
        'app.team_members',
        'app.fees',
        'app.users_fees',
        'app.events'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Places') ? [] : ['className' => PlacesTable::class];
        $this->Places = TableRegistry::get('Places', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Places);

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
