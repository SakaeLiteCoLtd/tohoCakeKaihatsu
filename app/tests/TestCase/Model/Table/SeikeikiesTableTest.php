<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SeikeikiesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SeikeikiesTable Test Case
 */
class SeikeikiesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SeikeikiesTable
     */
    public $Seikeikies;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Seikeikies'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Seikeikies') ? [] : ['className' => SeikeikiesTable::class];
        $this->Seikeikies = TableRegistry::getTableLocator()->get('Seikeikies', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Seikeikies);

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
