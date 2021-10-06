<?php
    function check() {
        include('../Database/db.php');
        include('../Database/session.php');
        $sql = 'SELECT COUNT(*) AS num FROM notification WHERE receiver = :username';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':username', $login_session,PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row['num'] != 0){
            echo "<script>alert('You have ".$row['num']." notifications');</script>";
        }
    }
?>