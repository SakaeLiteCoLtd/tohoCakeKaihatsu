<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InspectionDataResultParentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InspectionDataResultParentsTable Test Case
 */
class InspectionDataResultParentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InspectionDataResultParentsTable
     */
    public $InspectionDataResultParents;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InspectionDataResultParents',
        'app.InspectionDataConditonParents',
        'app.InspectionStandardSizeParents',
        'app.ProductConditonParents',
        'app.Products',
        'app.Staffs',
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
        $config = TableRegistry::getTableLocator()->exists('InspectionDataResultParents') ? [] : ['className' => InspectionDataResultParentsTable::class];
        $this->InspectionDataResultParents = TableRegistry::getTableLocator()->get('InspectionDataResultParents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InspectionDataResultParents);

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
