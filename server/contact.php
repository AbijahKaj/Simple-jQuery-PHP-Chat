<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (isset($_POST['email'])) {
    $connection = mysql_connect("localhost", "root", ""); // Establishing Connection with Server..
    $db = mysql_select_db("thoty", $connection); // Selecting Database
//Fetching Values from URL
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
//Insert query
    $query = mysql_query("insert into form_element(name, email, message) values ('$name', '$email', '$message')");
    mysql_close($connection); // Connection Closed
    echo json_encode(array('msg' => "Form Submitted Succesfully"));
} else {
    header("Location: ../");
}
?>