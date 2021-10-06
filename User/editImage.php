<?php
  include("../Database/db.php"); 
  include("../Database/session.php"); 

  if($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['act'])) {
    try {
      $sql = 'UPDATE users SET image = :fimage WHERE email = :email';
      $stmt = $dbh->prepare($sql);

      $filename = $_FILES['txt_file']['name'];

      $target_file = '../upload/'.$filename;

      $file_extension = pathinfo(
        $target_file, PATHINFO_EXTENSION);

      $file_extension = strtolower($file_extension);
       
      $valid_extension = array("png","jpeg","jpg");

      if(in_array($file_extension, $valid_extension)) {
        if(move_uploaded_file(
            $_FILES['txt_file']['tmp_name'],
            $target_file)
        ) {
          $stmt->bindParam(':fimage', $target_file);
        }
      }
      $stmt->bindParam(':email', $login_session, PDO::PARAM_STR);
      $result = $stmt->execute();
      if ($result){
        header("Location:user.php");
      }
    }  catch (PDOException $e) {
      echo $e->getMessage();                      
  }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Document</title>
</head>
<body>
<form name="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype='multipart/form-data'>
  <div class="container">
    <h1>Cover Iamge</h1>
    <p>Please choose a picture for your account.</p>
    <hr>

    <label for="psw-repeat"><b>Cover Image</b></label><br>
    <input type='file' name='txt_file' required >
    <hr>
    <button type="submit" name="act" class="registerbtn">Register</button>
  </div>
</form>
</body>