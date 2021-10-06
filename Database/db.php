  
<?php

$user = 'user';
$pass = 'user';

$manager = 'root';
$mgn_pass = 's3742891';

global $dbh;
global $dbh2;

$dbh = new PDO('mysql:host=localhost;dbname=assignment', $user, $pass);
$dbh2 = new PDO('mysql:host=localhost;dbname=assignment', $manager, $mgn_pass);


?>

