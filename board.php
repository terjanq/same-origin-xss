<?php
isset($_GET['source']) && highlight_file(__FILE__) && die();

header("Access-Control-Allow-Origin", "*");
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

$file = 'board.csv';

if ($_GET['token'] === $_ENV["token"] && strlen($_ENV["token"]) > 0) {
  file_put_contents($file, $_GET['content']);
} else {
  header('Content-Type: text/csv');
  readfile($file);
}
?>
