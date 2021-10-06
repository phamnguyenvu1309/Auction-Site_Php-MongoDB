<?php
    include("../Database/session.php");
    include("../Database/db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="history.css">
    <title>Document</title>
</head>
</head>
<body>
<div class="topnav">
  <a href="../AuctionPage/AuctionPage.php">Open Auction</a>
  <a href="../MyAuction/MyBid.php">My Bid</a>
  <a href="../MyAuction/Myauction.php">My Auction</a>
  <a class="active" href="history.php">History</a>
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
<div class="row">
    <div class="column">
    <h2>Bid History</h2>
<?php
    $sql = 'SELECT b.auctionId, b.bid, b.date
    FROM bids b, close_auction c WHERE b.auctionId = c.id
    AND bidder = :bidder';
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':bidder', $login_session, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    echo "<table>
    <tr>
    <th>Auction Id</th>
    <th>Bid</th>
    <th>Date</th>
    </tr>";
    foreach($rows as $row) {
        echo "<tr>";
        echo "<td>" . $row['auctionId'] . "</td>";
        echo "<td>" . $row['bid'] . "</td>";
        echo "<td>" . $row['date'] . "</td>";
        echo "</tr>";
    }
    echo "</table>"; 
?>
    </div>
    <div class="column" >
    <h2>Winning Auction</h2>
<?php
    $sql = 'SELECT DISTINCT a.id, a.Product, b.bid, a.`closing time` AS date
    FROM close_auction a, bids b
    WHERE a.id = b.auctionId
    AND a.`current bid` = b.bid
    AND b.bidder = :bidder';
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':bidder', $login_session, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    echo "<table>
    <tr>
    <th>Auction</th>
    <th>Product Name</th>
    <th>Bid</th>
    <th>Date</th>
    </tr>";
    foreach($rows as $row) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['Product'] . "</td>";
        echo "<td>" . $row['bid'] . "</td>";
        echo "<td>" . $row['date'] . "</td>";
        echo "</tr>";
    }
    echo "</table>"; 
?>
    </div>
</div>
</body>
</html>