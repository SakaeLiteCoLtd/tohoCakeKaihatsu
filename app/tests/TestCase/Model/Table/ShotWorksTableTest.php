<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ShotWorksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ShotWorksTable Test Case
 */
class ShotWorksTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ShotWorksTable
     */
    public $ShotWorks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ShotWorks',
        'app.Factories',
        'app.ProductConditionParents'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ShotWorks') ? [] : ['className' => ShotWorksTable::class];
        $this->ShotWorks = TableRegistry::getTableLocator()->get('ShotWorks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ShotWorks);

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
