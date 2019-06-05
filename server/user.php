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

class User {

    protected $name;
    protected $username;
    protected $id;
    protected $email;
    protected $phone;
    private $db;
    private $debug = array();

    function __construct() {
        $this->db = new DB();
    }

    public function signin($response = array()) {
        if (Utils::isConnected()) {
            $response['success'] = 1;
            $response['message'] = "Already connected!";
            return $response;
        }
        if (isset($_POST['username']) AND isset($_POST['pass'])) {
            $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
            $pass = htmlspecialchars($_POST['pass'], ENT_QUOTES);
            if (preg_match('/^[a-zA-Z_]+$/i', $username)) {
                $sql = "SELECT id FROM user WHERE username='$username' AND pass='$pass'";
                $query = $this->db->query($sql);
                $nb = $query->rowCount();
                if (isset($nb) AND $nb <= 1) {
                    $this->connectUser($query->fetch()['id']);
                    $this->debug[] = 'User connected';
                    $response['success'] = 1;
                    $this->debug[] = 'User signed up succesfully';
                    array_push($response, $this->debug);
                    return $response;
                } else {
                    $this->debug[] = "UserName query error {$nb}";
                }
            } else {
                $this->debug[] = "Wrong Username";
            }
        }
        $response['success'] = 0;
        $this->debug[] = 'Verify the form and try again later!';
        array_push($response, $this->debug);
        return $response;
    }

    function signup($response = array()) {
        if (Utils::isConnected()) {
            $response['success'] = 0;
            $response['message'] = "You can't create an account and you're connected!";
            return $response;
        }
        if (isset($_POST['username']) AND
                isset($_POST['name']) AND
                isset($_POST['pass']) AND
                isset($_POST['contact'])) {
            $name = htmlspecialchars($_POST['name']);
            $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
            $pass = $_POST['pass'];
            $contact = $_POST['contact'];
            if ($this->checkName($name, $response) AND
                    $this->checkUsername($username) AND
                    $this->checkPass($pass) AND
                    $this->checkContact($contact)) {
                $lastonline = time();
                $pass = $this->db->getDB()->quote($pass);
                $query = "INSERT INTO user VALUES(NULL,'$username','$name',0,'$contact',$pass,$lastonline)";
                $this->db->executeStatement($query);
                $this->connectUser($this->db->getDB()->lastInsertId(), $response);
                $response['success'] = 1;
                $this->debug[] = 'User signed up succesfully';
                array_push($response, $this->debug);
                return $response;
            }
        }
        $response['success'] = 0;
        $this->debug[] = 'Verify the form and try again later!';
        array_push($response, $this->debug);
        return $response;
    }

    private function connectUser($id) {
        $_SESSION['user'] = array('id' => base64_encode($id), 'hash' => md5(uniqid()));
        $this->debug[] = 'User connected succesfully';
    }

    private function checkName($name) {
        if (preg_match("/^[A-Z][a-zA-Z -]+$/", $name)) {
            $this->debug[] = 'Name verified';
            return true;
        } else {
            $this->debug[] = 'Name Error';
            return false;
        }
    }

    private function checkUsername($username) {
        if (preg_match('/^[a-zA-Z_]+$/i', $username)) {
            $sql = "SELECT COUNT(*) AS nb FROM user WHERE username='$username'";
            $nb = $this->db->query($sql)->fetch();
            if (isset($nb['nb']) AND $nb['nb'] <= 0) {
                $this->debug[] = 'UserName verified';
                return true;
            } else {
                $this->debug[] = "UserName query error {$nb['nb']}";
            }
        }
        $this->debug[] = 'Username Error';
        return FALSE;
    }

    private function checkPass($pass) {
        if (preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-zA-Z]).*$/", $pass)) {
            $this->debug[] = 'Pass verified';
            return true;
        }
        $this->debug[] = 'Pass Error';
        return FALSE;
    }

    private function checkContact($contact) {
        //$reg = "/^[a-zA-Z]w+(.w+)*@w+(.[0-9a-zA-Z]+)*.[a-zA-Z]{2,4}$/";
        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            $this->debug[] = 'Contact Email verified';
            return TRUE;
        } elseif (preg_match("/^d{1}-d{3}-d{3}-d{4}$/", $contact)) {
            $this->debug[] = 'Contact Error But a Phone Number';
            return FALSE;
            //curently testing with emails only!!!
        } else {
            $this->debug[] = 'Contact Error';
            return FALSE;
        }
    }

}
