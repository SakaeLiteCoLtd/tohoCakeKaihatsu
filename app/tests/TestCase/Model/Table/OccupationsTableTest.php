<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OccupationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OccupationsTable Test Case
 */
class OccupationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\OccupationsTable
     */
    public $Occupations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.occupations',
        'app.offices',
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
        $config = TableRegistry::getTableLocator()->exists('Occupations') ? [] : ['className' => OccupationsTable::class];
        $this->Occupations = TableRegistry::getTableLocator()->get('Occupations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Occupations);

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
