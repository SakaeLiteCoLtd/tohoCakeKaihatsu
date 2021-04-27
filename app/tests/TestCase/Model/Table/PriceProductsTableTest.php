<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PriceProductsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PriceProductsTable Test Case
 */
class PriceProductsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PriceProductsTable
     */
    public $PriceProducts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.price_products',
        'app.products',
        'app.custmoers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PriceProducts') ? [] : ['className' => PriceProductsTable::class];
        $this->PriceProducts = TableRegistry::getTableLocator()->get('PriceProducts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PriceProducts);

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
