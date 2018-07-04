<?php

require("connection.php");

function db_connect() {
    global $conn_host, $conn_user, $conn_pw, $conn_db;
    $conn = new mysqli($conn_host, $conn_user, $conn_pw, $conn_db);
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);
    return $conn;
}

function db_select($conn, $query) {
    //echo("<p>$query</p>");
    $result = $conn->query($query);
    $objects = array();
    if ($result->num_rows > 0) {
        while($object = $result->fetch_object())
            $objects[] = $object;
        $result->free(); }
    return $objects;
}

?>
