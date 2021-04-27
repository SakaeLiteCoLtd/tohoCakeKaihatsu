<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MaterialTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MaterialTypesTable Test Case
 */
class MaterialTypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MaterialTypesTable
     */
    public $MaterialTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.material_types'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MaterialTypes') ? [] : ['className' => MaterialTypesTable::class];
        $this->MaterialTypes = TableRegistry::getTableLocator()->get('MaterialTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MaterialTypes);

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
