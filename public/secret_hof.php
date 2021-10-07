<?php
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

$SECRET_HASH = '890750c18b72b2978a3367ad732789d5d53c478d008bddbd9c84fce54b2b1375';
$secret = $_POST['secret'];
$file = '../private/secret_hof.csv';

if($secret && hash_equals(hash('sha256', $secret), $SECRET_HASH)) {
  file_put_contents($file, $_POST['csv']);
}else echo("wrong secret");

$lb = file_get_contents($file);

?>


<form method="POST">
<textarea name="csv" style="width: 800px; height: 600px;"><?=htmlentities($lb);?></textarea><br><br>
Secret: <input type="text" name="secret" value="<?=htmlentities($secret, ENT_QUOTES);?>"><br>
<button>submit</button>
</form>
