<?php

$DEBUG = true;

if ($DEBUG) {
    error_reporting( E_ALL );
    ini_set('display_errors', 1); }

require('database.php');
$connection = db_connect();

$corpus = $_GET['corpus'];
$term = $_GET['term'];

function cmp($a, $b) {
    return bccomp($b->mi, $a->mi, 3);
}


if ($term) {
    $found = db_select($connection, "select * from technologies where name = '$term'");
    if (! empty($found)) {
        $frequency = $found[0]->count;
        $supers = db_select($connection, "select * from hierarchy where source = '$term'");
        $subs = db_select($connection, "select * from hierarchy where target = '$term'");
        $q = "select * from relations_cooc where source = '$term'";
        $related = db_select($connection, $q . " order by count desc limit 30");
        usort($related, "cmp");
        $related = array_slice($related, 0, 20);
        $q1 = "select * from relations_term where source = '$term'";
        $q2 = "select * from relations_term where target = '$term'";
        //printf("<pre>$q1</pre>");
        $relations1 = db_select($connection, $q1);
        $relations2 = db_select($connection, $q2);
        //print "<pre>"; print_r($found); print "</pre>";
    }
}

function url($term) {
    global $corpus;
    return sprintf("<a href=\"search.php?corpus=%s&term=%s\">%s</a>", $corpus, $term, $term);
}

function write_term($term) {
    global $found, $related, $relations1, $relations2, $frequency, $supers, $subs;
    if (empty($found)) {
        printf("<i>Term was not found in ontology</i>"); }
    else {
        printf("<p>Occurrences in corpus: %s</p>\n\n", $frequency);
        write_term_hierarchy($term, $supers, $subs);
        write_related_terms($term, $related);
        write_relations($term, $relations1, $relations2);
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
    printf("<u>Companion terms</u>\n\n");
    printf("<blockquote>\n");
    printf("Terms that cooccur with <i>$term</i> in the text. Printing the 20 ");
    printf("most relevant ones if there are more than 20 of those.<br/><br/>\n\n");
    foreach ($related as $t) {
        //printf("%d %f %s</br>\n", $t->count, $t->mi, url($t->target)); }
        printf("%s</br>\n", url($t->target)); }
    printf("</blockquote>\n\n");
    printf("</div>\n\n");
    printf("</p>\n");
}

function write_relations($term, $relations1, $relations2) {
    printf("<div class=box>\n\n");
    printf("<u>Relations</u>\n\n");
    printf("<blockquote>\n");
    printf("Relations that <i>$term</i> occurrs in.<br/><br/>\n\n");
    foreach ($relations1 as $r) {
        $context = explode("\tTerm\t", $r->context);
        write_context($context[0]);
        printf("%s ", url($r->source));
        write_context($context[1]);
        printf("%s ", url($r->target));
        write_context($context[2]);
        printf("</br>");
    }
    printf("</blockquote>\n\n");
    printf("</div>\n\n");
}

function write_context($context) {
    foreach(explode("\t", $context) as $element) {
        if (substr($element, 0, 4) == 'Span')
            printf("%s ", substr($element, 5));
        else if (substr($element, 0, 4) == 'Pred')
            printf("[%s] ", substr($element, 5));
    }

}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Taxonomy Browser</title>
<link href="style.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

<h1>Taxonomyy Browser - <?php echo $corpus ?></h1>

<p class="navigation">
[ <a href="index.php">browser home</a>
| <a href="search.php?corpus=<?php echo $corpus ?>">search</a>
]
</p>

<p>
    <form action="search.php">
    <input type="submit" value="search term">
    <input type="hidden" name="corpus" value="<?php echo $corpus ?>">
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
