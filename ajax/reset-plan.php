<?php
  include "../config/constants.php";
  include "../config/session.php";

  if(isset($_GET['plan_id'])){
    $plan_id = $_GET['plan_id'];
  }

  // Reset start date and active values to ZERO
  $reset = "UPDATE compounding SET
    start_date = '0',
    active = 0

    WHERE id=$plan_id AND user_id=$id
  ";

  $reset_res = mysqli_query($conn, $reset);

  if($reset_res == true){
    // Delete records for this plan
    $deleteRec_res = mysqli_query($conn, "DELETE FROM compounding_items WHERE plan_id=$plan_id AND user_id=$id");

    if($deleteRec_res == true){
      $_SESSION['reset-success'] = "Plan has been successfully reset";
    }
    else{
      $_SESSION['reset-failed'] = "Failed to remove plan trade records. Something went wrong";
    }
  }
  else{
    $_SESSION['reset-failed'] = "Failed to reset plan. Something went wrong";
  }
?>