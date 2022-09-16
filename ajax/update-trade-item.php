<?php
  include "../config/constants.php";
  include "../config/session.php";

  $trade_id = mysqli_real_escape_string($conn, $_POST['trade_id']);
  $trade_pair = mysqli_real_escape_string($conn, $_POST['trade_pair']);
  $trade_position = mysqli_real_escape_string($conn, $_POST['trade_position']);
  $trade_lotsize = mysqli_real_escape_string($conn, $_POST['trade_lotsize']);
  $trade_profit = mysqli_real_escape_string($conn, $_POST['trade_profit']);

  // Query DB to update trade item
  $update = "UPDATE compounding_items SET
            pair = '$trade_pair',
            position = '$trade_position',
            lotsize = '$trade_lotsize',
            profit = '$trade_profit'

            WHERE id=$trade_id
          ";

  $update_res = mysqli_query($conn, $update);

  if($update_res == false){
    echo "failed";
  }
  else{
    echo "success";
  }