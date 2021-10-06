<?php
    include('../Database/db.php');
    try {
        $id = intval($_GET['link']);
        $sql = "SELECT * FROM transaction_history WHERE auctionId = $id";
        $stmt = $dbh2->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $statement = $dbh2->prepare("CALL undo_transaction(?,?,?,?)");
        $statement->bindParam(1, $row['auctionId'], PDO::PARAM_INT);
        $statement->bindParam(2,$row['seller'], PDO::PARAM_STR);
        $statement->bindParam(3, $row['bidder'], PDO::PARAM_STR);
        $statement->bindParam(4, $row['bid'], PDO::PARAM_INT);
        $result = $statement->execute();
        if ($result){
            header("Location:adminPage.php");
        }
    } catch (PDOException $e) {
        echo $e->getMessage();                      
    }
?>