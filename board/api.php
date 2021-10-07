<?php
$secret = '';
if (isset($_GET['secret']) && !password_verify($_GET['secret'], $secret)) die();

header("Access-Control-Allow-Origin", "*");
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

$file = 'board.csv';

if (isset($_GET['secret'])) {
  file_put_contents($file, $_GET['content']);
} else {
  header('Content-Type: text/csv');
  readfile($file);
}
?>
