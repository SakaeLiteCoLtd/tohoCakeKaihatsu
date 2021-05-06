<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FactoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FactoriesTable Test Case
 */
class FactoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FactoriesTable
     */
    public $Factories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.factories',
        'app.companies',
        'app.staffs'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Factories') ? [] : ['className' => FactoriesTable::class];
        $this->Factories = TableRegistry::getTableLocator()->get('Factories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Factories);

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
