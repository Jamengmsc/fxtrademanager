<?php
  include "../config/constants.php";
  include "../config/session.php";

  // Get update form values from FormData
  $rec_id = $_POST['rec_id'];
  $pair = mysqli_real_escape_string($conn, $_POST['pair']);
  $position = mysqli_real_escape_string($conn, $_POST['position']);
  $lotsize = mysqli_real_escape_string($conn, $_POST['lotsize']);
  $profit = mysqli_real_escape_string($conn, $_POST['profit']);

  if($pair !== "" && $position !== "" && $lotsize !== "" && $profit !== ""){
    // Update trade record
    $update = "UPDATE records SET
      pair = '$pair',
      position = '$position',
      lotsize = '$lotsize',
      profit = '$profit'

      WHERE id=$rec_id
    ";

    // Query against the database
    $update_res = mysqli_query($conn, $update);
    if($update_res == true){
      $_SESSION['edit-trade-success'] = "Trade Updated Successfully!";
    }
    else{
      $_SESSION['edit-trade-fail'] = "Failed to Update Trade!";
    }
  }
?>