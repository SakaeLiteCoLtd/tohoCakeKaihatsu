<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MaterialsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MaterialsTable Test Case
 */
class MaterialsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MaterialsTable
     */
    public $Materials;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Materials',
        'app.Factories',
        'app.MaterialTypes',
        'app.PriceMaterials',
        'app.ProductMaterialParents',
        'app.不使用productMaterials'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Materials') ? [] : ['className' => MaterialsTable::class];
        $this->Materials = TableRegistry::getTableLocator()->get('Materials', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Materials);

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
