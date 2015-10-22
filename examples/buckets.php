<?php

// Include the PatrolServer PHP SDK
require_once "../init.php";

// Usage of the SDK objects
use PatrolSdk\Singleton as Patrol;

Patrol::setApiKey('ed94f55159c0710972d7da4b97766767');
Patrol::setApiSecret('92df264e875b0cad2f70906341017efa29eb1c5f35c59e3e380d943c6798f45b');

/*
 * Buckets
 *
 * A server bucket is used to send separate software to PatrolServer.
 * Note: This feature is in ALPHA and might not be available to your account yet.
 */
$servers = Patrol::servers();
$server = count($servers) ? $servers[0] : false;

if (!$server) {
    die("Add a server on the dashboard first");
}

$bucket = $server->bucket("myTestBucket");

if (!$bucket->id) {
    $bucket->software = [
        [
            "n" => "php",   // name
            "v" => "4.2.3"  // version
        ]
    ];

    $bucket->save();
}

/*
 * If you want to scan the server after, use:
 * - $server->scan()
 *
 * To check the status of a server, use $server->isScanning() which will return true or false.
 */

die('<pre>' . print_r($bucket, true) . '</pre>');
