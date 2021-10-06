<?php
    include('../Database/db.php');
    include('../Database/session.php');
    include('../AuctionPage/checkOpen.php');
    $id = intval($_GET['link']);
    check($id);
    $sql = 'UPDATE bids SET bid = :bid, date = current_timestamp WHERE auctionId = :auctionId AND bidder = :bidder';
    $stmt = $dbh->prepare($sql);
    if(isset($_POST['submit'])) {
        try {
            $bid = intval($_POST['bid']);
           
            $stmt->bindParam(':auctionId', $id, PDO::PARAM_INT);
            $stmt->bindParam(':bid', $bid, PDO::PARAM_INT);
            $stmt->bindParam(':bidder', $login_session);
            $result = $stmt->execute();
            
            if ($result) {header("Location:Mybid.php");}
        } catch (PDOException $e) {
            echo $e->getMessage();                         
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* {
  box-sizing: border-box;
}

/* Create two equal columns that floats next to each other */
.column {
  float: left;
  width: 50%;
  padding: 10px;
  height: 300px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
</style>
</head>
<script>
function disableButton(){
    document.getElementById('submit').disabled = true;
}
function showHint(str) {
  var regex=/^[0-9]+$/;
  if (!str.match(regex)) {
    document.getElementById("txtHint").innerHTML = "Must input number";
    document.getElementById('submit').disabled = true;
  } else {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const id = urlParams.get('link');
    if (str.length == 0) {
      document.getElementById("txtHint").innerHTML = "";
      return;
    } else {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("txtHint").innerHTML = this.responseText;
        }
        if (document.getElementById("txtHint").innerText != "good"){
          document.getElementById('submit').disabled = true;
      } else {
          document.getElementById('submit').disabled = false;
    }
      };
      xmlhttp.open("GET", "../AuctionPage/gethint.php?bid="+str+"&auctionId="+id, true);
      xmlhttp.send();
    }
  }
}
window.onload = disableButton;
</script>
<body>
<div class="row">
    <div class="column">
    <h2>Auction Detail</h2>
<?php
    $sql = 'SELECT * FROM open_auction WHERE id = :id';
    $stmt = $dbh->prepare($sql);
    $id = intval($_GET['link']);
    $stmt->bindParam(':id', $id,PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    foreach($rows as $row) {
?>
    <form>
        <label for="fname">Id:</label><br>
        <input type="text" id="auctionId" name="AuctionId" value="<?php echo $row['id'];?>" readonly><br>
        <label for="lname">Product :</label><br>
        <input type="text" id="product" name="product" value="<?php echo $row['Product'];?>" readonly><br><br>
        <label for="fname">Minimum Price:</label><br>
        <input type="text" id="min" name="min" value="<?php echo $row['minimum price'];?>" readonly><br>
        <label for="lname">Closing Time :</label><br>
        <input type="text" id="date" name="date" value="<?php echo $row['closing time'];?>" readonly><br><br>
        <label for="lname">Current Bid :</label><br>
        <input type="text" id="maxBid" name="maxBid" value="<?php echo $row['current bid'];?>" readonly><br><br>
        <label for="lname">Number of bid placed:</label><br>
        <input type="text" id="num" name="num" value="<?php echo $row['number of bid placed'];?>" readonly><br><br>
    </form>
<?php 
    }
?>
    </div>
    <div class="column" >
    <h2>Bidding</h2>
<?php
    $sql = 'SELECT bid, max(date) AS current FROM bids WHERE bidder = :username AND auctionId = :auctionId';
    $stmt = $dbh->prepare($sql);
    $id = intval($_GET['link']);
    $stmt->bindParam(':auctionId', $id,PDO::PARAM_INT);
    $stmt->bindParam(':username', $login_session, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
    <form method="POST">
    <label for="fname">Your current bid:</label><br>
        <input type="text" id="current" name="current" value="<?php echo $row['bid'];?>" readonly><br>
        <label for="fname">New Bid:</label><br>
        <input type="text" id="bid" name="bid" onkeyup="showHint(this.value)"  required><br>
        <p>Suggestions: <span id="txtHint"></span></p>
        <input type="submit" id="submit" value="Update" name="submit">
    </form>
    </div>
</div>
</body>
</html>