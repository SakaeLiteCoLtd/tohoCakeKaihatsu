<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StaffAbilitiesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StaffAbilitiesTable Test Case
 */
class StaffAbilitiesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\StaffAbilitiesTable
     */
    public $StaffAbilities;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.staff_abilities',
        'app.staffs',
        'app.menus'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('StaffAbilities') ? [] : ['className' => StaffAbilitiesTable::class];
        $this->StaffAbilities = TableRegistry::getTableLocator()->get('StaffAbilities', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->StaffAbilities);

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
