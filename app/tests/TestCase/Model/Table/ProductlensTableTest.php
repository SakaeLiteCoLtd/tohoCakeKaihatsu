<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductlensTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductlensTable Test Case
 */
class ProductlensTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductlensTable
     */
    public $Productlens;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Productlens',
        'app.Factories',
        'app.Customers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Productlens') ? [] : ['className' => ProductlensTable::class];
        $this->Productlens = TableRegistry::getTableLocator()->get('Productlens', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Productlens);

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
