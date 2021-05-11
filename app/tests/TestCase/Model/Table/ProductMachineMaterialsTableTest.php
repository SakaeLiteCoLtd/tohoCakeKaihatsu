<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductMachineMaterialsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductMachineMaterialsTable Test Case
 */
class ProductMachineMaterialsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductMachineMaterialsTable
     */
    public $ProductMachineMaterials;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductMachineMaterials',
        'app.ProductMaterialMachines',
        'app.ProductMaterialLotNumbers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductMachineMaterials') ? [] : ['className' => ProductMachineMaterialsTable::class];
        $this->ProductMachineMaterials = TableRegistry::getTableLocator()->get('ProductMachineMaterials', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductMachineMaterials);

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
