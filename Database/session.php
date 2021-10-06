<?php
    include("../Database/db.php");
    session_start();

    $user_check = $_SESSION['login_user'];

    $sql = "SELECT * FROM users WHERE email = '$user_check' or phone = '$user_check'";
    $stmt = $dbh->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $row['email'] ??= 'default value';

    $login_session = $row['email']; 

    if(!isset($_SESSION['login_user'])){
        header("location:login.php");
        die();
    }
?>