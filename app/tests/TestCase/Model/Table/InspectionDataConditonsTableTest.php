<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InspectionDataConditonsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InspectionDataConditonsTable Test Case
 */
class InspectionDataConditonsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InspectionDataConditonsTable
     */
    public $InspectionDataConditons;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InspectionDataConditons',
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
        $config = TableRegistry::getTableLocator()->exists('InspectionDataConditons') ? [] : ['className' => InspectionDataConditonsTable::class];
        $this->InspectionDataConditons = TableRegistry::getTableLocator()->get('InspectionDataConditons', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InspectionDataConditons);

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
