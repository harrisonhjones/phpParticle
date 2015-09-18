<?php
/*
 * @project phpSpark
 * @file    examples/callFunction.php
 * @authors Harrison Jones (harrison@hhj.me)
 * @date    March 16, 2015
 * @brief   Examples file. Flash the code in phpSpark.firmware.cpp to your Spark Core and try these functions out
 */

// For testing purposes we want to be as strict as possible
error_reporting(E_STRICT);

// Include the required files. You will need to rename phpSpark.config.sample.php to phpSpark.config.php and then set the values within to use this example
if((@include '../phpSpark.class.php') === false)  die("Unable to load phpSpark class");
if((@include '../phpSpark.config.php') === false)  die("Unable to load phpSpark configuration file");

// Grab a new instance of our phpSpark object
$spark = new phpSpark();

// Set the internal debug to true. Note, calls made to $spark->debug(...) by you ignore this line and display always
$spark->setDebug(true);
// Set the debug calls to display pretty HTML format. Other option is "TEXT". Note, calls made to $spark->debug(...) display as set here
$spark->setDebugType("HTML");

// Set the timeout to be pretty short (in case your core is offline)
$spark->setTimeout("5");

// Set our access token (set in the phpConfig.config.php file)
$spark->setAccessToken($accessToken);

// Turn on the D7 LED (requires Tinker to be on your Spark Core)
$spark->debug("Spark Function");
if($spark->callFunction($deviceID, "digitalwrite", "D7,HIGH") == true)
{
    $spark->debug_r($spark->getResult());
}
else
{
    $spark->debug("Error: " . $spark->getError());
    $spark->debug("Error Source" . $spark->getErrorSource());
}
?>