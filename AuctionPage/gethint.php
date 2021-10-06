<?php
include('../Database/db.php');
include('../Database/session.php');

// get the q parameter from URL
$bid = intval($_REQUEST["bid"]);
$auctionId = $_REQUEST['auctionId'];

$stmt = $dbh->prepare("CALL check_bid(?,?,?,@result)");
$stmt->bindParam(1, $bid, PDO::PARAM_INT);
$stmt->bindParam(2, $login_session, PDO::PARAM_STR);
$stmt->bindParam(3, $auctionId, PDO::PARAM_INT);
$stmt->execute();

$sql = "SELECT @result";
$statement = $dbh->prepare($sql);
$statement->execute();

list($result) = $statement->fetch(PDO::FETCH_NUM);
$hint = $result;
// lookup all hints from array if $q is different from ""


// Output "no suggestion" if no hint was found or output correct values
echo $hint === "" ? "good" : $hint;
?>