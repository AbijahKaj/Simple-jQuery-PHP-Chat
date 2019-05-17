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

class DB {

    private $db;

    public function __construct($db = NULL) {
        try {
            $this->db = new PDO("mysql:host=localhost;dbname=thoty", "root", "");
        } catch (Exception $ex) {
            throw new Exception('DB connection error: ' . $ex->getMessage());
        }
    }
    /**
     * @return PDO
     */
    public function getDB() {
        if (is_object($this->db)) {
            return $this->db;
        }
    }


    private static function throwDbError(array $errorInfo) {
        // TODO log error, send email, etc.
        throw new Exception('DB error [' . $errorInfo[0] . ', ' . $errorInfo[1] . ']: ' . $errorInfo[2]);
    }

    /**
     * @return PDOStatement
     */
    public function query($sql) {
        $statement = $this->db->query($sql, PDO::FETCH_ASSOC);
        if ($statement === false) {
            self::throwDbError($this->db->errorInfo());
        }
        return $statement;
    }

    public function executeStatement($query) {
        if ($this->db->exec($query) === false) {
            self::throwDbError($this->db->errorInfo());
        }
    }
    public function destroyDB() {
        $this->db = NULL;
    }

}
