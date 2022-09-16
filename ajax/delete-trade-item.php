<?php
  include "../config/constants.php";
  include "../config/session.php";

  $data = $_GET['item'];
  $items = explode(",", $data);

  // LOOP THROUGH THE SELECTED IDS AND DELETE ITEMS
  foreach($items as $sel_id){
    // Delete transaction item
    $del_trade_item = "DELETE FROM compounding_items WHERE id=$sel_id";
    $del_trade_item_res = mysqli_query($conn, $del_trade_item);
  }