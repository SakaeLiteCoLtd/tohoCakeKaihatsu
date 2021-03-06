<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductMaterialLotNumbersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductMaterialLotNumbersTable Test Case
 */
class ProductMaterialLotNumbersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductMaterialLotNumbersTable
     */
    public $ProductMaterialLotNumbers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.product_material_lot_numbers',
        'app.product_machine_materials',
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
        $config = TableRegistry::getTableLocator()->exists('ProductMaterialLotNumbers') ? [] : ['className' => ProductMaterialLotNumbersTable::class];
        $this->ProductMaterialLotNumbers = TableRegistry::getTableLocator()->get('ProductMaterialLotNumbers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductMaterialLotNumbers);

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
