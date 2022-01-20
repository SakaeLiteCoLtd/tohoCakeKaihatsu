<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GroupNamesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GroupNamesTable Test Case
 */
class GroupNamesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GroupNamesTable
     */
    public $GroupNames;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.GroupNames',
        'app.Groups',
        'app.Users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('GroupNames') ? [] : ['className' => GroupNamesTable::class];
        $this->GroupNames = TableRegistry::getTableLocator()->get('GroupNames', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GroupNames);

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
