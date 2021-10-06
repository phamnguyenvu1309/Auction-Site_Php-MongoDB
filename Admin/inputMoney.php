<?php
    include("../Database/db.php");
    $sql = 'UPDATE users SET balence = balence + :amount WHERE email = :username';
    $stmt = $dbh2->prepare($sql);
    if(isset($_POST['submit'])) {
        try {
            $amount = intval($_POST['money']);
            $username = $_POST['userlist'];
            
            $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $result = $stmt->execute();
            
            if ($result) {header("Location:inputMoney.php");}
            
        } catch (PDOException $e) {
            echo $e->getMessage();                         
        }
    }
?>
<html>
<head>
    <link rel="stylesheet" href="../AuctionPage/AuctionPage.css">
<script>
function hideAlert(){
    document.getElementById('alert').style.visibility = "hidden";
}
function checkInp()
{
    var x=document.forms["form"]["price"].value;
    var regex=/^[0-9]+$/;
    if (!x.match(regex))
    {
        document.getElementById('alert').style.visibility = "visible";
        document.getElementById("txtHint").innerHTML = "Must input numnber";
        document.getElementById('submit').disabled = true;
    } else {
        document.getElementById('submit').disabled = false;
        document.getElementById('alert').style.visibility = "hidden";
    }
}
window.onload = hideAlert;
</script>
</head>
<body>
<div class="topnav" id="myTopnav">
  <a href="adminPage.php">Open Auction</a>
  <a class="active" href="inputMoney.php">Money</a>
  <a href="../logout.php" style="float:right">Logout</a>
  
</div>
<br>
<?php
    $statement = $dbh2->prepare("SELECT email FROM users");
    $statement->execute();
    $data = $statement->fetchAll();
?>
<label for="cars">Choose a user:</label>
<select id="cars" name="userlist" form="carform">
    <option value="">Select a user email</option>
<?php foreach ($data as $row): ?>
    <option value="<?=$row["email"]?>"><?=$row["email"]?></option>
<?php endforeach ?>
</select>
<br><br>
<form id="carform" method="post">
  <label for="fname">Money Amount:</label>
  <input type="text" id="fname" name="money" onkeyup="checkInp()" required><br>
  <p id="alert">Alert: <span id="txtHint"></span></p>
  <input type="submit" id="submit" value="Submit" name="submit">
</form>


</body>
</html>