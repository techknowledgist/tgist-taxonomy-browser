<?php

// Configure variables needed for database connection

define('LOCAL', 1);
define('BATCAVE', 2);

$corpus = $_GET['corpus'];

if ($_SERVER['SERVER_NAME'] == 'batcaves.org' or
    $_SERVER['SERVER_NAME'] == 'www.batcaves.org') {
    $PLATFORM = BATCAVE; }
else {
    $PLATFORM = LOCAL; }

switch ($PLATFORM) {
case LOCAL: config_local(); break;
case BATCAVE: config_batcave(); break;
default:
   echo "<p>ERROR: no connection platform (connection.php)</p>"; }

function config_local() {
    global $corpus;
    config("127.0.0.1:8889", 'root', 'root', 'tgist' . $corpus); }

function config_batcave() {
    global $corpus;
    $pw = trim(file_get_contents("password.txt"));
    config("localhost", 'batcave1_tgist', $pw, 'batcave1_tgist_' . $corpus); }

function config($host, $user, $password, $database) {
    global $conn_hostname, $conn_username, $conn_password, $conn_database;
    $conn_hostname = $host;
    $conn_username = $user;
    $conn_password = $password;
    $conn_database = $database; }

function show_config() {
    global $conn_hostname, $conn_username, $conn_password, $conn_database;
    dbg("DATABASE: $conn_hostname > $conn_username > $conn_database"); }

?>
