<?php
  include "./config/constants.php";
  include "./config/session.php";

  if(isset($_GET['acct_no'])){
    $acct_no = $_GET['acct_no'];

    $active = 1;

    // Update the account no.
    $activate_acct = "UPDATE new_account SET active=$active WHERE acct_no=$acct_no";
    $activate_acct_res = mysqli_query($conn, $activate_acct);

    if($activate_acct_res == true){
      $updateRec_acct = "UPDATE record_acct SET active=$active WHERE acct_no=$acct_no";
      $updateRec_acct_res = mysqli_query($conn, $updateRec_acct);
    }

    // Redirect to homepage
    header("location: index.php");
  }
?>