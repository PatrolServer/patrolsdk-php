<?php

namespace PatrolSdk;

class Patrol {

    const VERSION = '1.0.0';

    // @var string The PatrolServer API key used for requests
    public static $apiKey;

    // @var string The API secret used to sign requests
    public static $apiSecret;

    // @var string The base URL for the PatrolServer API endpoint
    public static $apiBase = "https://api.patrolserver.com";

    // @var bool Indicate if we should enable logging
    public static $enableLog = false;

    /**
     * @return string The API key used for requests
     */
    public static function getApiKey() {
        return self::$apiKey;
    }

    /**
     * Sets the API key used for requests
     *
     * @param string $apiKey
     */
    public static function setApiKey($apiKey) {
        self::$apiKey = $apiKey;
    }

    /**
     * @return string The API secret used to sign requests
     */
    public static function getApiSecret() {
        return self::$apiSecret;
    }

    /**
     * @param string $apiSecret
     */
    public static function setApiSecret($apiSecret) {
        self::$apiSecret = $apiSecret;
    }

}
