<?php
  include "../config/constants.php";

  $sel_id = $_GET['sel_id'];
  $output = "";

  $res = mysqli_query($conn, "SELECT * FROM savings WHERE id=$sel_id");
  if(mysqli_num_rows($res) == 1){
    $row = mysqli_fetch_assoc($res);

    if($row['trans_type'] == "Deposit"){
      $output = '
        <div class="bg-light p-1 pb-2 rounded-lg">
          <form method="post" class="edit_savings" autocomplete="off" enctype="multipart/form-data">
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="m-0 p-3" style="font-size:18px;color:#e7781c;">Edit Transaction</h6>

              <i class="fa fa-times mr-3" style="font-size:20px;color:#e7781c;cursor:pointer"></i>
            </div>

            <div class="col-12">
              <label for="type">Transaction Type:</label>
                <select name="trans_type" id="type" class="w-100">
                  <option value="Deposit" selected>Deposit</option>
                  <option value="Withdrawal">Withdrawal</option>
                </select>
            </div>

            <div class="col-12">
              <label for="description">Description:</label>
              <input type="text" name="desc" class="w-100 mb-2" id="trans_desc" value="' . $row['trans_desc'] . '">
            </div>

            <div class="d-flex justify-content-between align-items-start">
              <div class="col-6">
                <label for="amount">Amount:</label>
                <input type="text" name="amount" class="w-100 mb-2" id="amount" value="' . $row['amount'] . '">
              </div>

              <div class="text-center">
                <label for="curr_receipt">Current Receipt</label>
                <img src="./images/receipts/' . $row['receipt'] . '" width="30%" class="img-fluid">
                <input type="hidden" name="curr_receipt" class="w-100 mb-2" value="' . $row['receipt'] . '">
              </div>
            </div>

            <div class="col-12">
              <label for="amount">Select New Receipt:</label>
              <input type="file" name="new_receipt" class="w-100 mb-2" id="file">
            </div>

            <input type="hidden" name="selected_id" value="' . $row['id'] . '">
          
            <div class="col-12 mt-3">
              <input type="submit" id="update_trans" name="update_trans" value="Update Transaction" class="w-100 text-dark bg-warning border-0" style="font-weight:600" onclick="editTransaction(event)">
            </div>
          </form>
        </div>
      ';
    }
    elseif($row['trans_type'] == "Withdrawal"){
      $output = '
        <div class="bg-light p-1 pb-2 rounded-lg">
          <form method="post" class="edit_savings" autocomplete="off" enctype="multipart/form-data">
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="m-0 p-3" style="font-size:18px;color:#e7781c;">Edit Transaction</h6>

              <i class="fa fa-times mr-3" style="font-size:20px;color:#e7781c;cursor:pointer"></i>
            </div>

            <div class="col-12">
              <label for="type">Transaction Type:</label>
                <select name="trans_type" id="type" class="w-100">
                  <option value="Deposit">Deposit</option>
                  <option value="Withdrawal" selected>Withdrawal</option>
                </select>
            </div>

            <div class="col-12">
              <label for="description">Description:</label>
              <input type="text" name="desc" class="w-100 mb-2" id="trans_desc" value="' . $row['trans_desc'] . '">
            </div>

            <div class="d-flex justify-content-between align-items-start">
              <div class="col-6">
                <label for="amount">Amount:</label>
                <input type="text" name="amount" class="w-100 mb-2" id="amount" value="' . $row['amount'] . '">
              </div>

              <div class="text-center">
                <label for="curr_receipt">Current Receipt</label>
                <img src="./images/receipts/' . $row['receipt'] . '" width="30%" class="img-fluid">
                <input type="hidden" name="curr_receipt" class="w-100 mb-2" value="' . $row['receipt'] . '">
              </div>
            </div>

            <div class="col-12">
              <label for="amount">Select New Receipt:</label>
              <input type="file" name="new_receipt" class="w-100 mb-2" id="file">
            </div>

            <input type="hidden" name="selected_id" value="' . $row['id'] . '">
          
            <div class="col-12 mt-3">
              <input type="submit" id="update_trans" name="update_trans" value="Update Transaction" class="w-100 text-dark bg-warning border-0" style="font-weight:600" onclick="editTransaction(event)">
            </div>
          </form>
        </div>
      ';
    }
  }

  echo $output;
?>