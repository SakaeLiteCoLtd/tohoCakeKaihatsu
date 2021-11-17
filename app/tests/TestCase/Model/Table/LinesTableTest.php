<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LinesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LinesTable Test Case
 */
class LinesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LinesTable
     */
    public $Lines;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Lines',
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
        $config = TableRegistry::getTableLocator()->exists('Lines') ? [] : ['className' => LinesTable::class];
        $this->Lines = TableRegistry::getTableLocator()->get('Lines', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Lines);

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
