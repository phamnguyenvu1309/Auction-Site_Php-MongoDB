<?php

include("Database/db.php");
session_start();

if (isset($_POST['act'])) {
  $email= $_POST['email'];
  $password = $_POST['password'];

  if ( $email == "admin" && $password == "admin"){
    header("Location:Admin/adminPage.php");
  }

  $sql = "SELECT * FROM users WHERE email = '$email' OR phone = '$email'";
  $stmt = $dbh->query($sql);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  $row['psw'] ??= 'default value';

  $hashed = password_hash($row['psw'], PASSWORD_BCRYPT, ['cost' => 12]);

  if ($row && password_verify($password, $hashed)) {
    $_SESSION['login_user'] = $email;
    header("Location:AuctionPage/AuctionPage.php");
  } else {
    echo "<h2>Login failed.</h2>";
  }
}

?>

<form method="post">
  <div>
    <span>Email</span><br>
    <input type="text" name="email">
   </div>
  <div>
    <span>Password</span><br>
    <input type="password" name="password">
   </div>
   <div>
    <input type="submit" name="act" value="Login">
   </div>
   <div>
    <a href="register.php">Don't have an account</a>
  </div>
</form>