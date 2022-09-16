<?php
  include "../config/constants.php";
  include "../config/session.php";

  if(isset($_GET['acct_id'])){
    $acct_id = $_GET['acct_id'];
    $user_id = $id;

    // Get Account number from record_acct table in database
    $get_acct = mysqli_query($conn, "SELECT acct_no FROM record_acct WHERE id=$acct_id");
    $acct_no = mysqli_fetch_assoc($get_acct)['acct_no'];

    // Get account full details from new_account table in database
    $acct_details = mysqli_query($conn, "SELECT * FROM new_account WHERE acct_no=$acct_no");
    $acct_row = mysqli_fetch_assoc($acct_details);

    $type = $acct_row['acct_type'];
    $currency = $acct_row['currency'];
    $broker = $acct_row['broker'];
    $balance = $acct_row['balance'];
    $date = date("d/m/Y", strtotime($acct_row['date_added']));


    // echo $acct_no . ", " . $acct_type . ", " . $lotsize . ", " . $profit;
    $data = array("$acct_id", "$acct_no", "$type", "$currency", "$broker", "$balance", "$date");
    $myJSON = json_encode($data);

    echo $myJSON;
  }



?>