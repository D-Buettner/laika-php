<?php

require __DIR__ . '/../vendor/autoload.php';

use Medigo\Laika;

/**
 * Class LaikaTest
 **/

$mock = Phake::mock('Medigo\Laika');

class LaikaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Partial mock of Laika, the class being tested.
     *
     * @var Laika
     */
    private $laika;

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
     * Constructor function for LaikaTest.
     */
    public function setUp()
    {
        $this->laika = Phake::partialMock('Medigo\Laika', 'e', 'url');
    }

    /**
     * Tests Laika's "fetchAllFeatures" function.
     */
    public function testFetchAllFeatures()
    {
        //checks if laika can get the features and process them successfully
        Phake::when($this->laika)->httpRequest('api/features')->thenReturn($this->features);
        $this->assertEquals(true, $this->laika->fetchAllFeatures());
    }

    /**
     * Tests Laika's "isEnabled" function.
     */
    public function testIsEnabled()
    {
        $this->laika->setFeatures($this->features);

        //checks the status of an enabled feature
        $this->assertEquals(true, $this->laika->isEnabled('f1'));
        //checks the status of a disabled feature
        $this->assertEquals(false, $this->laika->isEnabled('f2'));
    }
}
