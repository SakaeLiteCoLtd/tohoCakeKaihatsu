<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ShotdataBasesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ShotdataBasesTable Test Case
 */
class ShotdataBasesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ShotdataBasesTable
     */
    public $ShotdataBases;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ShotdataBases',
        'app.Factories',
        'app.ProductConditionParents'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ShotdataBases') ? [] : ['className' => ShotdataBasesTable::class];
        $this->ShotdataBases = TableRegistry::getTableLocator()->get('ShotdataBases', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ShotdataBases);

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
