<?php

// DEBUGGING UTILITY METHODS


$LOGFILE = NULL;


function dbg($object)
{
  global $DEBUG;
  if (! $DEBUG) { return; }
  if (is_object($object) or is_array($object)) {
    echo "<pre>\n";
    print_r($object);
    echo "</pre>\n"; 
  } else {
    echo "<pre>$object</pre>\n";		
  }
}

function dbg_open_log()
{
  global $_SESSION, $LOGFILE;
  $cid = $_SESSION['corpus-id'];
  $cname = $_SESSION['corpus-name'];
  $aname = $_SESSION['annotator-name'];
  $logdir = "../logs/$cid-$cname";
  $logfile = "$logdir/$aname.txt";
  if (! file_exists($logdir)) 
    mkdir($logdir, 0777);
  //echo "<pre>OPENING $logfile</pre>";
  $LOGFILE = fopen($logfile, 'a');
}

function dbg_close_log()
{
  global $LOGFILE;
  fclose($LOGFILE);
  $LOGFILE = NULL;
}

function dbg_log($query)
{
  // TODO: should check whether we can indeed use the session variables here, the submit
  // code for the annotator uses the POST variables in order to avoid problems when
  // annotators have more than one window open; my hunch is that using SESSION variables
  // here will write the correct queries but may give them the wrong headers.
  
  global $_SESSION, $LOGFILE;
  if (! is_null($LOGFILE)) {
    $cid = $_SESSION['corpus-id'];
    $cname = $_SESSION['corpus-name'];
    $lid = $_SESSION['layer-id'];
    $fid = $_SESSION['file-id'];
    $aid = $_SESSION['annotator-id'];
    $lname = $_SESSION['layer-name'];
    $fname = $_SESSION['file-name'];
    $aname = $_SESSION['annotator-name'];
    $timestamp = date("Ymd-His", time());
    //echo "<pre>$timestamp</pre>";
    fwrite($LOGFILE, "$timestamp $cid $cname $lid $lname $fid $fname $aid $aname\n$query\n\n"); }
}

function dbg_warn($message, $force_warning=False)
{
  global $DEBUG;
  if ($DEBUG or $force_warning) {
    echo "<pre><font color=red>WARNING: $message</font></pre>\n"; }
}

function dbg_memory_usage()
{
  if ( function_exists('memory_get_usage') ) {
    $mem = round(memory_get_usage() / (1024*1024), 1)." Mb";
    dbg("Memory = $mem <br>\n"); }
}

function dbg_vars()
{
  global $_GET, $_POST, $_SESSION, $_FILES, $DEBUG;
  if (! $DEBUG) { return; }
  echo "<pre>\n";
  foreach ($_GET as $var => $value) {
    echo "\$_GET['$var'] => ";
    _dbg_v($var, $value); }
  foreach ($_POST as $var => $value) {
    echo "\$_POST['$var'] => ";
    _dbg_v($var, $value); }
  if (isset($_SESSION)) {
    foreach ($_SESSION as $var => $value) {
      echo "\$_SESSION['$var'] => ";
      _dbg_v($var, $value); }}
  foreach ($_FILES as $var => $value) {
    echo "\$_FILES['$var'] => ";
    _dbg_v($var, $value); }
  echo "</pre>\n";
}

function _dbg_v($var, $value)
{
  if (is_array($value)) {
    echo '[ ' . implode(' , ', $value) . " ]\n"; }
  else {
    echo "'$value'\n"; }
}

?>
