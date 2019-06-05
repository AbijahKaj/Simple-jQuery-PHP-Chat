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

/**
 * Main application class.
 */
class Index {

    const _DEFAULT = 'init';
    const PAGE_DIR = './';

    /**
     * System config.
     */
    public function init() {
// error reporting - all errors for development (ensure you have display_errors = On in your php.ini file)
        error_reporting(E_ALL | E_STRICT);
        mb_internal_encoding('UTF-8');
        set_exception_handler([$this, 'handleException']);
        spl_autoload_register([$this, 'loadClass']);
// session
        session_start();
    }

    /**
     * Run the application!
     */
    public function run() {
        $this->runAction($this->getAction());
    }

    /**
     * Exception handler.
     */
    public function handleException($ex) {
        $extra = ['message' => $ex->getMessage()];
        if ($ex instanceof NotFoundException) {
            header('HTTP/1.0 404 Not Found');
            $this->runAction('404', $extra);
        } else {
// TODO log exception
            header('HTTP/1.1 500 Internal Server Error');
            $this->runAction('500', $extra);
        }
    }

    /**
     * Class loader.
     */
    public function loadClass($name) {
        $filename = $name . ".php";
        if (file_exists($filename)) {
            require_once $filename;
        } else {
            throw new NotFoundException("The class $name not found!");
        }
    }

    private function getAction() {
        $action = self::_DEFAULT;
        if (array_key_exists('action', $_GET)) {
            $action = htmlspecialchars($_GET['action']);
        }
        return $this->checkAction($action);
    }

    private function checkAction($action) {
        if (!preg_match('/^[a-z0-9-]+$/i', $action)) {
// TODO log attempt, redirect attacker, ...
            throw new NotFoundException('Unsafe action "' . $action . '" requested');
        }
        return $action;
    }

    private function runAction($action, array $extra = []) {
        if (Utils::isConnected()) {
            switch ($action) {
                case 'sendmsg':
                    $chat = new Chat();
                    break;
                case 'getusers':
                    $response = (new Chat())->getUsers();
                    Utils::sendResponse($response);
                    break;
                //----------------------------------------UTILITIES
                case 'logout':
                    Utils::logout();
                    break;
                case '404':
                    Utils::sendResponse($extra);
                    break;
                case '500':
                    Utils::sendResponse($extra);
                    break;
                default:
                    Utils::sendResponse(array('success' => 0,
                        'message' => 'The action "' . $action . '" cannot be executed!'));
                    break;
            }
        } else {
            switch ($action) {
                case 'signin':
                    $user = new User();
                    $response = $user->signin();
                    Utils::sendResponse($response);
                    break;
                case 'signup':
                    $user = new User();
                    $response = $user->signup();
                    Utils::sendResponse($response);
                    break;
                case '404':
                    Utils::sendResponse($extra);
                    break;
                case '500':
                    Utils::sendResponse($extra);
                    break;
                case 'get-status':
                    $response = array("connected" => Utils::isConnected());
                    Utils::sendResponse($response);
                    break;
                default:
                    Utils::sendResponse(array('success' => 0,
                        'message' => 'The action "' . $action . '" cannot be executed!'));
                    break;
            }
        }
    }

}

$index = new Index();
$index->init();
// run application!
//
$index->run();
