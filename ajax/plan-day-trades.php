<?php
  include "../config/constants.php";
  include "../config/session.php";

  if(isset($_GET['d']) && isset($_GET['id'])){
    $date = $_GET['d'];
    $plan_id = $_GET['id'];

    $date = date("Y-m-d", $date);
    $output = '';

    // Query DB for trades
    $trades = mysqli_query($conn, "SELECT * FROM compounding_items WHERE user_id=$id AND plan_id=$plan_id AND item_date='$date'");
    if(mysqli_num_rows($trades) > 0){

      echo ' 
        <h4 class="text-gray font-weight-normal">All Trades on ' . date("d/m/Y", strtotime($date)) . ' </h4>

        <table class="table-sm day_trade_tbl">
          <tr>
            <th><input type="checkbox" id="sel_all" class="mb-n1"></th>
            <th>PAIR</th>
            <th>Position</th>
            <th>Lotsize</th>
            <th>Profit/Loss</th>
          </tr>';
      
      $sn = 1;

      while($rows = mysqli_fetch_assoc($trades)){
        $trade_id = $rows['id'];
        $pair = $rows['pair'];
        $position = $rows['position'];
        $lotsize = $rows['lotsize'];
        $profit = $rows['profit'];
  
        $output .= '
          <tr>
            <td><div class="text-center"><input type="checkbox" class="checked_items" name="sel_items[]" value="' . $trade_id . '"></td>
            <td>' . $pair . '</td>
            <td>' . $position . '</td>
            <td>' . $lotsize . '</td>
            <td>' . $profit . '</td>
          </tr>
        ';
      }

      echo $output;
      echo '
        </table>
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex justify-content-between align-items-center small">
            <div class="edit_trades mt-1 mb-n1 text-warning"><span>EDIT</span></div>
            <div class="delete_trades mt-1 mb-n1 text-gray font-italic"><span>Delete</span></div>
          </div>

          <div class="dismiss_trades mt-1 mb-n1 text-right"><span>Close</span></div>
        </div>';
      }
    else{
      if(date("Y-m-d") > $date){
        echo "<div class='text-warning text-left font-italic mt-n1'>No trade was taken on selected day...</div>";
        echo '<div class="dismiss_trades mt-2 mb-n1 text-right"><span>Close</span></div>';
      }
      else{
        echo "<div class='text-warning text-left font-italic'>No trades yet...</div>";
        echo '<div class="dismiss_trades mt-1 mb-n1 text-right"><span>Close</span></div>';
      }
    }
  }