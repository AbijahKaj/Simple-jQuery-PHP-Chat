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
    private $db;
    private $debug = array();

    /**
     * @uses $chat->verify()
     */
    public function __construct() {
        $this->db = new DB();
    }

    /**
     * Function to send the message by using users ID
     * @param int $from
     * @param int $to
     * @param string $message
     */
    protected function sendMessage($from, $to, $message) {
        if (isset($_POST['from']) && is_int($_POST['from']) && isset($_POST['to']) && is_int($_POST['to']) && isset($_POST['msg'])) {
            $from = $_POST['from'];
            $to = $_POST['to'];
            $message = htmlspecialchars($_POST['msg'], ENT_QUOTES);
            if ($this->userExists($from) && $this->userExists($to)) {
                $timesent = time();
                $message = $this->db->getDB()->quote($message);
                $query = "INSERT INTO chat VALUES(NULL,'$from','$to',$message,$timesent,0)";
                $this->db->executeStatement($query);
                $response['success'] = 1;
                $this->debug[] = 'Message sent succesfully';
                array_push($response, $this->debug);
                return $response;
            } else {
                $this->debug[] = "Users $from and $to don't exist";
            }
        }
        $response['success'] = 0;
        $this->debug[] = 'Verify the form and try again later!';
        array_push($response, $this->debug);
        return $response;
    }

    public function getUsers($query = "") {
        if(isset($_POST['query'])){
            $query = $_POST['query'];
        }
        $query = htmlspecialchars($query);
        if (strlen($query) <= 50) {
            $where = ($query !== "") ? "WHERE username LIKE %%$query%% OR name LIKE %%$query%%" : "";
            $sql = "SELECT id,username,name FROM user $where LIMIT 10";
            $users = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            $response['success'] = 1;
            $this->debug[] = 'User query executed';
            array_push($response, $this->debug);
            $response['users'] = $users;
            return $response;
        }
        $response['success'] = 0;
        $this->debug[] = "Query too long $query";
        array_push($response, $this->debug);
        return $response;
    }

    public function userExists($id) {
        $sql = "SELECT COUNT(*) AS nb FROM user WHERE id='$id'";
        $nb = $this->db->query($sql)->fetch();
        if (isset($nb['nb']) AND $nb['nb'] <= 0) {
            $this->debug[] = "ID $id nonexistant";
            return FALSE;
        } else {
            return TRUE;
            $this->debug[] = "ID $id exists {$nb['nb']}";
        }
    }

}
