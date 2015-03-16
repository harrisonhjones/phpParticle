<?php

/*
 * @project phpSpark
 * @file    phpSpark.examples.php
 * @authors Harrison Jones (harrison@hhj.me)
 * @date    March 12, 2015
 * @brief   Examples file. Flash the code in phpSpark.firmware.cpp to your Spark Core and try these functions out
 */

// Include the required files. You will need to rename phpSpark.config.sample.php to phpSpark.config.php and then set the values within to use this example
if((@include 'phpSpark.class.php') === false)  die("Unable to load phpSpark class");
if((@include 'phpSpark.config.php') === false)  die("Unable to load phpSpark configuration file");

// Grab a new instance of our phpSpark object
$spark = new phpSpark();

// Set the internal debug to true. Note, calls made to $spark->debug(...) by you ignore this line and display always
$spark->setDebug(true);
// Set the debug calls to display pretty HTML format. Other option is "TEXT". Note, calls made to $spark->debug(...) display as set here
$spark->setDebugType("HTML");

// Set our access token (set in the phpConfig.config.php file)
$spark->setAccessToken($accessToken);

// Turn on the D7 LED (requires Tinker to be on your Spark Core)
$spark->debug("Spark Function");
if($spark->doFunction($deviceID, "digitalwrite", "D7,HIGH") == true)
{
    $spark->debug_r($spark->getResult());
}
else
{
    $spark->debug("Error: " . $spark->getError());
    $spark->debug("Error Source" . $spark->getErrorSource());
}

// Grab the current uptime of your core (requires a modified version of tinker on your Spark Core)
$spark->debug("Spark Variable");
if($spark->getVariable($deviceID, "uptime") == true)
{
    $spark->debug_r($spark->getResult());
}
else
{
    $spark->debug("Error: " . $spark->getError());
    $spark->debug("Error Source" . $spark->getErrorSource());
}

// List all the devices on your account
$spark->debug("Spark Devices");
if($spark->listDevices() == true)
{
    $spark->debug_r($spark->getResult());
}
else
{
    $spark->debug("Error: " . $spark->getError());
    $spark->debug("Error Source" . $spark->getErrorSource());
}

// Grab a specific device's info
$spark->debug("Spark Device Info");
if($spark->getDeviceInfo($deviceID) == true)
{
    $spark->debug_r($spark->getResult());
}
else
{
    $spark->debug("Error: " . $spark->getError());
    $spark->debug("Error Source" . $spark->getErrorSource());
}

// Rename your Spark Core
$spark->debug("Spark Set Device Name");
if($spark->setDeviceName($deviceID,"james") == true)
{
    $spark->debug_r($spark->getResult());
}
else
{
    $spark->debug("Error: " . $spark->getError());
    $spark->debug("Error Source" . $spark->getErrorSource());
}

// List of Spark core tokens
$spark->debug("Spark Tokens");
if($spark->listTokens($username,$password) == true)
{
    $spark->debug_r($spark->getResult());
}
else
{
    $spark->debug("Error: " . $spark->getError());
    $spark->debug("Error Source" . $spark->getErrorSource());
}

// End Program
?>