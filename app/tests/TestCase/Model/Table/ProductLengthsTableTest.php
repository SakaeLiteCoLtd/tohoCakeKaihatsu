<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductLengthsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductLengthsTable Test Case
 */
class ProductLengthsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductLengthsTable
     */
    public $ProductLengths;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductLengths',
        'app.Products'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductLengths') ? [] : ['className' => ProductLengthsTable::class];
        $this->ProductLengths = TableRegistry::getTableLocator()->get('ProductLengths', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductLengths);

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
