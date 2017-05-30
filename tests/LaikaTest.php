<?php

require __DIR__ . '/../vendor/autoload.php';

use Medigo\Laika;

class LaikaTest extends \PHPUnit_Framework_TestCase
{

    private $laika;

    private $features = array(
      "f1" => array(
        "id" => 1,
        "created_at" => "2016-03-04T00:00:00Z",
        "name" => "f1",
        "status" => array('test' => true)
      ),
      "f2" => array(
        "id" => 2,
        "created_at" => "2016-03-04T00:00:00Z",
        "name" => "f2",
        "status" => array('test' => false)
      )
    );

    public function setUp()
    {
        $this->laika = Phake::partialMock('Medigo\Laika', 'test', 'http://example.org/', 'user', 'password');
        Phake::when($this->laika)->get('api/features')->thenReturn($this->features);
    }

    public function testGetFeatures()
    {
        $features = $this->laika->getFeatures();
        $this->assertEquals($this->features, $features);
    }

    public function testIsEnabled()
    {
        //checks the status of an enabled feature
        $this->assertEquals(true, $this->laika->isEnabled('f1'));
        //checks the status of a disabled feature
        $this->assertEquals(false, $this->laika->isEnabled('f2'));
    }
}
