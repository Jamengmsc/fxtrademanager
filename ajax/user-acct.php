<?php
  include "../config/constants.php";
  include "../config/session.php";

  if(isset($_GET['user_id'])){
    $user_id = $_GET['user_id'];

    // Delete all trade records for this user
    $del_records = "DELETE FROM records WHERE user_id=$user_id";
    $del_records_res = mysqli_query($conn, $del_records);

    if($del_records == false){
      echo "Failed to delete trade records for this user";
    }
    else{
      // Delete all transactions of deposits, withdrawals and transfers
      $del_trans = "DELETE FROM transfers WHERE user_id=$user_id";
      $del_trans_res = mysqli_query($conn, $del_trans);

      if($del_trans_res == false){
        echo "Failed to delete transactions for this user";
      }
      else{
        // Delete all accounts in record_acct for this user
        $del_record_acct = "DELETE FROM record_acct WHERE user_id=$user_id";
        $del_record_acct_res = mysqli_query($conn, $del_record_acct);

        if($del_record_acct_res == false){
          echo "Failed to delete accounts in record account!";
        }
        else{
          // Delete all accounts in New Account for this user
          $del_acct = "DELETE FROM new_account WHERE user_id=$user_id";
          $del_acct_res = mysqli_query($conn, $del_acct);

          if($del_acct_res == false){
            echo "Failed to delete accounts for this user";
          }
          else{
            // Delete User Account
            $del_user = "DELETE FROM user_reg WHERE id=$user_id";
            $del_user_res = mysqli_query($conn, $del_user);

            if($del_user_res == false){
              echo "Failed to delete user account";
            }
            else{
              echo "success";
            }
          }
        }
      }
    }
  }
?>