<?php
    include('../Database/db.php');
    try {
        $sql = 'DELETE FROM notification WHERE id = :id';
        $id = intval($_GET['link']);
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $result = $stmt->execute();
        if ($result) {
            header("Location:notification.php");
        }
    } catch (PDOException $e) {
        echo $e->getMessage();                      
    }
?>