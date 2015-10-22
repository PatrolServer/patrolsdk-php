<?php

// Include the PatrolServer PHP SDK
require_once "../init.php";

// Usage of the SDK objects
use PatrolSdk\Patrol;

/*
 * Create the first Patrol instance
 */
$patrol1 = Patrol::init();
$patrol1->setApiKey('key_1');
$patrol1->setApiSecret('secret_1');

/*
 * Create a second Patrol instance, with a different key and secret combination
 */
$patrol2 = Patrol::init();
$patrol2->setApiKey('another_key_2');
$patrol2->setApiSecret('another_secret_2');

