<?php
  include("Database/db.php"); 

  if($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['act'])) {
    try {
      $email = test_input($_POST['email']);
      $password = test_input($_POST['password']);
      $phone = test_input($_POST['phone']);
      $Fname = test_input($_POST['Fname']);
      $Lname = test_input($_POST['Lname']);
      $branch = test_input($_POST['branchList']);
      $iden = test_input($_POST['identification']);

      $sql = 'SELECT COUNT(*) AS num FROM users WHERE email = :email';
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam(':email', $email);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if($row['num'] > 0){
        die('That username already exists!');
      }

      $sql = 'INSERT INTO users(email,phone,psw,Fname,Lname,balence,image,branch_name,identification_number) VALUES(:email, :phone, :psw, :Fname, :Lname, 0,:fimage, :branch, :iden)';
      $stmt = $dbh->prepare($sql);

      $filename = $_FILES['txt_file']['name'];

      $target_file = 'upload/'.$filename;

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
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':psw', $password);
      $stmt->bindParam(':phone', $phone);
      $stmt->bindParam(':Fname', $Fname);
      $stmt->bindParam(':Lname', $Lname); 
      $stmt->bindParam(':branch', $branch);
      $stmt->bindParam(':iden', $iden);
      $result = $stmt->execute();
      if ($result){
        header("Location:login.php");
      }
    }  catch (PDOException $e) {
      echo $e->getMessage();                      
  }
  }
  
  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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
<script>
  function checkPassword(str){
    var psw = document.getElementById('psw').value;
    if ( str != psw){
      document.getElementById('pswMsg').innerHTML = "*Uncorrect password input";
    } else {
      document.getElementById('pswMsg').innerHTML = "";
    }
  }
</script>
<script>
  function checkInp(){
    var x= document.forms["form"]["phone"].value;
    var regex=/^[0-9]+$/;
    if (x.match(regex) && x.length == 10)
    {
        document.getElementById("phoneMsg").innerHTML = "";
   } else {
     document.getElementById("phoneMsg").innerHTML = "*Must input numnber and contains 10 number";
   }
  }
</script>
<script>
  function checkIn2p(){
    var x= document.forms["form"]["identification"].value;
    var regex=/^[0-9]+$/;
    if (x.match(regex) && x.length == 10)
    {
        document.getElementById("idenMsg").innerHTML = "";
   } else {
     document.getElementById("idenMsg").innerHTML = "*Must input numnber and contains 11 number";
   }
  }
</script>
<body>
<form id="registerForm" name="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype='multipart/form-data'>
  <div class="container">
    <h1>Register</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>

    <?php
      $statement = $dbh->prepare("SELECT branch_name FROM branch");
      $statement->execute();
      $data = $statement->fetchAll();
    ?>
    <label for="branch">Choose a branch:</label>
    <select id="branch" name="branchList" form="registerForm">
        <option value="">Select a branch</option>
        <?php foreach ($data as $row): ?>
        <option value="<?=$row["branch_name"]?>"><?=$row["branch_name"]?></option>
        <?php endforeach ?>
    </select><br><br>

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter email" name="email" id="email"  required>

    <label for="phone"><b>Phone</b></label>
    <input type="text" placeholder="Enter phone" name="phone" id="phone"  onkeyup="checkInp()"required>
    <p><span id="phoneMsg" style="color:red"></span></p>

    <label for="identification"><b>Identification Number</b></label>
    <input type="text" placeholder="Enter idetntfication number" name="identification" id="identification"  onkeyup="checkInp2()" required>
    <p><span id="idenMsg" style="color:red"></span></p>

    <label for="Fname"><b>First Name</b></label>
    <input type="text" placeholder="Enter First Name" name="Fname" id="Fname" required>

    <label for="Lname"><b>Last Name</b></label>
    <input type="text" placeholder="Enter Last Name" name="Lname" id="Lname" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" id="psw" required>

    <label for="psw-repeat"><b>Repeat Password</b></label>
    <input type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" onkeyup="checkPassword(this.value)" required>
    <p><span id="pswMsg" style="color:red"></span></p>

    <label for="psw-repeat"><b>Cover Image</b></label><br>
    <input type='file' name='txt_file' required >
    <hr>

    <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>
    <button type="submit" name="act" class="registerbtn">Register</button>
  </div>

  <div class="container signin">
    <p>Already have an account? <a href="login.php">Sign in</a>.</p>
  </div>
</form>
</body>
