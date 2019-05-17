<?php

/*
 * Copyright 2019 Abijah Kajabika.
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

class Chat {
    /**
     *
     * @var string Message
     */
    protected $message;
    /**
     * @uses $chat->verify()
     */
    public function __construct() {
        if (isset($_POST['username']) && isset($_POST['msg'])) {
            $msg = htmlentities($_POST['msg']);
            $date = date('D, d M Y H:i:s');
            Utils::sendResponse(array('success' => 1, 'msg' => $msg, 'date' => $date));
        } else {
            $msg = "An error occured";
            Utils::sendResponse(array('success' => 0, 'msg' => $msg));
        }
    }
    /**
     * Function to save the message by using users ID
     * @param int $from
     * @param int $to
     * @param string $message
     */
    protected function saveMessage($from,$to,$message) {
        
    }
}
