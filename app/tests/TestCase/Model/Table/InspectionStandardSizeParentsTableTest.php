<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InspectionStandardSizeParentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InspectionStandardSizeParentsTable Test Case
 */
class InspectionStandardSizeParentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InspectionStandardSizeParentsTable
     */
    public $InspectionStandardSizeParents;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InspectionStandardSizeParents',
        'app.Products',
        'app.InspectionDataResultParents',
        'app.InspectionStandardSizeChildren'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InspectionStandardSizeParents') ? [] : ['className' => InspectionStandardSizeParentsTable::class];
        $this->InspectionStandardSizeParents = TableRegistry::getTableLocator()->get('InspectionStandardSizeParents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InspectionStandardSizeParents);

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
