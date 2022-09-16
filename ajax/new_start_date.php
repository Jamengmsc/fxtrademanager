<?php
  include "../config/constants.php";
  include "../config/session.php";

  $plan_id = mysqli_real_escape_string($conn, $_POST['plan_id']);
  $plan_weekend = mysqli_real_escape_string($conn, $_POST['plan_weekend']);
  $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);

  $today = date("Y-m-d");
  $day = date("l", strtotime($today)); // Today
  $selected_day = date("l", strtotime($start_date)); // Checks for weekends


  if($plan_weekend == 0){
    if($day == "Saturday" || $day == "Sunday"){
      $_SESSION['start-date-err'] = "You cannot select " . $day . " for this plan. Select a week day";
    }
    else{ // If it is not a weekend
      if($start_date < $today){
        $_SESSION['start-date-err'] = "Invalid Start Date. Choose a future date";
      }
      else {
        if(!empty($start_date)){
          // Update DB
          $active = 1;
    
          $insert_start_date = "UPDATE compounding SET
            start_date = '$start_date',
            active = $active
      
            WHERE id=$plan_id
          ";
    
          // Query database
          $insert_start_date_res = mysqli_query($conn, $insert_start_date);
    
          if($insert_start_date_res == true){
            $_SESSION['start-date'] = "Start date is successfully set";
          }
        }
        else{
          $_SESSION['start-date-err'] = "No date was selected";
        }
      }
    }
  }

  // If plan includes weekends
  else{
    if($start_date < $today){
      $_SESSION['start-date-err'] = "Invalid Start Date. Choose a future date";
    }
    else {
      if(!empty($start_date)){
        // Update DB
        $active = 1;
  
        $insert_start_date = "UPDATE compounding SET
          start_date = '$start_date',
          active = $active
    
          WHERE id=$plan_id
        ";
  
        // Query database
        $insert_start_date_res = mysqli_query($conn, $insert_start_date);
  
        if($insert_start_date_res == true){
          $_SESSION['start-date'] = "Start date is successfully set";
        }
      }
      else{
        $_SESSION['start-date-err'] = "No date was selected";
      }
    }
  }
?>