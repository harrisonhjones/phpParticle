<?php
/*
 * @project phpParticle
 * @file    examples/newWebhook.php
 * @authors Devin Pearson (devin@blackhat.co.za)
 * @date    March 18, 2015
 * @brief   Examples file.
 */

// For testing purposes we want to be as strict as possible
error_reporting(E_STRICT);

// Include the required files. You will need to rename phpParticle.config.sample.php to phpParticle.config.php and then set the values within to use this example
if((@include '../phpParticle.class.php') === false)  die("Unable to load phpParticle class");
if((@include '../phpParticle.config.php') === false)  die("Unable to load phpParticle configuration file");

// Grab a new instance of our phpParticle object
$particle = new phpParticle();

// Set the internal debug to true. Note, calls made to $particle->debug(...) by you ignore this line and display always
$particle->setDebug(true);
// Set the debug calls to display pretty HTML format. Other option is "TEXT". Note, calls made to $particle->debug(...) display as set here
$particle->setDebugType("HTML");

// Set our access token (set in the phpConfig.config.php file)
$particle->setAccessToken($accessToken);

// create particle webhook
$particle->debug("Create Particle Web Hook");

$extras = array();
$extras['mydevices'] = true;
$extras['deviceid'] = $deviceID;
$extras['requestType'] = "POST";
//$extras['headers'] = array("X-Device-ID"=>"test");
//$extras["form"] = json_encode(array("form_name"=>"form_value"));      // Not implemented server side yet
//$extras['json'] = array("json_key"=>"json_value");
$extras['query'] = array("p1"=>"v1");
$extras['auth'] = array("username"=>"test","password"=>"test_password");

// headers & auth are mutually exclusive (can't have both at the same time or the call will fail on the cloud side)
// json and query are mutually exclusive

$fields = array_merge(array('event' => $event, 'url' => $url, 'deviceid' => $deviceID),$extras);
print_r($fields);

if($particle->newWebhook('test', 'http://google.com/',$extras) == true)
{
    $particle->debug_r($particle->getResult());
}
else
{
    $particle->debug("Error: " . $particle->getError());
    $particle->debug("Error Source" . $particle->getErrorSource());
}
?>