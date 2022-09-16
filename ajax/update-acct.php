<?php
  include "../config/constants.php";
  include "../config/session.php";

  // Get account edit form data
  $acct_id = mysqli_real_escape_string($conn, $_POST['acct_id']);

  // Get account no from record_acct
  $get_acct_no = mysqli_query($conn, "SELECT acct_no FROM record_acct WHERE id=$acct_id");
  $acct_num = mysqli_fetch_assoc($get_acct_no)['acct_no'];


  $acct_no = mysqli_real_escape_string($conn, $_POST['acct_no']);
  $acct_type = mysqli_real_escape_string($conn, $_POST['acct_type']);
  $currency = mysqli_real_escape_string($conn, $_POST['currency']);
  $broker = mysqli_real_escape_string($conn, $_POST['broker']);
  $balance = mysqli_real_escape_string($conn, $_POST['balance']);
  $created = mysqli_real_escape_string($conn, $_POST['created']);

  // Update new_account table on database
    // Check for empty fields
    if($acct_no !== "" && $acct_type !== "" && $currency !== "" && $broker !== "" && $balance !== ""){

      // Update account table
      $upd_acct = "UPDATE new_account SET
                    acct_no=$acct_no,
                    acct_type='$acct_type',
                    currency='$currency',
                    broker='$broker',
                    balance='$balance'

                    WHERE acct_no=$acct_num
                  ";

      $upd_acct_res = mysqli_query($conn, $upd_acct);

      if($upd_acct_res == true){
        // Update Record_acct also
        $upd_rec_acct = "UPDATE record_acct SET
          acct_no=$acct_no,
          acct_type='$acct_type'

          WHERE id=$acct_id
        ";

        $upd_rec_acct_res = mysqli_query($conn, $upd_rec_acct);
      }
    }
?>