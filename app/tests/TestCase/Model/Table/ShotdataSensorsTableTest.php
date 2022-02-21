<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ShotdataSensorsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ShotdataSensorsTable Test Case
 */
class ShotdataSensorsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ShotdataSensorsTable
     */
    public $ShotdataSensors;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ShotdataSensors'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ShotdataSensors') ? [] : ['className' => ShotdataSensorsTable::class];
        $this->ShotdataSensors = TableRegistry::getTableLocator()->get('ShotdataSensors', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ShotdataSensors);

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
