<?php

// Include the PatrolServer PHP SDK
require_once "../init.php";

// Usage of the SDK objects
use PatrolSdk\Singleton as Patrol;

// Helper function to display the outcome in a readable format, you can ignore this.
function display($title, $data) {
    echo "<h1>" . $title . "</h1>";

    if (!is_null($data)) {
        echo('<pre>' . print_r($data, true) . '</pre>');
    }

    echo "\n\n";
}

/*
 * In order to communicate with the PatrolServer API, you must register a key and secret.
 */
Patrol::setApiKey('ed94f55159c0710972d7da4b97766767');
Patrol::setApiSecret('92df264e875b0cad2f70906341017efa29eb1c5f35c59e3e380d943c6798f45b');

// Get all the servers from the account linked to the key or secret above.
$servers = Patrol::servers();
display("Servers", $servers);

// Get a single server
$first_server = count($servers) ? $servers[0] : null;

// Let's not continue if no servers are added, we'll continue with this server.
if (!$first_server) {
    die("Add a server on the dashboard first");
}

// Print the first server in the list.
display("First server", $first_server);

/*
 * You can also fetch a single server from the API, provide the ID in the "server" function as first parameter.
 * Optional scopes can also be provided as second parameter. An example:
 *
 * $scopes = [
 *     'exploitable',
 *     'status'
 * ]
 * $first_server = Patrol::server($first_server->id, $scopes);
 */

/*
 * List the installed software on this particular server.
 *
 * If you want to list exploits together with the software, include "exploits" as scope variable like:
 * $software = $first_server->allSoftware();
 */
$software = $first_server->allSoftware();
display("Installed software on server " . $first_server->name, $software);

