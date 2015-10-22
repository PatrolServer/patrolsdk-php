<?php

namespace PatrolSdk;

use PatrolSdk\Patrol as Entry;

/**
 * To use the Singleton instead, make use of the following Object:
 * use PatrolSdk\Singleton as Patrol
 *
 * Methods like the following will be accessible:
 * - Patrol::setApiKey('');
 * - Patrol::setApiSecret('');
 * - Patrol::servers();
 */

class Singleton {

    // @var Patrol
    private static $instance;

    /**
     * When calling a static variable, a Singleton of PatrolSdk\Patrol will be created just once
     * and the call will be redirected to the one matching the Singleton.
     *
     * @param string $name Function name, case sensitive!!
     * @param array $arguments Function arguments
     */
    public static function __callStatic($name, $arguments) {
        if (!self::$instance) {
            self::$instance = Entry::init();
        }

        return call_user_func_array([self::$instance, $name], $arguments);
    }

}
