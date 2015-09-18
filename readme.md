[![Build Status](https://travis-ci.org/articfox1986/phpParticle.svg?branch=master)](https://travis-ci.org/articfox1986/phpParticle)
[![Coverage Status](https://coveralls.io/repos/articfox1986/phpParticle/badge.svg?branch=master&service=github)](https://coveralls.io/github/articfox1986/phpParticle?branch=master)

phpSpark
========

PHP Class for interacting with the Spark Cloud (spark.io)

## Installation ##

- GIT clone or download a zip of the repo and unzip into your project director
- Rename `phpSpark.config.sample.php` to `phpSpark.config.php`
- Set your access token and device id in `phpSpark.config.php`
- (Optional) Copy and paste the code in `spark.firmware.cpp` into a new app in the Spark WebIDE & flash it to your core
- (Optional) Run the any of the examples in the `examples` folder

## Usage

- Check out the examples in the `examples` folder
- Try out the [phpSparkDashboard](https://github.com/harrisonhjones/phpSparkDashboard) project which uses this project ([demo](http://projects.harrisonhjones.com/phpSparkDashboard/))

## Implemented Features

### Device Management
- List Devices
- Get device info 
- Rename/Set device name
- Call Spark Function on a device
- Grab the value of a Spark Variable from a device
- Remote (Over the Air) Firmware Uploads
- Device signaling (make it flash a rainbow of colors)

### Access Token Management
- Generate a new access token
- List your access tokens
- Delete an access token

### Webhook Management

- List Webhooks
- Add Webhook
- Delete Webhook

### Account/Cloud Management
- Use a local spark cloud
- Claim core
- Remove core

## Not Yet Implemented Features
- OAuth Client Creation (/v1/clients)
- Advanced OAuth topics 