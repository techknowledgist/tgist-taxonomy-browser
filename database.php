<?php

require("connection.php");
require_once("debugging.php");

$db_debugging = true;
$db_debugging = false;

# Hidden type inside of TopType, will not be displayed
$SPOOKY_TYPE = 'Spooky';


// DATABASE CONNECTION

// TODO: the following functions are all very basic and repeated for many sites
// and should perhaps be in their own module.

function db_connect()
{
    // Connect to host and select database

    global $conn_hostname;
    global $conn_username;
    global $conn_password;
    global $conn_database;
    global $db_conn;
    global $db_debugging;
    $db_conn = mysql_connect($conn_hostname, $conn_username, $conn_password);
    if (mysql_errno()) {
        echo "<hr><h2>Connection Failure</h2>";
        echo "<b>There is a problem with the database connection, try again later.</font></b>";
        echo "<p>Please send an email to marc@cs.brandeis.edu if the problem persists.<hr>";
        if ($db_debugging)
            echo "mysql_connect($conn_hostname, $conn_username, $conn_password)";
        exit;
        echo mysql_errno() . ": " . mysql_error(). "\n<p><hr><p>\n\n";
    }
    mysql_select_db($conn_database, $db_conn);
    if (mysql_errno()) {
        echo mysql_errno() . ": " . mysql_error(). "\n<p><hr><p>\n\n";
    }
  
    //$db_conn = mysqli_connect($conn_hostname, $conn_username, $conn_password, $conn_database);
    //if (!$link) {
    //  echo "Error: Unable to connect to MySQL." . PHP_EOL;
    //  echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    //  echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    //  exit;
    //}
}


function db_close()
{
    mysql_close();
}


// BASIC DATABASE FUNCTIONS

function db_select($query, $collapse=false)
{
    $result = db_query($query);
    if ($result) {
        $rows = array();
        while ($row = mysql_fetch_row($result))
            $rows[] = $collapse ? $row[0] : $row;
        return $rows;
    } else {
        return $result;
    }
}


function db_select_objects($query, $caller=false)
{
    $result = db_query($query, $caller);
    if ($result) {
        $rows = array();
        while ($row = mysql_fetch_object($result)) {
            $rows[] = $row; 
        }
        //echo "<p>$caller: $query</p>";
        return $rows;
    } else {
        return $result;
    }
}

// updates, inserts and deletes simply forward to db_query()
function db_update($query, $caller=false) { return db_query($query, $caller, $log=true); }
function db_insert($query, $caller=false) { return db_query($query, $caller, $log=true); }
function db_delete($query, $caller=false) { return db_query($query, $caller, $log=true); }



function db_query($query, $caller=NULL, $log=false)
{
    // Function that wraps some error reporting around a mysql_query call. It is intended to
    // be called from one of the methods above, but will work fine if called directly

    global $db_conn, $db_debugging;

    if ($log) dbg_log($query);

    // set to true to make the debugging trace a bit more informative (and less compact)
    $print_caller = false;
    //$print_caller = true;

    $trace_stack = array();
    foreach (debug_backtrace() as $element) {
        $file = basename($element['file']);
        $class = array_key_exists('class',$element) ? $element['class'] : 'None';
        $function = $element['function'];
        $line = $element['line'];
        array_unshift($trace_stack, "  $file:$class:$function:$line"); }
    $trace_stack = implode("\n", $trace_stack);
  
    if ($db_debugging) {
        $print_caller ? dbg("$query\n$trace_stack") : dbg("$query"); }
  
    $result = mysql_query($query, $db_conn);
    if (mysql_errno() and $db_debugging) {
        echo '<p><hr><p>MySQL Error:' . mysql_errno() . ": " . mysql_error(). "\n";
        echo "<p>Query: $query<p>Caller: $caller<p><hr><p>\n\n";
    }

    return $result;
}



?>
