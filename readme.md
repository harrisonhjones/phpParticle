[![Build Status](https://travis-ci.org/articfox1986/phpParticle.svg?branch=master)](https://travis-ci.org/articfox1986/phpParticle)

phpParticle
========

PHP Class for interacting with the Particle Cloud (particle.io)

## Installation ##

- GIT clone or download a zip of the repo and unzip into your project director
- Rename `phpParticle.config.sample.php` to `phpParticle.config.php`
- Set your access token and device id in `phpParticle.config.php`
- (Optional) Copy and paste the code in `particle.firmware.cpp` into a new app in the Particle WebIDE & flash it to your core
- (Optional) Run the any of the examples in the `examples` folder

## Usage

- Check out the examples in the `examples` folder
- Try out the [phpParticleDashboard](https://github.com/harrisonhjones/phpParticleDashboard) project which uses this project ([demo](http://projects.harrisonhjones.com/phpParticleDashboard/))

## Implemented Features

### Device Management
- List Devices
- Get device info 
- Rename/Set device name
- Call Particle Function on a device
- Grab the value of a Particle Variable from a device
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
- Use a local particle cloud
- Claim core or photon
- Remove core or photon

## Not Yet Implemented Features
- OAuth Client Creation (/v1/clients)
- Advanced OAuth topics 