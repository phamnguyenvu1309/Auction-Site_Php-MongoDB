<?php
    function check($id) {
        include('../Database/db.php');
        $sql = 'SELECT COUNT(*) AS num FROM open_auction WHERE id = :id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $id,PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row['num'] == 0){
            header("Location:AuctionPage.php");
        }
    }
?>