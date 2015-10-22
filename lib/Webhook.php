<?php

namespace PatrolSdk;

class Webhook {

    // @var string The webhook identifier
    private $identifier;

    // @var function A callable function that will be executed when a webhook fetches the Event object
    private $callback;

    // @var string General webhook identifier
    private $webhook_identifier = "webhook_patrolserver";

    private $patrol;

    /**
     * @param string $identifier
     * @param callable $callback
     */
    public function __construct(Patrol $patrol, $identifier, callable $callback) {
        if (substr($identifier, 0, 8) !== "webhook.") {
            $identifier = "webhook." . $identifier;
        }

        $this->identifier = $identifier;
        $this->callback = $callback;
        $this->patrol = $patrol;

        // Only execute the event when a valid PatrolServer request
        if ($this->isRequestAWebhook()) {
            $this->handleWebhook();
        }
    }

    /**
     * @param array $array Optional array to get the value from
     * @param string $prop The property as string
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
            Log::info($this->patrol, 'Webhook ' . $this->identifier . ' has no event_id or webhook_id');
            return;
        }

        $httpClient = new HttpClient($this->patrol, 'GET', 'webhooks/' . $webhook_id . '/events/' . $event_id);
        $response = $httpClient->response();

        if (!$response) {
            Log::info($this->patrol, 'Retrieving webhook from ' . $httpClient->getUrl() . ' failed');
            return;
        }

        $data = $this->field($response, 'data');

        if (!$data) {
            Log::info($this->patrol, 'Event has invalid format to be processed: ' . print_r($response, true));
        }

        $callable = $this->callback;

        if (is_callable($callable)) {
            $callable($data);
        }
    }

}
