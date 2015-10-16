<?php

// Include the PatrolServer PHP SDK
require_once "../init.php";

// Usage of the SDK objects
use PatrolSdk\Patrol;
use PatrolSdk\Webhook;
use PatrolSdk\Log;

/*
 * The log part is just an example, the SDK provides a testing environment where you can log data.
 * In this example, we'll use the Log interface to log incoming webhooks to "log.txt".
 */
Log::setPath(dirname(__FILE__) . '/log.txt');

// Logging is not enabled by default, we'll enable the Log interface manually.
Patrol::$enableLog = true;

/*
 * When the SDK retrieves a webhook event, it only contains an event_id and a webhook_id. It then fetches
 * the Event through the PatrolServer API (https://api.patrolserver.com). In order to access the API endpoints, a key and
 * secret is needed.
 */
Patrol::setApiKey('194786f61ea856b6468c0c41fa0d4bdb');
Patrol::setApiSecret('D6360a34e730ae96d74f545a286bfb01468cd01bb191eed49d9e421c2e56f958');

/*
 * Event: webhook.test
 *
 * This event gets triggered when you hit the "Test Webhook" button in the API page.
 * It's a great way to check if your webhook is working. If this logs, it works!
 */
Webhook::listen('webhook.test', function ($event) {
    Log::info('webhook.test');
    Log::info($event);
});

/*
 * Event: webhook.new_server_issues
 *
 * When new issues are found on your server. Let's say, your PHP software package suddenly becomes outdated,
 * this webhook will inform your software about the fact that that particular server now runs an outdated PHP package.
 *
 * An example of the Event data can be found here: https://api.patrolserver.com/#webhooks-integrations
 */
Webhook::listen('webhook.new_server_issues', function ($event) {
    Log::info('webhook.new_server_issues');
    Log::info($event);
});

/*
 * Event: webhook.scan_started
 *
 * When a scan is started, both manually or automatically, this webhook will inform your application.
 */
Webhook::listen('webhook.scan_started', function ($event) {
    Log::info('webhook.scan_started');
    Log::info($event);
});

/*
 * Event: webhook.scan_finished
 *
 * When we finished scanning your server, both manually or automatically, this webhook will inform your application.
 */
Webhook::listen('webhook.scan_finished', function ($event) {
    Log::info('webhook.scan_finished');
    Log::info($event);
});
