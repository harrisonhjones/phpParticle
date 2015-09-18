<?php
 
use articfox1986\phpparticle\ParticleAPI;
 
class ParticleAPITest extends PHPUnit_Framework_TestCase {
 
  public function test_set_endpoint()
  {
    $particle = new ParticleAPI;
	$particle->setEndpoint("https://api.spark.io/");
    $this->assertEquals("https://api.spark.io/",$particle->getEndpoint());
  }
 
}