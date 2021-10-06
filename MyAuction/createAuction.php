<?php
  include("../Database/db.php"); 
  include("../Database/session.php");
  include("../vendor/autoload.php");
  $client = new MongoDB\Client("mongodb://localhost:27017");
  $collection = $client->rmit->course;
  if($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['act'])) {
    try {
        $count = intval($_POST['count']);
        $product = $_POST['product'];
        $minimum = intval($_POST['price']);
        $date = $_POST['time'] ;
        $date = date("Y-m-d H:i:s",strtotime($date));
        $time = time();
        // insert additional detail to MongoDb
        //$bulk->insert(['auction' => $time]);
        $additional = array('auction' => $time);
        $fieldCounter = 0;
        for ($x = 1; $x <= $count; $x++) {
          $field = "field".$x;
          $detail = "detail".$x;
          if (!isset($_POST[$field]) || !isset($_POST[$detail])) {
            continue;
          }
          $fieldCounter++;
          $fieldDetail = $_POST[$field]."_". $_POST[$detail];
          $field = "field".$fieldCounter;
          $additional[$field] = $fieldDetail;
        }
        $collection->insertOne($additional);
        // Insert data to sql
        $sql = 'INSERT INTO auction(product_name,`minimum price`,`closing time`,seller,`current bid`,`number of bid placed`,created_time) VALUES (:product,:price,:dt,:seller,0,0,:current)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':product', $product, PDO::PARAM_STR);
        $stmt->bindParam(':price', $minimum, PDO::PARAM_INT);
        $stmt->bindParam(':dt', $date);
        $stmt->bindParam(':seller', $login_session);
        $stmt->bindParam(':current', $time, PDO::PARAM_STR);
        $result = $stmt->execute();
        if ($result){
          header("Location:Myauction.php");
        }
    }  catch (PDOException $e) {
      echo $e->getMessage();                      
  }
  }
  
  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Document</title>
    <script src="extending.js"></script>
</head>
<script>
function hideAlert(){
    document.getElementById('alert').style.visibility = "hidden";
    document.getElementById('count').setAttribute("type", "hidden");
}
function checkInp()
{
    var x=document.forms["form"]["price"].value;
    var regex=/^[0-9]+$/;
    if (!x.match(regex))
    {
        document.getElementById('alert').style.visibility = "visible";
        document.getElementById("txtHint").innerHTML = "Must input numnber";
        document.getElementById('submit').disabled = true;
    } else {
        document.getElementById('submit').disabled = false;
        document.getElementById('alert').style.visibility = "hidden";
    }
}
window.onload = hideAlert;
</script>
<body>
<div id="readroot" style="display: none">
    <input type="button" value="Remove additional field"
        onclick="this.parentNode.parentNode.removeChild(this.parentNode);" /><br /><br />
    <label for="field"><b>Additional field</b></label>
    <input type="text" placeholder="Enter field name" name="field" required><br><br><br>
    <label for="detail"><b>Additional detail</b></label>
    <input type="text" placeholder="Enter field detail" name="detail" required><br><br><br>
</div>
<form name="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <div class="container">
    <h1>Creat Auction</h1>
    <p>Please fill in this form to create an auction.</p>
    <hr>
    <input type="text" name="count" id="count">
    <label for="product"><b>Product Name</b></label>
    <input type="text" placeholder="Enter product name" name="product" required><br><br><br>

    <label for="price"><b>Minimum Price</b></label>
    <input type="text" placeholder="Enter minimum price" name="price"  onkeyup="checkInp()" required><br>
    <p id="alert">Alert: <span id="txtHint"></span></p>

    <label for="time"><b>Closing Time</b></label>
    <input type="datetime-local" name="time"  required><br><br>

    <span id="writeroot"></span>
    <input type="button" value="Add field" onclick="moreFields()"/>
    <button type="submit" id="submit" name="act" class="registerbtn">Create</button>
  </div>
</form>
</body>