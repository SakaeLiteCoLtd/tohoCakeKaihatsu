<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InspectionDataConditonParentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InspectionDataConditonParentsTable Test Case
 */
class InspectionDataConditonParentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InspectionDataConditonParentsTable
     */
    public $InspectionDataConditonParents;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InspectionDataConditonParents',
        'app.InspectionDataConditonChildren'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InspectionDataConditonParents') ? [] : ['className' => InspectionDataConditonParentsTable::class];
        $this->InspectionDataConditonParents = TableRegistry::getTableLocator()->get('InspectionDataConditonParents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InspectionDataConditonParents);

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
}
