<?php
  include "../config/constants.php";

  if(isset($_GET['acct_id'])){
    $acct_id = $_GET['acct_id'];

    // Delete account details from records table in database
    $del_record = "DELETE FROM records WHERE acct_id=$acct_id";
    $del_record_res = mysqli_query($conn, $del_record);

    if($del_record_res == true){
      // Get account no from record_acct
      $get_acct_no = mysqli_query($conn, "SELECT acct_no FROM record_acct WHERE id=$acct_id");
      $acct_row = mysqli_fetch_assoc($get_acct_no);

      $acct_no = $acct_row['acct_no'];

      // Delete account from new_account table 
      $del_new_acct = "DELETE FROM new_account WHERE acct_no=$acct_no";
      $del_new_acct_res = mysqli_query($conn, $del_new_acct);

      if($del_new_acct_res == true){
        // Delete account details from transfers 
        $del_transfers = "DELETE FROM transfers WHERE acct_id=$acct_id";
        $del_transfers_res = mysqli_query($conn, $del_transfers);

        if($del_transfers_res == true){
          // Delete account details from record_acct
          $del_record_acct = "DELETE FROM record_acct WHERE id=$acct_id";
          $del_record_acct_res = mysqli_query($conn, $del_record_acct);

          if($del_record_acct_res == true){
            echo "Account Deleted Successfully";
          }
        }
      }
    }
  }
?>