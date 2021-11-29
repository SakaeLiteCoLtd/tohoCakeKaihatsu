<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DailyReportsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DailyReportsTable Test Case
 */
class DailyReportsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DailyReportsTable
     */
    public $DailyReports;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.DailyReports',
        'app.Products',
        'app.InspectionDataResultParents'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('DailyReports') ? [] : ['className' => DailyReportsTable::class];
        $this->DailyReports = TableRegistry::getTableLocator()->get('DailyReports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DailyReports);

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
