<?php

namespace PatrolSdk;

class HttpClient {

    // @var string The full request url containing key, secret and a GET payload if set
    private $url;

    // @var string The initial request path, used to build the $url
    private $path;

    // @var string POST/GET
    private $method;

    // @var string Payload in GET will become a query string, in POST it will be send as post values
    private $payload;

    // @var array Scope definitions
    private $scopes;

    // @var Patrol Containing the key and secret needed to perform the request
    private $patrol;

    /**
     * @param Patrol $patrol
     * @param string $method
     * @param string $path
     * @param array|null $payload
     */
    public function __construct(Patrol $patrol, $method, $path, $payload = null) {
        $this->patrol = $patrol;

        $this->method = strtolower($method);
        $this->payload = $payload;
        $this->path = $path;
    }

    /**
     * Takes a path and builds the URL with the given key and secret
     *
     * @param string $path
     */
    private function buildUrl($path) {
        $key = $this->patrol->getApiKey();
        $secret = $this->patrol->getApiSecret();

        $base = $this->patrol->apiBase;

        $url = $base . '/' . $path . '?key=' . $key . '&secret=' . $secret;

        if ($this->method === "get" && !is_null($this->payload)) {
            $query = http_build_query($this->payload);
            $url .= '&' . $query;
        }

        if (count($this->scopes)) {
            $scopes = implode(',', $this->scopes);
            if ($scopes) {
                $url .= '&scope=' . $scopes;
            }
        }

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
        $this->buildUrl($this->path);

        if ($this->method === "get") {
            return $this->get();
        }

        if ($this->method === "post") {
            return $this->post();
        }
    }

    /**
     * @return var The response from the HTTP GET request
     */
    private function get() {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $temp = curl_exec($ch);
        $error = !$temp ? curl_error($ch) : false;

        curl_close($ch);

        if (!$temp) {
            if ($error) {
                throw new Exception($error);
            }

            return null;
        }

        $decoded = @json_decode($temp, true);

        if (!$decoded) {
            return null;
        }

        return $decoded;
    }

    /**
     * @return var The response from the HTTP POST request
     */
    private function post() {
        $data = http_build_query($this->payload);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $temp = curl_exec($ch);
        $error = !$temp ? curl_error($ch) : false;

        curl_close($ch);

        if (!$temp) {
            if ($error) {
                throw new Exception($error);
            }

            return null;
        }

        $decoded = @json_decode($temp, true);

        if (!$decoded) {
            return null;
        }

        return $decoded;
    }

    /**
     * @param $scopes
     */
    public function setScopes($scopes) {
        $this->scopes = $scopes;
    }

}
