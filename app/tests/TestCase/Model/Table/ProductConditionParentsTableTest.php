<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductConditionParentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductConditionParentsTable Test Case
 */
class ProductConditionParentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductConditionParentsTable
     */
    public $ProductConditionParents;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.product_condition_parents',
        'app.products',
        'app.product_conditon_children'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductConditionParents') ? [] : ['className' => ProductConditionParentsTable::class];
        $this->ProductConditionParents = TableRegistry::getTableLocator()->get('ProductConditionParents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductConditionParents);

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
