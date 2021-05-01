<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductConditonChildrenTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductConditonChildrenTable Test Case
 */
class ProductConditonChildrenTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductConditonChildrenTable
     */
    public $ProductConditonChildren;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.product_conditon_children',
        'app.product_condition_parents'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductConditonChildren') ? [] : ['className' => ProductConditonChildrenTable::class];
        $this->ProductConditonChildren = TableRegistry::getTableLocator()->get('ProductConditonChildren', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductConditonChildren);

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
