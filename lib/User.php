<?php

namespace PatrolSdk;

class User extends PatrolModel {

    /**
     * Gets the current user
     *
     * @return User
     */
    public function get() {
        return $this->_get('user');
    }

    /**
     * Deletes the user
     */
    public function delete() {
        return $this->_post('user/delete');
    }

    /**
     * Updates the user
     */
    public function update() {
        return $this->_post('user/update', $this->dirty_values);
    }

}
