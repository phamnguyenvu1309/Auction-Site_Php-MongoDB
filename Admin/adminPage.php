<html>
<head>
    <link rel="stylesheet" href="../AuctionPage/AuctionPage.css">
<script>
function showTable() {
    var start = new Date(document.getElementById("start").value).toISOString().slice(0,10);
    var end = new Date(document.getElementById("end").value).toISOString().slice(0,10);
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("txtHint").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET","transactionHistory.php?begin="+start+"&end="+end,true);
    xmlhttp.send();
}
</script>
</head>
<body>
<div class="topnav" id="myTopnav">
  <a class="active" href="adminPage.php">Open Auction</a>
  <a href="inputMoney.php">Money</a>
  <a href="../logout.php" style="float:right">Logout</a>
  
</div>
<br>
<form>
    <label for="birthday">Start Time:</label>
    <input type="date" id="start" name="start">
    <label for="birthday">End Time:</label>
    <input type="date" id="end" name="end">
</form>
<button onclick="showTable()">Confirm</button>
<br>
<div id="txtHint"><b>Transaction history will be listed here</b></div>

</body>
</html>