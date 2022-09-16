<?php
  include "../config/constants.php";
  // include "../config/session.php";

  if(isset($_GET['acct_id'])){
    $acct_id = $_GET['acct_id'];
  }


  // RESET ALL ACCOUNT VALUES TO ZERO
    // Get Account No. from account ID
    $get_acctno = mysqli_query($conn, "SELECT acct_no FROM record_acct WHERE id=$acct_id");
    echo $acct_no = mysqli_fetch_assoc($get_acctno)['acct_no'];
    
    // Reset Account balance to Zero
    $acct_bal = "UPDATE new_account SET balance='0.00' WHERE acct_no=$acct_no";
    $acct_bal_res = mysqli_query($conn, $acct_bal);

    if($acct_bal_res == false){
      echo "Could not reset default account balance to Zero!";
      die();
    }
    else{
      // Delete all transfers for this account
      $del_transfers= "DELETE FROM transfers WHERE acct_id=$acct_id";
      $del_transfers_res = mysqli_query($conn, $del_transfers);

      if($del_transfers_res == false){
        echo "Could not delete all transactions for this account";
        die();
      }
      else{
        // Delete all trade records for this account
        $del_record = "DELETE FROM records WHERE acct_id=$acct_id";
        $del_record_res = mysqli_query($conn, $del_record);

        if($del_record_res == false){
          echo "Could not delete all trade records for this account";
          die();
        }
        else{
          echo "You have successfully reset this account";
        }
      }
    }
?>