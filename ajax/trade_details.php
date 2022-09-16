<?php
  include "../config/constants.php";
  include "../config/session.php";

  if(isset($_GET['trade_id'])){
    $trade_id = $_GET['trade_id'];
    $user_id = $id;

    $getRecord = mysqli_query($conn, "SELECT * FROM records WHERE user_id=$id AND id=$trade_id");

    if(mysqli_num_rows($getRecord) == 1){
      $row = mysqli_fetch_assoc($getRecord);
      
      // Get other account details of the record
      $date = date("d M Y", strtotime($row['record_date']));
      $pair = $row['pair'];
      $position = $row['position'];
      $lotsize = $row['lotsize'];
      $profit = $row['profit'];
    }

    // echo $acct_no . ", " . $acct_type . ", " . $lotsize . ", " . $profit;
    $data = array("$date", "$pair", "$position", "$lotsize", "$profit");
    $myJSON = json_encode($data);

    echo $myJSON;
  }
?>