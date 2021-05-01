<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductMaterialParentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductMaterialParentsTable Test Case
 */
class ProductMaterialParentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductMaterialParentsTable
     */
    public $ProductMaterialParents;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.product_material_parents',
        'app.products',
        'app.inspection_data_result_parents',
        'app.product_material_machines'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductMaterialParents') ? [] : ['className' => ProductMaterialParentsTable::class];
        $this->ProductMaterialParents = TableRegistry::getTableLocator()->get('ProductMaterialParents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductMaterialParents);

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
