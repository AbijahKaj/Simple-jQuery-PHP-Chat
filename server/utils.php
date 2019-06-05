<?php

/*
 * Copyright 2019 Abijah.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class Utils {

    /**
     * Utility to send JSON code to clients
     * @param array|string $response
     * @return null Echo JSON code to the client
     */
    public static function sendResponse($response) {
        if (is_array($response)) {
            $response['hash'] = isset($_SESSION['user']['hash']) ? $_SESSION['user']['hash'] : NULL;
            echo json_encode($response);
        }
        header("Content-type:application/json");
    }

    /**
     * Little fuction to check if a user is connected
     * @return bool True or False is a user is connected
     */
    public static function isConnected() {
        return isset($_SESSION['user']);
    }

    public static function logout() {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            $response = array('success' => 1, 'message' => "Logged out successfuly");
            self::sendResponse($response);
        }
    }

}
