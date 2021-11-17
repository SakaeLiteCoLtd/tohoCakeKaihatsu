<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\KensakigusTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\KensakigusTable Test Case
 */
class KensakigusTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\KensakigusTable
     */
    public $Kensakigus;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Kensakigus',
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
        $config = TableRegistry::getTableLocator()->exists('Kensakigus') ? [] : ['className' => KensakigusTable::class];
        $this->Kensakigus = TableRegistry::getTableLocator()->get('Kensakigus', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Kensakigus);

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
