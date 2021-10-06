<?php
    include('../Database/db.php');
    include('../Database/session.php');
    include('../AuctionPage/checkOpen.php');
    include("../vendor/autoload.php");
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->rmit->course;
    $id = intval($_GET['link']);
    check($id);
    $sql = 'INSERT INTO bids(auctionId,bid,bidder,date) values (:auctionId,:bid,:bidder, current_timestamp())';
    $stmt = $dbh->prepare($sql);
    if(isset($_POST['submit'])) {
        try {
            $bid = intval($_POST['bid']);
           
            $stmt->bindParam(':auctionId', $id, PDO::PARAM_INT);
            $stmt->bindParam(':bid', $bid, PDO::PARAM_INT);
            $stmt->bindParam(':bidder', $login_session);
            $result = $stmt->execute();
            
            if ($result) {header("Location:AuctionPage.php");}
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
<style>

* {
  box-sizing: border-box;
}
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
      xmlhttp.open("GET", "gethint.php?bid="+str+"&auctionId="+id, true);
      xmlhttp.send();
    }
  }
}
</script>
<style>
h1 {text-align: center;}
</style>
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
      if ($row['seller'] == $login_session){  
        $hide = "true";
      } else {
        $hide = "false";
      }
      $auction = intval($row['created_time']);
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
    if ($hide == "true"){
?>
    <style type="text/css">
    #biddingSection{
      display:none;
    }
    </style>
<?php
    }
?>  
  <form>
<?php
    $list = $collection->find(['auction'=>$auction]);
    foreach ($list as $result){
      $counter = 1;
      $escape = "false";
      while ($escape != "true"){
          $field = 'field'.$counter;
          if (isset($result->$field)){
            $string = $result->$field;
?>
            <label for="fname"><?php echo explode("_",$string)[0];?></label><br>
            <input type="text" id="auctionId" name="AuctionId" value="<?php echo explode("_",$string)[1];?>" readonly><br><br>
<?php     
            $counter++;
          } else {
            $escape = "true";
          }
      }
    }
?>
    </form>
  </div>
  <div class="column" >
    <div id="biddingSection">
      <h1>Bidding</h1>
      <form method="POST">
        <label for="fname">Bid:</label><br>
        <input type="text" id="bid" name="bid" onkeyup="showHint(this.value)" required><br>
        <p>Suggestions: <span id="txtHint"></span></p>
        <input type="submit" id="submit" value="Submit" name="submit">
      </form> 
    </div>
  </div>
</div>
</body>
</html>
