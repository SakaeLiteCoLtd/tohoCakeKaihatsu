<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductMaterialsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductMaterialsTable Test Case
 */
class ProductMaterialsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductMaterialsTable
     */
    public $ProductMaterials;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.product_materials',
        'app.products',
        'app.materials'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductMaterials') ? [] : ['className' => ProductMaterialsTable::class];
        $this->ProductMaterials = TableRegistry::getTableLocator()->get('ProductMaterials', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductMaterials);

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
