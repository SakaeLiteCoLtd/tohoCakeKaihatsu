<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LinenamesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LinenamesTable Test Case
 */
class LinenamesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LinenamesTable
     */
    public $Linenames;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Linenames',
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
        $config = TableRegistry::getTableLocator()->exists('Linenames') ? [] : ['className' => LinenamesTable::class];
        $this->Linenames = TableRegistry::getTableLocator()->get('Linenames', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Linenames);

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
