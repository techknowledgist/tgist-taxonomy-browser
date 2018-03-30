<?php 

$DEBUG = false;
//$DEBUG = true;

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
        //print "<pre>"; print_r($supers); print "</pre>"; 
    }
}

dbg_vars();


function url($term) {
    return sprintf("<a href=\"index.php?term=%s\">%s</a>", $term, $term);
}

function write_term($term) {

    global $found, $related, $frequency, $supers, $subs;

    if (empty($found)) {
        printf("<i>Term was not found in ontology</i>"); }
    else {
        printf("<p>Occurrences in corpus: %s</p>\n\n", $frequency);
        write_term_hierarchy($term, $supers, $subs);
        write_related_terms($term, $related);
    }
}


function write_term_hierarchy($term, $supers, $subs) {

    $indent = "&emsp;";
    printf("<p class=box>\n");
    if (empty($supers)) {
        printf("Top</br>\n"); }
    else {
        foreach ($supers as $super) {
            printf("%s</br>\n", url($super->target)); }}
    printf("%s<b>%s</b></br>\n", $indent, $term);
    foreach ($subs as $sub) {
        printf("%s%s%s</br>\n", $indent, $indent, url($sub->source)); }
    printf("</p>\n");
}

function write_related_terms($term, $related){

    printf("<div class=box>\n\n");
    printf("<u>Related terms</u>\n\n");
    printf("<blockquote>\n");
    printf("<i>Printing no more than 20 related terms, selection is currently random.</i><br/><br/>\n\n");
    foreach ($related as $t) {
        printf("%s</br>\n", url($t->target)); }
    printf("</blockquote>\n\n");
    printf("</div>\n\n");
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Ontology Browser</title>
<link href="style.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

<h1>Ontology Browser</h1>

<!--
<p class="navigation">
[ <a href="index.php">browser home</a>
| <a href="search.php">search</a>
]
</p>
-->

<p>
    <form action="index.php">
    <input type="submit" value="search term">
    <input type="text" name="term">
    </form>
</p>
   
<?php if ($term) { ?>
<?php
    printf("<h2>%s</h2>\n\n", $term);
    write_term($term);
?>
<?php } ?>
  
</body>
</html>
