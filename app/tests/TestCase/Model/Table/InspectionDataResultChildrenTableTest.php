<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InspectionDataResultChildrenTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InspectionDataResultChildrenTable Test Case
 */
class InspectionDataResultChildrenTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InspectionDataResultChildrenTable
     */
    public $InspectionDataResultChildren;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.inspection_data_result_children',
        'app.inspection_data_result_parents',
        'app.inspection_standard_size_children'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InspectionDataResultChildren') ? [] : ['className' => InspectionDataResultChildrenTable::class];
        $this->InspectionDataResultChildren = TableRegistry::getTableLocator()->get('InspectionDataResultChildren', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InspectionDataResultChildren);

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
