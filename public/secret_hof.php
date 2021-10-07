<?php
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
$file = '../private/secret_hof.csv';


if(isset($_POST['csv'])){
  file_put_contents($file, $_POST['csv']);
  header('location: /');
  die();
}

$lb = file_get_contents($file);

?>


<form method="POST">
<textarea name="csv" style="width: 800px; height: 600px;"><?=htmlentities($lb);?></textarea><br><br>
<button>submit</button>
</form>
