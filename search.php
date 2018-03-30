<?php 

$DEBUG = false;
$DEBUG = true;

if ($DEBUG) {
    error_reporting( E_ALL );
    ini_set('display_errors', 1); }

require('database.php');
db_connect();

$term = $_GET['term'];

if ($term) {
    $found = db_select_objects("select * from technologies where name = '$term'"); 
    if (! empty($found)) {
        $frequency = $found[0]->count;
        //print "<pre>"; print_r($found); print "</pre>"; 
        $supers = db_select_objects("select * from hierarchy where source = '$term'");
        $subs = db_select_objects("select * from hierarchy where target = '$term'");
        $related = db_select_objects("select * from relations where source = '$term' limit 20");
        print "<pre>"; print_r($supers); print "</pre>"; 
    }
}

dbg_vars();


function url($term) {
    return sprintf("<a href=\"search.php?term=%s\">%s</a>\n", $term, $term);
}

function write_result($term) {

    global $found, $related, $frequency, $supers, $subs;

    if (empty($found)) {
        printf("<i>Term was not found in ontology</i>"); }

    else {

        $indent = "&nbsp;&nbsp;&nbsp;&nbsp";

        printf("<p>Occurrences in corpus: %s</p>\n\n", $frequency);

        printf("<p>\n");
        if (empty($supers)) {
            printf("Top</br>\n"); }
        else {
            foreach ($supers as $super) {
                printf("%s</br>\n", url($super->target)); }}
        printf("%s<b>%s</b></br>\n", $indent, $term);
        foreach ($subs as $sub) {
            printf("%s%s%s</br>\n", $indent, $indent, url($sub->source)); }
        printf("</p>\n");

        printf("<p><u>Related terms</u></p>\n\n");
        printf("<blockquote>\n");
        foreach ($related as $t) {
            printf(url($t->target)); }
        printf("</blockquote>\n\n");
    }
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>BSO Browser - Search</title>
<link href="style.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

<h1>Ontology Browser</h1>

<p class="navigation">
[ <a href="index.php">browser home</a>
| <a href="search.php">search</a>
]
</p>

<p>
    <form action="search.php">
    <input type="submit" value="search term">
    <input type="text" name="term">
    </form>
</p>
   
<?php if ($term) { ?>
<div class="infoboxc">
<?php
    printf("<h2 class=header>%s</h2>\n\n", $term);
    write_result($term);
?>
</div>
<?php } ?>
  
</body>
</html>
