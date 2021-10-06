<?php
    if ( $_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['userLogin'])) {
        header("Location:login.php");
    }
    if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['managerLogin'])) {
        //header("Location:login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
a:link, a:visited {
  background-color: #f44336;
  color: white;
  padding: 14px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
}

a:hover, a:active {
  background-color: red;
}
</style>
<body>
    <h1>LOGIN</h1>
    <form method="post">
        <input type="submit" name="userLogin" value="User Login"/>
        <input type="submit" name="managerLogin" value="Manager Login"/>
    </form>
</body>