<?php
$row = 1;

$csv = array_map('str_getcsv', file(__DIR__.'/secret_hof.csv'));
$hof='';
for($i=0; $i<count($csv); $i++){
    $data=$csv[$i];
    $user = htmlentities($data[0]);
    $handle = htmlentities($data[1], ENT_QUOTES);
    $comment = htmlentities($data[2]);
    if(strlen(trim($comment)) != 0) $comment = "<span class=\"comment\">$comment</span>";
    else $comment = '';
    $hof.="<li><a href=\"https://twitter.com/$handle\">$user</a> $comment</li>\n";
}

?>