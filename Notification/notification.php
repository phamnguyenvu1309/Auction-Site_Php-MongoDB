<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../MyAuction/Myauction.css">
</head>
<body>
<div class="topnav" id="myTopnav">
  <a href="../AuctionPage/AuctionPage.php">Open Auction</a>
  <a href="../MyAuction/Mybid.php">My Bid</a>
  <a  href="../MyAuction/Myauction.php">My Auction</a>
  <a href="../History/history.php">History</a>
  <a class="active" href="notification.php">Notification</a>
  <div class="dropdown">
    <button class="dropbtn">User
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
      <a href="../User/user.php">Information</a>
      <a href="../logout.php">Logout</a>
    </div>
  </div>
</div>
<br>
<?php
include("../Database/session.php");
include("../Database/db.php");


$sql = 'SELECT * FROM notification WHERE receiver = :username';
$stmt = $dbh->prepare($sql);
$stmt->execute(['username' => $login_session]); 
$rows = $stmt->fetchAll();

echo "<table>
<tr>
<th>Auction</th>
<th>Message</th>
<th>Transaction</th>
</tr>";
foreach ($rows as $row) {
  echo "<tr>";
  echo "<td>" . $row['auctionId'] . "</td>";
  echo "<td>" . $row['message'] . "</td>";
  echo "<td>" . $row['transaction'] . "</td>";
  echo '<td><button><a href="deleteMessage.php?link='. $row['id'] .'">Delete</a></button></td>';
  echo "</tr>";
}
echo "</table>";
?>
<br>
</body>
</html>