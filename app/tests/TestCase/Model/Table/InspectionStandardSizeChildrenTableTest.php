<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InspectionStandardSizeChildrenTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InspectionStandardSizeChildrenTable Test Case
 */
class InspectionStandardSizeChildrenTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InspectionStandardSizeChildrenTable
     */
    public $InspectionStandardSizeChildren;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InspectionStandardSizeChildren',
        'app.InspectionStandardSizeParents',
        'app.InspectionDataResultChildren'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InspectionStandardSizeChildren') ? [] : ['className' => InspectionStandardSizeChildrenTable::class];
        $this->InspectionStandardSizeChildren = TableRegistry::getTableLocator()->get('InspectionStandardSizeChildren', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InspectionStandardSizeChildren);

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
