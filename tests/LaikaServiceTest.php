<?php
require 'vendor/autoload.php';
require 'LaikaService.php';

/**
 * Class LaikaServiceTest
 **/

$mock = Phake::mock('LaikaService');

class LaikaServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Partial mock of LaikaService, the class being tested.
     *
     * @var LaikaService
     */
    private $laikaService;

    /**
     * Array used on method stubbing to fake the information received from the server.
     *
     * @var array
     */
    private $features = array(
      "f1" => array(
        "id" => 1,
        "created_at" => "2016-03-04T00:00:00Z",
        "name" => "f1",
        "status" => array('e' => true)
      ),
      "f2" => array(
        "id" => 2,
        "created_at" => "2016-03-04T00:00:00Z",
        "name" => "f2",
        "status" => array('e' => false)
      )
    );

    /**
     * Constructor function for LaikaServiceTest.
     */
    public function setUp()
    {
        $this->laikaService = Phake::partialMock('LaikaService', 'e', 'url');
    }

    /**
     * Tests LaikaService's "fetchAllFeatures" function.
     */
    public function testFetchAllFeatures()
    {
        //checks if the service can get the features and process them successfully
        Phake::when($this->laikaService)->httpRequest('GET', 'api/features')->thenReturn($this->features);
        $this->assertEquals(true, $this->laikaService->fetchAllFeatures());
    }

    /**
     * Tests LaikaService's "isEnabled" function.
     */
    public function testIsEnabled()
    {
        $this->laikaService->setFeatures($this->features);

        //checks the status of an enabled feature
        $this->assertEquals(true, $this->laikaService->isEnabled('f1'));
        //checks the status of a disabled feature
        $this->assertEquals(false, $this->laikaService->isEnabled('f2'));
    }
}
