<?php

namespace PatrolSdk;

class Bucket extends PatrolModel {

    // @var integer A Bucket object is always a child of a Server object
    protected $server_id;

    /**
     * Set the server id manually
     *
     * @param integer $server_id
     */
    public function setServerId($server_id) {
        $this->server_id = $server_id;
    }

    /**
     * Gets all buckets from a server
     *
     * @return array List of PatrolSdk\Bucket
     */
    public function all() {
        return $this->_get('servers/' . $this->server_id . '/software_bucket');
    }

    /**
     * Gets a single bucket from a server
     *
     * @param string $key
     *
     * @return Bucket
     */
    public function find($key) {
        return $this->_get('servers/' . $this->server_id . '/software_bucket/' . $key);
    }

    /**
     * Saves a bucket, if the bucket does not exist, a new bucket will be created
     */
    public function save() {
        $bucket = $this->_post('servers/' . $this->server_id . '/software_bucket/' . $this->key, $this->dirty_values);
        $this->mergeValues($bucket);
    }

    /**
     * Deletes the current bucket
     *
     * @return integer Is deleted
     */
    public function delete() {
        return $this->_post('servers/' . $this->server_id . '/software_bucket/' . $this->key . '/delete');
    }

}
