<?php
  include "../config/constants.php";
  include "../config/session.php";


  if(isset($_GET['date_from']) && isset($_GET['date_to'])){
    $dateFrom = $_GET['date_from'];
    $dateTo = $_GET['date_to'];


    if($dateFrom == "" && $dateTo !== ""){ // Check for empty Start date and throw error
      echo "Empty start date!";
    }
    elseif(!empty($dateFrom) && !empty($dateTo)){ // On input of both dates
      if($dateFrom > $dateTo){ // Check if start date is greater than end date and throw error
        echo "Date Order Error!";
      }
      else{ // Good to query mysqli database
        $custom = mysqli_query($conn, "SELECT SUM(profit) as custom_total FROM records WHERE record_date BETWEEN '$dateFrom' AND '$dateTo'");

        while($custom_row = mysqli_fetch_assoc($custom)){
          $custom_total = $custom_row['custom_total'];
        }
        $cust_profit = number_format($custom_total, 2, ".", ",");


        // Count the number of trades
        $custom_count = mysqli_query($conn, "SELECT COUNT(position) as custom_count FROM records WHERE record_date BETWEEN '$dateFrom' AND '$dateTo'");
        while($count_row = mysqli_fetch_assoc($custom_count)){
          $trades_custom = $count_row['custom_count'];
        }
      }
    }


    $data = array("$cust_profit", "$trades_custom");
    $myJSON = json_encode($data);

    echo $myJSON;
  }
?>