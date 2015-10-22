<?php

namespace PatrolSdk;

class PatrolModel extends PatrolObject {

    /**
     * Constructor
     *
     * @param Patrol $patrol
     * @param integer $id optional
     * @param array $defaults optional
     */
    public function __construct(Patrol $patrol, $id = null, $defaults = null) {
        parent::__construct($patrol, $id);

        if (is_array($defaults)) {
            $this->defaults($defaults);
        }
    }

    /**
     * Perform a GET request based on this model
     *
     * @param string $url
     * @param array $parameters
     * @param array $scopes
     * @param string $class The class type where the result should be casted to
     *
     * @return PatrolSdk\Model
     */
    protected function _get($url, $parameters = null, $scopes = [], $class = null) {
        $client = new HttpClient($this->patrol, 'get', $url, $parameters);

        $client->setScopes($scopes);

        $data = $client->response();

        if (isset($data['error'])) {
            $this->_error($data);
        }

        $data = $data['data'];

        $callee = $class ? $class : get_called_class();
        return Util::parseResponseToPatrolObject($this->patrol, $data, $callee, $this->defaults);
    }

    /**
     * Perform a POST request based on this model
     *
     * @param string $url
     * @param array $data
     * @param string $class The class type where the result should be casted to
     *
     * @return PatrolSdk\Model
     */
    protected function _post($url, $data = [], $class = null) {
        $client = new HttpClient($this->patrol, 'post', $url, $data);

        $data = $client->response();

        if (isset($data['error'])) {
            $this->_error($data);
        }

        if (!isset($data['data'])) {
            return $data;
        }

        $data = $data['data'];

        $callee = $class ? $class : get_called_class();
        return Util::parseResponseToPatrolObject($this->patrol, $data, $callee, $this->defaults);
    }

    /**
     * Triggers an exception if "error" is present in an API call result
     *
     * @param array $response API response
     */
    private function _error($response) {
        $error = (isset($response["error"]["message"])) ?
            $response["error"]["message"] :
            "Unknown error occured.";

        throw new Exception($error);
    }

}
