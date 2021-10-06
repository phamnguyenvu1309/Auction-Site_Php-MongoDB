<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="Mybid.css">
</head>
<body>
<div class="topnav" id="myTopnav">
  <a href="../AuctionPage/AuctionPage.php">Open Auction</a>
  <a class="active" href="Mybid.php">My Bid</a>
  <a href="../MyAuction/Myauction.php">My Auction</a>
  <a href="../History/history.php">History</a>
  <a href="../Notification/notification.php">Notification</a>
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

<?php
include("../Database/session.php");
include("../Database/db.php");


$sql = 'SELECT DISTINCT o.* FROM open_auction o, bids b WHERE o.id = b.auctionId AND b.bidder = :username';
$stmt = $dbh->prepare($sql);
$stmt->execute(['username' => $login_session]); 
$rows = $stmt->fetchAll();
?>
<h2>Open Bid</h2>
<?php

echo "<table>
<tr>
<th>Product Name</th>
<th>Minimum Price</th>
<th>Closing Time</th>
<th>Seller</th>
<th>Current Bid</th>
<th>Number of bid placed</th>
</tr>";
foreach ($rows as $row) {
  echo "<tr>";
  echo "<td>" . $row['Product'] . "</td>";
  echo "<td>" . $row['minimum price'] . "</td>";
  echo "<td>" . $row['closing time'] . "</td>";
  echo "<td>" . $row['seller'] . "</td>";
  echo "<td>" . $row['current bid'] . "</td>";
  echo "<td>" . $row['number of bid placed'] . "</td>";
  echo '<td><button><a href="updateBid.php?link=' . $row['id'] . '">Update</a></button></td>';
  echo "</tr>";
}
echo "</table>";
?>
</body>
</html>