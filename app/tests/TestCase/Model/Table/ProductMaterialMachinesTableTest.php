<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductMaterialMachinesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductMaterialMachinesTable Test Case
 */
class ProductMaterialMachinesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductMaterialMachinesTable
     */
    public $ProductMaterialMachines;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.product_material_machines',
        'app.product_material_parents',
        'app.product_machine_materials'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductMaterialMachines') ? [] : ['className' => ProductMaterialMachinesTable::class];
        $this->ProductMaterialMachines = TableRegistry::getTableLocator()->get('ProductMaterialMachines', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductMaterialMachines);

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
