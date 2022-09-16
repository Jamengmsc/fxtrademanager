<?php
  include "../config/constants.php";
  include "../config/session.php";

  if(isset($_GET['rec_id'])){
    $rec_id = $_GET['rec_id'];
    $user_id = $id;

    $getRecord = mysqli_query($conn, "SELECT * FROM records WHERE user_id=$id AND id=$rec_id");

    if(mysqli_num_rows($getRecord) == 1){
      $rows = mysqli_fetch_assoc($getRecord);
      $acct_id = $rows['acct_id'];

      $getAcctNo = mysqli_query($conn, "SELECT acct_no, acct_type FROM record_acct WHERE id=$acct_id");

      if(mysqli_num_rows($getAcctNo) == 1){
        $acct_row = mysqli_fetch_assoc($getAcctNo);

        $acct_no = $acct_row['acct_no'];
        $acct_type = $acct_row['acct_type'];
      }

      // Get other account details of the record
      $pair = $rows['pair'];
      $position = $rows['position'];
      $lotsize = $rows['lotsize'];
      $profit = $rows['profit'];
    }

    $data = array("$acct_no", "$acct_type", "$lotsize", "$profit", "$rec_id", "$pair", "$position");
    $myJSON = json_encode($data);

    echo $myJSON;
  }
?>