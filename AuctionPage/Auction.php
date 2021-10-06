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
$category = $_GET['category'];
$sort = $_GET['type'];

switch($category) {
  case 'number':
    if ( $sort == 'ASC'){
        $rows = $dbh->query('SELECT * FROM open_auction ORDER BY `number of bid placed` ASC');
    } elseif ( $sort == 'DESC') {
        $rows = $dbh->query('SELECT * FROM open_auction ORDER BY `number of bid placed` DESC');
    } 
    break;
  case 'time':
    if ( $sort == 'ASC'){
        $rows = $dbh->query('SELECT * FROM open_auction ORDER BY `closing time` ASC');
    } elseif ( $sort == 'DESC') {
        $rows = $dbh->query('SELECT * FROM open_auction ORDER BY `closing time` DESC');
    } 
    break;
  case 'price':
    if ( $sort == 'ASC'){
        $rows = $dbh->query('SELECT * FROM open_auction ORDER BY `minimum price` ASC');
    } elseif ( $sort == 'DESC') {
        $rows = $dbh->query('SELECT * FROM open_auction ORDER BY `minimum price` DESC');
    } 
    break;
  default :
    $rows = $dbh->query('SELECT * FROM open_auction');
}

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
  echo '<td><button><a href="Bidding.php?link=' . $row['id'] . '">View</a></button></td>';
  echo "</tr>";
}
echo "</table>";
?>
</body>
</html>