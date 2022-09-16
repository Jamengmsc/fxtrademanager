<?php
  include "../config/constants.php";
  include "../config/session.php";

  $data = $_GET['item'];
  $items = explode(",", $data);

  foreach($items as $item_id){
    $trade = mysqli_query($conn, "SELECT * FROM compounding_items WHERE id=$item_id");
    if(mysqli_num_rows($trade) == 1){
      $row = mysqli_fetch_assoc($trade);

      $trade_id = $row['id'];
      $pair = $row['pair'];
      $position = $row['position'];
      $lotsize = $row['lotsize'];
      $profit = $row['profit'];

      if($position == "Buy"){
        echo '
        <form action="" method="post">
          <h5>Edit Trade Item</h5>

          <input type="text" name="trade_pair" class="w-100" value="' . $pair . '">

          <div class="d-flex justify-content-between align-items-center">
            <select name="trade_position" class="w-100">
              <option value="Buy" selected>BUY</option>
              <option value="Sell">SELL</option>
            </select>

            <input type="text" name="trade_lotsize" class="w-100 ml-1" value="' . $lotsize . '">
          </div>

          <input type="text" name="trade_profit" class="w-100" value="' . $profit . '">
          <input type="hidden" name="trade_id" class="w-100" value="' . $trade_id . '">

          <div class="d-flex justify-content-between align-items-center">
            <div class="dismiss_edit text-right"><span>Close</span></div>
            <input type="submit" id="update-trade" class="text-warning small m-0" value="Save Changes">
          </div>
        </form>';
      }
      else{
        echo '
        <form action="" method="post">
          <h5>Edit Trade Item</h5>

          <input type="text" name="trade_pair" class="w-100" value="' . $pair . '">

          <div class="d-flex justify-content-between align-items-center">
            <select name="trade_position" class="w-100">
              <option value="Buy">BUY</option>
              <option value="Sell" selected>SELL</option>
            </select>

            <input type="text" name="trade_lotsize" class="w-100 ml-1" value="' . $lotsize . '">
          </div>

          <input type="text" name="trade_profit" class="w-100" value="' . $profit . '">
          <input type="hidden" name="trade_id" class="w-100" value="' . $trade_id . '">

          <div class="d-flex justify-content-between align-items-center">
            <div class="dismiss_edit text-right"><span>Close</span></div>
            <input type="submit" id="update-trade" class="text-warning small m-0" value="Save Changes">
          </div>
        </form>';
      }
      
    }
  }