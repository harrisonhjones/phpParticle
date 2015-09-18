<?php
 
use articfox1986\phpparticle\ParticleAPI;
 
class ParticleAPITest extends PHPUnit_Framework_TestCase {
 
  public function test_setting_endpoint()
  {
    $particle = new ParticleAPI;
	$result = $particle->setEndpoint("https://api.spark.io/");
	
    $this->assertEquals(true,$result);
	$this->assertEquals("https://api.spark.io/",$particle->getEndpoint());
  }
  
  public function test_setting_timeout()
  {
    $particle = new ParticleAPI;
	$result = $particle->setTimeout(15);
	
	$this->assertEquals(true,$result);
    $this->assertEquals(15,$particle->getTimeout());
  }
  
  public function test_setting_non_numeric_value_for_timeout()
  {
    $particle = new ParticleAPI;
	$result = $particle->setTimeout('test');
	
    $this->assertEquals(false,$result);
    $this->assertEquals('Non numeric timeout',$particle->getError());
    $this->assertEquals('setTimeout',$particle->getErrorSource());
  }
	
  public function test_setting_auth()
  {
    $particle = new ParticleAPI;
	$result = $particle->setAuth('test@test.com','password');
	
	$this->assertEquals(true,$result);
    $this->assertEquals('test@test.com',$particle->getEmail());
    $this->assertEquals('password',$particle->getPassword());
  }
  
}