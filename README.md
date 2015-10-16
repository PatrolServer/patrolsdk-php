# PatrolServer PHP SDK
The PHP SDK provides a stable interface to implement PatrolServer functionality in your own applications. You can signup for a PatrolServer account at [https://patrolserver.com](https://patrolserver.com).

## Requirements
PHP 5.3.3 and later.

## Composer
You can install the SDK via [Composer](https://getcomposer.org/). Add the following rules to your ``composer.json``.
```
{
  "require": {
    "patrolserver/patrolsdk-php": "1.*"
  }
}
```
Next, install the composer packages with:
```
composer install
```
In order to use the SDK, use Composer's [autoload](https://getcomposer.org/doc/00-intro.md#autoloading):
```php
require_once('vendor/autoload.php');
```
If you do not want to install the SDK with Composer, you can download the latest version here and require ``init.php`` in the ``lib`` folder.

## Usage
```php
use PatrolSdk\Patrol;
use PatrolSdk\Webhook;

Patrol::setApiKey('194786f61ea856b6468c0c41fa0d4bdb');
Patrol::setApiSecret('D6360a34e730ae96d74f545a286bfb01468cd01bb191eed49d9e421c2e56f958');

Webhook::listen('webhook.scan_finished', function ($event) {
	$server_id = $event['server_id'];

	// You can get the Server object with https://api.patrolserver.com/servers/{id}
});
```

## Documentation
See [https://api.patrolserver.com/](https://api.patrolserver.com/) for the latest documentation.

[![Analytics](https://ga-beacon.appspot.com/UA-65036233-1/PatrolServer/patrolsdk-php?pixel)](https://github.com/igrigorik/ga-beacon)
