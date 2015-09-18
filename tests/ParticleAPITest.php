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
  
  public function test_clearing_auth()
  {
    $particle = new ParticleAPI;
	$result = $particle->clearAuth();
	
	$this->assertEquals(true,$result);
    $this->assertEquals(false, $particle->getEmail());
    $this->assertEquals(false,$particle->getPassword());
  }
  
  public function test_setting_access_token()
  {
    $particle = new ParticleAPI;
	$result = $particle->setAccessToken('a5a7b2d620fa349c8e825f02a6513de6ca7baabb');
	
	$this->assertEquals(true,$result);
    $this->assertEquals('a5a7b2d620fa349c8e825f02a6513de6ca7baabb',$particle->getAccessToken());
  }
  
  public function test_clearing_access_token()
  {
    $particle = new ParticleAPI;
	$result = $particle->clearAccessToken();
	
	$this->assertEquals(true,$result);
    $this->assertEquals(false,$particle->getAccessToken());
  }
  
  public function test_setting_debug_type()
  {
    $particle = new ParticleAPI;
	$result = $particle->setDebugType('TEXT');
	
	$this->assertEquals(true,$result);
    $this->assertEquals('TEXT',$particle->getDebugType());
  }
  
  public function test_setting_debug_type_invalid()
  {
    $particle = new ParticleAPI;
	$result = $particle->setDebugType('SomethingStrange');
	
	$this->assertEquals(false,$result);
	$this->assertContains('Bad debut type',$particle->getError());
    $this->assertEquals('HTML',$particle->getDebugType());
  }
  
  public function test_setting_debug_to_true()
  {
    $particle = new ParticleAPI;
	$result = $particle->setDebug(true);
	
	$this->assertEquals(true,$result);
    $this->assertEquals(true,$particle->getDebug());
  }
  
  public function test_setting_disable_ssl_to_true()
  {
    $particle = new ParticleAPI;
	$result = $particle->setDisableSSL(true);
	
	$this->assertEquals(true,$result);
    $this->assertEquals(true,$particle->getDisableSSL());
  }
  
  public function test_setting_debug_message()
  {
    $particle = new ParticleAPI;
	$result = $particle->debug('debugging text');
	
	$this->assertEquals(true,$result);
  }

  
}