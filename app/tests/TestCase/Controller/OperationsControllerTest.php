<?php
namespace App\Test\TestCase\Controller;

use App\Controller\OperationsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\OperationsController Test Case
 */
class OperationsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Operations',
        'app.Companies',
        'app.Offices'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
