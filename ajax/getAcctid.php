<?php
  include "../config/constants.php";
  include "../config/session.php";

  // $acct_id = $_POST['id'];
  $acct_id = $_POST['acct_id'];

  $sql = "UPDATE record_acct SET
        status=0
        WHERE user_id=$id
      ";

      $res = mysqli_query($conn, $sql);


      $sql1 = "UPDATE record_acct SET
        status=1
        WHERE id=$acct_id AND user_id=$id
      ";

      $res1 = mysqli_query($conn, $sql1);

  
  // Get the currency of selected account
    // Get the account number from record_acct table from database
    $get_acct = mysqli_query($conn, "SELECT acct_no FROM record_acct WHERE id=$acct_id");
    $acct_no = mysqli_fetch_assoc($get_acct)['acct_no'];

    // Get the currency from new_account table in database
    $get_curr = mysqli_query($conn, "SELECT currency FROM new_account WHERE acct_no=$acct_no");
    $acct_currency = mysqli_fetch_assoc($get_curr)['currency'];

  // Load recorded trade for selected account
  $output = "";

  $records = mysqli_query($conn, "SELECT * FROM records WHERE user_id=$id and acct_id=$acct_id ORDER BY record_time DESC LIMIT 10");
  if(mysqli_num_rows($records) > 0){

    $_SESSION['record'] = mysqli_num_rows($records);

    $sn = 1;
    while($rows = mysqli_fetch_assoc($records)){
      if($rows['profit'] > 0){
        $output .= '<tr>
                    <td id="date" class="font-italic">
                      ' . date("d/m/y", strtotime($rows['record_date'])) . ' 
                    </td>
                    <td style="font-weight:600" id="pair">'.$rows['pair'] . '</td>
                    <td id="position" style="text-transform:uppercase; font-weight:600">'.$rows['position'] . '</td>
                    <td id="lotsize">' . $rows['lotsize'] . '</td>
                    <td id="profit"><div class="text-success">' . $rows['profit'] . '</div></td>
                    <td id="modify_tbl">
                      <a class="px-2 text-secondary" style="color:#e7781c; font-size:13px; font-weight:600" href="" onclick="updateRecord(event, ' . $rows['id'] . ')">Edit</a>
                      <a class="px-2 font-italic" style="color:#e7781c;font-size:13px; text-decoration:underline" href="" onclick="deleteRecord(event, ' . $rows['id'] . ')">Delete</a>
                    </td>
                  </tr>';
      }
      else{
        $output .= '<tr>
                      <td id="date" class="font-italic">
                        ' . date("d/m/y", strtotime($rows['record_date'])) . ' 
                      </td>
                      <td style="font-weight:600" id="pair">'.$rows['pair'] . '</td>
                      <td id="position" style="text-transform:uppercase; font-weight:600">'.$rows['position'] . '</td>
                      <td id="lotsize">' . $rows['lotsize'] . '</td>
                      <td id="profit"><div class="text-danger">' . $rows['profit'] . '</div></td>
                      <td id="modify_tbl">
                        <a class="px-2 text-secondary" style="color:#e7781c; font-size:13px; font-weight:600" href="" onclick="updateRecord(event, ' . $rows['id'] . ')">Edit</a>
                        <a class="px-2 font-italic" style="color:#e7781c;font-size:13px; text-decoration:underline" href="" onclick="deleteRecord(event, ' . $rows['id'] . ')">Delete</a>
                      </td>
                    </tr>';
      }
    }

    echo $output;

    $sum_profit = mysqli_query($conn, "SELECT SUM(profit) AS total FROM records WHERE user_id=$id AND acct_id=$acct_id");
    while($row = mysqli_fetch_assoc($sum_profit)){ 
      echo '<tr id="column-totalling" class="bg-dark py-3">
              <td colspan="4" id="total" class="text-light text-right font-italic font-weight-normal">SUM TOTAL OF ALL TRADES =</td>
              <td id="sum_profits" class="text-light">' . $acct_currency . $row['total'] . '</td>
              <td></td>
            </tr>
          ';
    }
  }
  else{
    echo '
          <tr>
            <td colspan="6" class="text-md-center text-danger text-left font-italic p-3">No trade records for this account</td>
          </tr>
          ';

    echo '<tr id="column-totalling" class="bg-dark py-3">
              <td colspan="4" id="total" class="text-light text-right font-italic font-weight-normal">SUM TOTAL OF ALL TRADES =</td>
              <td id="sum_profits">' . $acct_currency . ' 0</td>
              <td></td>
            </tr>
          ';
  }
?>