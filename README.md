# PatrolServer PHP SDK
The PHP SDK provides a stable interface to implement PatrolServer functionality in your own applications. You can signup for a PatrolServer account at [https://patrolserver.com](https://patrolserver.com). For a more in-depth explanation on how webhooks work, check out the blog post covering the introduction of PatrolServer's webhooks at [https://blog.patrolserver.com/2015/10/15/introducting-webhooks/](https://blog.patrolserver.com/2015/10/15/introducting-webhooks/).

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
// Use the Singleton or create a separate PatrolSdk\Patrol object
use PatrolSdk\Singleton as Patrol;

Patrol::setApiKey('194786f61ea856b6468c0c41fa0d4bdb');
Patrol::setApiSecret('D6360a34e730ae96d74f545a286bfb01468cd01bb191eed49d9e421c2e56f958');

Patrol::webhook('webhook.scan_finished', function ($event) {
	$server_id = $event['server_id'];

	if ($server_id) {
		// Get the Server object from the server_id
		$server = Patrol::server($server_id);
		
		// Get the installed software
		$software = $server->allSoftware();
	}
});
```

## Documentation
See [https://api.patrolserver.com/](https://api.patrolserver.com/) for the latest documentation.

[![Analytics](https://ga-beacon.appspot.com/UA-65036233-1/PatrolServer/patrolsdk-php?pixel)](https://github.com/igrigorik/ga-beacon)
