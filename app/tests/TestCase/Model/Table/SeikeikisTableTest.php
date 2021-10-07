<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SeikeikisTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SeikeikisTable Test Case
 */
class SeikeikisTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SeikeikisTable
     */
    public $Seikeikis;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Seikeikis',
        'app.Factories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Seikeikis') ? [] : ['className' => SeikeikisTable::class];
        $this->Seikeikis = TableRegistry::getTableLocator()->get('Seikeikis', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Seikeikis);

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
