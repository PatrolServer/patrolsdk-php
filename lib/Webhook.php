<?php

namespace PatrolSdk;

class Webhook {

    /**
     * Provides an entry to bind webhook callbacks
     *
     * @param string $identifier
     * @param function $callback
     */
    public static function listen($identifier, $callback) {
        new Webhook($identifier, $callback);
    }

    // @var string The webhook identifier
    private $identifier;

    // @var function A callable function that will be executed when a webhook fetches the Event object
    private $callback;

    // @var string General webhook identifier
    private $webhook_identifier = "webhook_patrolserver";

    /**
     * @param string $identifier
     * @param callable $callback
     */
    public function __construct($identifier, callable $callback) {
        $this->identifier = $identifier;
        $this->callback = $callback;

        // Only execute the event when a valid PatrolServer request
        if ($this->isRequestAWebhook()) {
            $this->handleWebhook();
        }
    }

    /**
     * @param $array Optional array to get the value from
     * @param $prop The property as string
     *
     * @return Object A value matching the property in the array
     */
    private function field($array, $prop = null) {
        if (is_string($array)) {
            $prop = $array;
            $array = $_POST;
        }

        if (!isset($array[$prop])) {
            return null;
        }

        return $array[$prop];
    }

    /**
     * @return bool Is request a webhook or not
     */
    private function isRequestAWebhook() {
        return !is_null($this->field('webhook_patrolserver'));
    }

    /**
     * Handles the actual webhook, happens in two steps:
     *
     * - Retrieves the $webhook_id and $event_id
     *
     * - Uses the key and secret provided to fetch the event object and casts it to an array
     *
     * - Calls the callable function with the Event object
     */
    private function handleWebhook() {
        $identifier = $this->field('identifier');

        if ($identifier !== $this->identifier) {
            return;
        }

        $event_id = $this->field('event_id');
        $webhook_id = $this->field('webhook_id');

        if (!$event_id || !$webhook_id) {
            Log::info('Webhook ' . $this->identifier . ' has no event_id or webhook_id');
            return;
        }

        $httpClient = new HttpClient('GET', 'webhooks/' . $webhook_id . '/events/' . $event_id);
        $response = $httpClient->response();

        if (!$response) {
            Log::info('Retrieving webhook from ' . $httpClient->getUrl() . ' failed');
            return;
        }

        $data = $this->field($response, 'data');

        if (!$data) {
            Log::info('Event has invalid format to be processed: ' . print_r($response, true));
        }

        $callable = $this->callback;

        if (is_callable($callable)) {
            $callable($data);
        }
    }

}
