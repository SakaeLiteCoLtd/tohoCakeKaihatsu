<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RelayLogsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RelayLogsTable Test Case
 */
class RelayLogsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RelayLogsTable
     */
    public $RelayLogs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.RelayLogs',
        'app.MachineRelays'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('RelayLogs') ? [] : ['className' => RelayLogsTable::class];
        $this->RelayLogs = TableRegistry::getTableLocator()->get('RelayLogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RelayLogs);

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