<?php

namespace PatrolSdk;

class Software extends PatrolModel {

    // @var integer A Software object is always a child of a Server object
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
     * Gets a list of software available on the server
     *
     * @param array $scopes optional
     *
     * @return array List of PatrolSdk\Software
     */
    public function all($scopes = []) {
        return $this->_get('servers/' . $this->server_id . '/software', null, $scopes);
    }

    /**
     * Gets a single software object on the server
     *
     * @param integer $id
     * @param array $scopes optional
     *
     * @return Software
     */
    public function find($id, $scopes = []) {
        return $this->_get('servers/' . $this->server_id . '/software/' . $id, null, $scopes);
    }

    /**
     * Gets a list of exploits from this software
     *
     * @param array $scopes optional
     *
     * @return array List of PatrolSdk\Exploit
     */
    public function exploits($scopes = []) {
        if (!$this->id) {
            throw new Exception("The software has no ID, can\'t get exploits");
        }

        if (!$this->server_id) {
            throw new Exception("The software has no server ID, can\'t get exploits");
        }

        $exploit = new Exploit($this->patrol);

        $exploit->defaults([
            'software_id' => $this->id,
            'server_id' => $this->server_id
        ]);

        return $exploit->all();
    }

    /**
     * This is a casting hook, when a new value is set in PatrolModel, this hook will be called,
     * in this case, when "exploits" are set in the model, cast them to a PatrolSdk\Exploit object
     *
     * @param string $key
     * @param object $value
     *
     * @return object $value Can be casted
     */
    protected function __valueSet($key, $value) {
        if ($key === "exploits") {
            $casted = [];
            foreach ($value as $exploit) {
                $casted[] = new Exploit($this->patrol, $exploit);
            }
            return $casted;
        }

        return $value;
    }

}
