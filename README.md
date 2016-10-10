# php-panaceamobile

An API client for sending SMSs via the [Panacea Mobile](https://www.panaceamobile.com) API

[![Author](http://img.shields.io/badge/author-@superbalist-blue.svg?style=flat-square)](https://twitter.com/superbalist)
[![Build Status](https://img.shields.io/travis/Superbalist/php-panaceamobile/master.svg?style=flat-square)](https://travis-ci.org/Superbalist/php-panaceamobile)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/superbalist/php-panaceamobile.svg?style=flat-square)](https://packagist.org/packages/superbalist/php-panaceamobile)
[![Total Downloads](https://img.shields.io/packagist/dt/superbalist/php-panaceamobile.svg?style=flat-square)](https://packagist.org/packages/superbalist/php-panaceamobile)


## Installation

```bash
composer require superbalist/php-panaceamobile
```

## Integrations

Want to get started quickly? Check out some of these integrations:

* Laravel - https://github.com/Superbalist/simple-sms-panacea-mobile

## Usage

```php
$username = 'your_username';
$password = 'your_password';

$guzzleClient = new \GuzzleHttp\Client();

$client = new \Superbalist\PanaceaMobile\PanaceaMobileAPI($guzzleClient, $username, $password);

$response = $client->sendMessage('+27000000000', 'This is a test message.');
var_dump($response);
```
