phpSpark
========

PHP Class for interacting with the Spark Cloud (spark.io)

## Installation ##

- GIT clone or download a zip of the repo and unzip into your project director
- Rename `phpSpark.config.sample.php` to `phpSpark.config.php`
- Set your access token and device id in `phpSpark.config.php`
- (Optional) Copy and paste the code in `spark.firmware.cpp` into a new app in the Spark WebIDE & flash it to your core
- (Optional) Run the example file `phpSpark.examples.php`

## Usage
See `phpSpark.examples.php`

## Implemented Features

- List Devices
- Get device info 
- Rename/Set device name
- Call Spark Function on a device
- Grab the value of a Spark Variable from a device
- Generate a new access token
- List your access tokens
- Delete an access token
- Use a local spark cloud
- List Webhooks
- Delete Webhook

## To Do

- Allow for remote firmware uploads