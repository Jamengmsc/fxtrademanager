<?php
  include "../config/constants.php";

  if(isset($_GET['acct_id'])){
    $acct_id = $_GET['acct_id'];

    // Get Account No
    $getAcctno = mysqli_query($conn, "SELECT acct_no FROM record_acct WHERE id=$acct_id");
    $acct_no = mysqli_fetch_assoc($getAcctno)['acct_no'];

    // Check account active status
    $checkActive = mysqli_query($conn, "SELECT active FROM new_account WHERE acct_no=$acct_no");
    if(mysqli_num_rows($checkActive) == 1){
      $row = mysqli_fetch_assoc($checkActive);

      $acctStat = $row['active'];
    }

    if($acctStat == 1){
      $active = 0;
      echo $status = "disabled";
    }
    elseif($acctStat == 0){
      $active = 1;
      echo $status = "enabled";
    }

    // Disable account
    $disableAcct = "UPDATE new_account SET active=$active WHERE acct_no=$acct_no";
    $disableAcct_res = mysqli_query($conn, $disableAcct);

    if($disableAcct_res == true){
      $updateRec_acct = "UPDATE record_acct SET active=$active WHERE acct_no=$acct_no";
      $updateRec_acct_res = mysqli_query($conn, $updateRec_acct);
    }
  }
?>