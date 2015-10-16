<?php

namespace PatrolSdk;

class HttpClient {

    private $url;
    private $method;
    private $payload;

    /**
     * @param string $method
     * @param string $path
     * @param array|null $payload
     */
    public function __construct($method, $path, $payload = null) {
        $this->method = strtolower($method);
        $this->payload = $payload;

        $this->buildUrl($path);
    }

    /**
     * Takes a path and builds the URL with the given key and secret
     *
     * @param string $path
     */
    private function buildUrl($path) {
        $key = Patrol::getApiKey();
        $secret = Patrol::getApiSecret();

        $base = Patrol::$apiBase;

        $url = $base . '/' . $path . '?key=' . $key . '&secret=' . $secret;

        $this->url = $url;
    }

    /**
     * @return string The complete URL built from the $path
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @return array The response from the HTTP GET/POST request, based on the $method
     */
    public function response() {
        if ($this->method === "get") {
            return $this->get();
        }

        if ($this->method === "post") {
            return $this->post();
        }
    }

    /**
     * @return array The response from the HTTP GET request
     */
    private function get() {
        $temp = @file_get_contents($this->url);

        if (!$temp) {
            return null;
        }

        $decoded = @json_decode($temp, true);

        if (!$decoded) {
            return null;
        }

        return $decoded;
    }

    /**
     * @return null Currently not implemented
     */
    private function post() {
        # TODO: Implement this in a later stadium, for now we fully support Webhooks
    }

}
