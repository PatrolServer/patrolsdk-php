<?php

namespace PatrolSdk;

class Util {

    /**
     * Check if param is an array and can be iterated on,
     * indicating this is a collection
     *
     * @param object $arr
     *
     * @return boolean
     */
    public static function isIterator($arr) {
        if (!is_array($arr)) {
            return false;
        }

        foreach (array_keys($arr) as $key) {
            if (!is_numeric($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if param can be handled as an object
     *
     * @param object $arr
     *
     * @return boolean
     */
    public static function isObject($arr) {
        return is_array($arr);
    }

    /**
     * Parses a response to PatrolModel
     *
     * @param Patrol $patrol
     * @param object $data
     * @param string $class
     * @param array $defaults
     *
     * @return object Can be an array (if response is a collection), a PatrolModel object or the default data (if primitive data type)
     */
    public static function parseResponseToPatrolObject(Patrol $patrol, $data, $class = null, $defaults = null) {
        if (self::isIterator($data)) {
            $parsed = [];
            foreach ($data as $item) {
                $parsed[] = self::parseResponseToPatrolObject($patrol, $item, $class, $defaults);
            }
            return $parsed;
        } else if (self::isObject($data)) {
            $callable_class = is_null($class) ? 'PatrolSdk\PatrolObject' : $class;
            return new $callable_class($patrol, $data, $defaults);
        } else {
            return $data;
        }
    }

}
