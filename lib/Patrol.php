<?php

namespace PatrolSdk;

class Patrol {

    const VERSION = '1.0.1';

    // @var string The PatrolServer API key used for requests
    public $apiKey;

    // @var string The API secret used to sign requests
    public $apiSecret;

    // @var string The base URL for the PatrolServer API endpoint
    public $apiBase = "https://api.patrolserver.com";

    // @var bool Indicate if we should enable logging
    public $enableLog = false;

    /**
     * Empty constructor
     */
    public function __construct() {

    }

    /**
     * Static init
     *
     * @param string $key optional
     * @param string $secret optional
     *
     * @return Patrol Can be either an empty Patrol object or key/secret combined based on parameters
     */
    public static function init($key = null, $secret = null) {
        $patrol = new Patrol;

        if ($key && $secret) {
            $patrol->setApiKey($key);
            $patrol->setApiSecret($secret);
        }

        return $patrol;
    }

    /**
     * @return string The API key used for requests
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * Sets the API key used for requests
     *
     * @param string $apiKey
     */
    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string The API secret used to sign requests
     */
    public function getApiSecret() {
        return $this->apiSecret;
    }

    /**
     * @param string $apiSecret
     */
    public function setApiSecret($apiSecret) {
        $this->apiSecret = $apiSecret;
    }

    /**
     * The standard value of the $apiBase is "https://api.patrolserver.com",
     * this is mainly here for debug purposes
     *
     * @param string $baseUrl
     */
    public function setApiBaseUrl($baseUrl) {
        $this->apiBase = $baseUrl;
    }

    /**
     * Enable logging for Webhooks
     */
    public function enableLog() {
        $this->enableLog = true;
    }

    /**
     * Disable logging for Webhooks
     */
    public function disableLog() {
        $this->enableLog = false;
    }

    /**
     * The functions below describe the SDK functionality
     * -----
     */

    /**
     * Gets a list of all the servers
     *
     * @param array $scopes optional
     *
     * @return array of PatrolSdk\Server
     */
    public function servers($scopes = []) {
        $server = new Server($this, $scopes);
        return $server->all();
    }

    /**
     * Gets a single server
     * If $id is empty, create a repository that we can use to save a new server
     *
     * @param integer $id optional
     * @param array $scopes optional
     *
     * @return Server
     */
    public function server($id = null, $scopes = []) {
        $server = new Server($this);

        if (is_null($id)) {
            return $server;
        }

        return $server->find($id, $scopes);
    }

    /**
     * Gets the current user
     *
     * @return User
     */
    public function user() {
        $user = new User($this);
        return $user->get();
    }

    /**
     * Gets a new webhook listener
     *
     * @return Webhook
     */
    public function webhook($identifier, $callback) {
        return new Webhook($this, $identifier, $callback);
    }

    /**
     * Log wrapper
     */
    public function log($data) {
        Log::info($this, $data);
    }

}
