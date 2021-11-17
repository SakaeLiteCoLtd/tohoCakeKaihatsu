<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TanisTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TanisTable Test Case
 */
class TanisTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TanisTable
     */
    public $Tanis;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Tanis',
        'app.Factories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Tanis') ? [] : ['className' => TanisTable::class];
        $this->Tanis = TableRegistry::getTableLocator()->get('Tanis', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Tanis);

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
