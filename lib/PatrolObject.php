<?php

namespace PatrolSdk;

class PatrolObject {

    // @var integer Object ID
    protected $id;

    // @var array Store for __set and __get
    protected $values;

    // @var array Default values to be copied to $values
    protected $defaults;

    // @var array Values changed by the user
    protected $dirty_values;

    // @var Patrol The linked PatrolSdk\Patrol object
    protected $patrol;

    /**
     * Constructor
     *
     * @param Patrol $patrol
     * @param integer $id optional
     */
    public function __construct(Patrol $patrol, $id = null) {
        $this->values = [];
        $this->dirty_values = [];

        $this->patrol = $patrol;

        if (is_array($id)) {
            foreach ($id as $key => $value) {
                if ($key !== 'id') {
                    if (method_exists($this, '__valueSet')) {
                        $value = $this->__valueSet($key, $value);
                    }

                    $this->values[$key] = $value;
                }
            }

            $id = isset($id['id']) ? $id['id'] : null;
        }

        if (!is_null($id)) {
            $this->id = $id;
        }
    }

    /**
     * Accessor magic methods
     */
    public function __isset($k) {
        return isset($this->values[$k]);
    }

    public function __set($k, $v) {
        // Push values set manually as dirty, those are the ones that need to be updated / synced
        $this->dirty_values[$k] = $v;

        $this->values[$k] = $v;
    }

    public function &__get($k) {
        if ($k === "id") {
            return $this->id;
        }

        $nullref = null;

        if (array_key_exists($k, $this->values)) {
            return $this->values[$k];
        } else {
            $class = get_class($this);
            error_log("PatrolServer Error: Undefined property of $class instance: $k");
            return $nullref;
        }
    }

    public function __toArray() {
        return $this->values;
    }

    public function __toJSON() {
        return json_encode($this->__toArray());
    }

    public function __toString() {
        $class = get_class($this);
        return $class . ' JSON: ' . $this->__toJSON();
    }

    /**
     * @return array Keys of $values
     */
    public function keys() {
        return array_keys($this->values);
    }

    /**
     * Sets the default values for this PatrolObject
     *
     * @param array $defaults
     */
    public function defaults($defaults) {
        $this->defaults = $defaults;

        foreach ($defaults as $k => $v) {
            if (property_exists($this, $k)) {
                $this->{$k} = $v;
            }
        }
    }

    /**
     * Merges values from another PatrolObject
     *
     * @param PatrolObject
     */
    public function mergeValues($object) {
        $values = $object->__toArray();
        foreach ($values as $k => $v) {
            $this->values[$k] = $v;
        }
        if ($object->id) $this->id = $object->id;

        $this->dirty_values = [];
    }

}
