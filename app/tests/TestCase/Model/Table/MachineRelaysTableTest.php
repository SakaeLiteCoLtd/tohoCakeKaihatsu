<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MachineRelaysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MachineRelaysTable Test Case
 */
class MachineRelaysTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MachineRelaysTable
     */
    public $MachineRelays;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MachineRelays',
        'app.RelayLogs'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MachineRelays') ? [] : ['className' => MachineRelaysTable::class];
        $this->MachineRelays = TableRegistry::getTableLocator()->get('MachineRelays', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MachineRelays);

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
}
