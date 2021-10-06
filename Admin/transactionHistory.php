<!DOCTYPE html>
<html>
<head>
<style>
table {
  width: 100%;
  border-collapse: collapse;
}

table, td, th {
  border: 1px solid black;
  padding: 5px;
}

th {text-align: left;}
</style>
</head>
<body>

<?php
include('../Database/db.php');

$start = $_GET['begin'];
$end = $_GET['end'];

//$sql = 'SELECT * FROM close_auction WHERE `closing time` BETWEEN :start AND :end';
$sql = "SELECT * FROM transaction_history WHERE `closing time` BETWEEN :start AND :end";
$stmt = $dbh2->prepare($sql);
$stmt->bindParam(':start',$start);
$stmt->bindParam(':end',$end);
$stmt->execute();
$rows = $stmt->fetchAll();

echo "<table>
<tr>
<th>Auction</th>
<th>Seller</th>
<th>Winner</th>
<th>Transaction Amount</th>
</tr>";
foreach ($rows as $row) {
  echo "<tr>";
  echo "<td>" . $row['auctionId'] . "</td>";
  echo "<td>" . $row['seller'] . "</td>";
  echo "<td>" . $row['bidder'] . "</td>";
  echo "<td>" . $row['bid'] . "</td>";
  echo '<td><button><a href="undoTransaction.php?link=' . $row['auctionId'] . '">Undo</a></button></td>';
  echo "</tr>";
}
echo "</table>";
?>
</body>
</html>