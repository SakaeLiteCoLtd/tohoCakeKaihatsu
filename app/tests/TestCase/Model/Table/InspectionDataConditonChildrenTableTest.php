<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InspectionDataConditonChildrenTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InspectionDataConditonChildrenTable Test Case
 */
class InspectionDataConditonChildrenTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InspectionDataConditonChildrenTable
     */
    public $InspectionDataConditonChildren;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InspectionDataConditonChildren',
        'app.InspectionDataConditonParents',
        'app.ProductConditonChildren'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InspectionDataConditonChildren') ? [] : ['className' => InspectionDataConditonChildrenTable::class];
        $this->InspectionDataConditonChildren = TableRegistry::getTableLocator()->get('InspectionDataConditonChildren', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InspectionDataConditonChildren);

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
