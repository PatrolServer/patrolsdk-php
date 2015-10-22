<?php

namespace PatrolSdk;

class Server extends PatrolModel {

    /**
     * Gets a list of all servers
     *
     * @param array $scopes optional
     *
     * @return array List of PatrolSdk\Server
     */
    public function all($scopes = []) {
        return $this->_get('servers', null, $scopes);
    }

    /**
     * Gets a single server
     *
     * @param integer $id
     * @param array $scopes optional
     *
     * @return Server
     */
    public function find($id, $scopes = []) {
        return $this->_get('servers/' . $id, null, $scopes);
    }

    public function scan() {
        return $this->_post('servers/' . $this->id . '/scan');
    }

    public function isScanning() {
        return $this->_get('servers/' . $this->id . '/isScanning');
    }

    /**
     * Saves a server
     */
    public function save() {
        if ($this->id) {
            throw new Exception('Can\'t save this server because it is already stored');
        }

        $server = $this->_post('servers', $this->dirty_values);
        $this->mergeValues($server);
    }

    /**
     * Requests a verification token for this server
     */
    public function requestVerificationToken() {
        if (!isset($this->values['name'])) {
            throw new Exception('Your server object does not contain a domain');
        }

        return $this->_get('request_verification_token', ['domain' => $this->values['name']]);
    }

    /**
     * Verifies the server with the verification token
     */
    public function verify($token = null) {
        if (!$token) {
            $data = $this->requestVerificationToken();
            $token = $data['token'];
        }

        return $this->_post('servers/' . $this->id . '/verify', ['token' => $token]);
    }

    /**
     * Delete the server
     */
    public function delete() {
        return $this->_post('servers/' . $this->id . '/delete');
    }

    /**
     * The following methods are relation specific
     * -----
     */

    /**
     * Gets all installed software from this server
     *
     * @param array $scopes optional
     *
     * @return array List of PatrolSdk\Software
     */
    public function allSoftware($scopes = []) {
        if (!$this->id) {
            throw new Exception("The server has no ID, can\'t get software");
        }

        $software = new Software($this->patrol);

        $software->defaults([
            'server_id' => $this->id
        ]);

        return $software->all($scopes);
    }

    /**
     * Gets a single installed software from this server
     *
     * @param integer $id
     * @param array $scopes optional
     *
     * @return Software
     */
    public function software($id, $scopes = []) {
        if (!$this->id) {
            throw new Exception("The server has no ID, can\'t get software");
        }

        $software = new Software($this->patrol);

        $software->defaults([
            'server_id' => $this->id
        ]);

        return $software->find($id, $scopes);
    }

    /**
     * Gets all software buckets from this server
     *
     * @return array List of PatrolSdk\Bucket
     */
    public function buckets() {
        if (!$this->id) {
            throw new Exception("The server has no ID, can\'t get buckets");
        }

        $bucket = new Bucket($this->patrol);

        $bucket->defaults([
            'server_id' => $this->id
        ]);

        return $bucket->all();
    }

    /**
     * Gets a single software bucket
     * If no key is set, an empty Bucket object will be returned which will act as a repository
     *
     * @param string $key optional
     *
     * @return Bucket
     */
    public function bucket($key = null) {
        if (!$this->id) {
            throw new Exception("The server has no ID, can\'t get buckets");
        }

        $bucket = new Bucket($this->patrol);

        $bucket->defaults([
            'server_id' => $this->id
        ]);

        if (is_null($key)) {
            return $bucket;
        }

        try {
            return $bucket->find($key);
        } catch (Exception $ex) {
            if ($ex->getMessage() === "Bucket does not exist") {
                $bucket->key = $key;
                return $bucket;
            }
        }

        return null;
    }

}
