<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="user.css">
</head>
<body>
<div class="topnav" id="myTopnav">
  <a href="../AuctionPage/AuctionPage.php">Open Auction</a>
  <a href="../MyAuction/Mybid.php">My Bid</a>
  <a href="../MyAuction/Myauction.php">My Auction</a>
  <a href="../History/history.php">History</a>
  <div class="dropdown">
    <button class="dropbtn">User
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
      <a href="#">Information</a>
      <a href="../logout.php">Logout</a>
    </div>
  </div>
</div>
<br>
<?php
include("../Database/session.php");
include("../Database/db.php");


$sql = 'SELECT * FROM users WHERE email = :username';
$stmt = $dbh->prepare($sql);
$stmt->execute(['username' => $login_session]); 
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
?>
  <div class="card">
    <div class="container">
      <img src="../<?php echo $row['image'] ;?>" alt="Snow" style="width:100%">
      <button class="btn"><a href="editImage.php">Edit</a></button>
    </div>
    <h1><?php echo $row['Fname']." ".$row['Lname'] ;?></h1>
    <p class="title"><?php echo $row['email']." / ".$row['phone'] ;?></p>
    <p><?php echo $row['identification_number'] ;?></p><div class="row">
    <div class="row">
      <div class="column">
          <h2>Balence</h2>
          <p><?php echo $row['balence'] ;?></p>
      </div>
      <div class="column">
          <h2>In-dept</h2>
          <p><?php echo $row['in-dept'] ;?></p>
      </div>
    </div>
    <p><button>Contact</button></p>
  </div>
<?php
}
?>
</body>
</html>