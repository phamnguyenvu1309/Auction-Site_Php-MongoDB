<?php
  include('check_notification.php');
  check();
?>
<html>
<head>
    <link rel="stylesheet" href="AuctionPage.css">
<script>
function showUser(string) {
    const str = string.split(' ');
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("txtHint").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET","Auction.php?category="+str[0]+"&type="+str[1],true);
    xmlhttp.send();
}
function showTable(){
  showUser("Random random");
}
window.onload = showTable;
</script>
</head>
<body>
<div class="topnav" id="myTopnav">
  <a class="active" href="../AuctionPage/AuctionPage.php">Open Auction</a>
  <a href="../MyAuction/MyBid.php">My Bid</a>
  <a href="../MyAuction/Myauction.php">My Auction</a>
  <a href="../History/history.php">History</a>
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
<br>
<form>
<label for="cars">Number of bid placed:</label>
<select name="users" onchange="showUser(this.value)">
  <option value="">Select sort:</option>
  <option value="number ASC">ASC</option>
  <option value="number DESC">DESC</option>
</select>
<label for="cars">Closing time:</label>
<select name="users" onchange="showUser(this.value)">
  <option value="">Select sort:</option>
  <option value="time ASC">ASC</option>
  <option value="time DESC">DESC</option>
</select>
<label for="cars">Minimum price:</label>
<select name="users" onchange="showUser(this.value)">
  <option value="">Select sort:</option>
  <option value="price ASC">ASC</option>
  <option value="price DESC">DESC</option>
</select>
</form>
<br>
<div id="txtHint"><b>Person info will be listed here...</b></div>

</body>
</html>