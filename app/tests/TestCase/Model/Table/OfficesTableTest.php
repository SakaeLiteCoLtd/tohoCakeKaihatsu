<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OfficesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OfficesTable Test Case
 */
class OfficesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\OfficesTable
     */
    public $Offices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.offices',
        'app.companies',
        'app.departments',
        'app.occupations',
        'app.positions',
        'app.staffs'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Offices') ? [] : ['className' => OfficesTable::class];
        $this->Offices = TableRegistry::getTableLocator()->get('Offices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Offices);

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
