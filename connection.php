<?php

define('MACAIR', 1);
define('BATCAVE', 2);

$corpus = $_GET['corpus'];

if ($_SERVER['SERVER_NAME'] == 'batcaves.org' or
    $_SERVER['SERVER_NAME'] == 'www.batcaves.org') {
    $PLATFORM = BATCAVE; }
else {
    $PLATFORM = MACAIR; }

switch ($PLATFORM) {  
case MACAIR: connect_macair(); break;
case BATCAVE: connect_batcave(); break;
default:
   echo "<p>ERROR: no connection platform (connection.php)</p>"; }

function connect_macair() {
    connect("127.0.0.1", 'root', "wortel", 'bso'); }

function connect_batcave() {
    global $corpus;
    $pw = trim(file_get_contents("password.txt"));
    connect("localhost", 'batcave1_tgist', $pw, 'batcave1_tgist_' . $corpus); }

function connect($host, $user, $password, $database) {
    global $conn_hostname, $conn_username, $conn_password, $conn_database;
    $conn_hostname = $host;
    $conn_username = $user; 
    $conn_password = $password;
    $conn_database = $database; }

function show_connection() {
    global $conn_hostname, $conn_username, $conn_password, $conn_database;
    dbg("DATABASE: $conn_hostname > $conn_username > $conn_database"); }

?>
