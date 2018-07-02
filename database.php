<?php

require("connection.php");
require_once("debugging.php");


// DATABASE CONNECTION

function db_connect() {
    global $conn_hostname, $conn_username, $conn_password, $conn_database;
    $conn = new mysqli(
      $conn_hostname, $conn_username, $conn_password, $conn_database);
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);
    return $conn;
}

function db_select($conn, $query) {
    //debug($query);
    $result = $conn->query($query);
    $objects = array();
    if ($result->num_rows > 0) {
        while($object = $result->fetch_object())
            $objects[] = $object;
        $result->free(); }
    return $objects;
}

?>
