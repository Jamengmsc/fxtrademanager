<?php
  include "../config/constants.php";

  $sel_id = $_GET['sel_id'];
  $output = "";

  $res = mysqli_query($conn, "SELECT * FROM budgets WHERE id=$sel_id");
  if(mysqli_num_rows($res) == 1){
    $row = mysqli_fetch_assoc($res);

    if($row['income'] == 1){
      $output = '
        <div class="bg-light p-1 pb-2 rounded-lg">
          <form method="post" class="edit_budget" autocomplete="off" enctype="multipart/form-data">
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="m-0 p-3 pb-2" style="font-size:18px;color:#e7781c;">Edit Income Item</h6>

              <i class="fa fa-times mr-3" style="font-size:20px;color:#e7781c;cursor:pointer"></i>
            </div>

            <div class="col-12">
              <label for="description">Item Description:</label>
              <input type="text" name="item_desc" class="w-100 mb-2" id="trans_desc" value="' . $row['description'] . '">
            </div>

            <div class="d-flex justify-content-between align-items-start">
              <div class="col-6">
                <label for="amount">Exp. Income:</label>
                <input type="text" name="budg_amount" class="w-100 mb-2" id="amount" value="' . $row['budget_amount'] . '">
              </div>

              <div class="col-6">
                <label for="amount">Actual Income:</label>
                <input type="text" name="act_amount" class="w-100 mb-2" id="amount" value="' . $row['actual_amount'] . '">
              </div>
            </div>

            <div class="row px-3 mt-1 mb-0">
              <div class="col-12 d-flex justify-content-end align-items-center">
                <input type="checkbox" name="income_amount" class="m-0" id="income" checked>
                <span class="text-dark font-italic ml-1" style="font-size:13px; font-weight:600">Income Type</span>
              </div>
            </div>

            <input type="hidden" name="budget_id" value="' . $row['id'] . '">
          
            <div class="col-12 mt-2">
              <input type="submit" id="update_budget" name="update_budget" value="Update Income Item" class="w-100 text-dark bg-warning border-0" style="font-weight:600" onclick="editBudget(event)">
            </div>
          </form>
        </div>
      ';
    }

    else{
      $output = '
        <div class="bg-light p-1 pb-2 rounded-lg">
          <form method="post" class="edit_budget" autocomplete="off" enctype="multipart/form-data">
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="m-0 p-3 pb-2" style="font-size:18px;color:#e7781c;">Edit Budget Item</h6>

              <i class="fa fa-times mr-3" style="font-size:20px;color:#e7781c;cursor:pointer"></i>
            </div>

            <div class="col-12">
              <label for="description">Item Description:</label>
              <input type="text" name="item_desc" class="w-100 mb-2" id="trans_desc" value="' . $row['description'] . '">
            </div>

            <div class="d-flex justify-content-between align-items-start">
              <div class="col-6">
                <label for="amount">Budget Amt:</label>
                <input type="text" name="budg_amount" class="w-100 mb-2" id="amount" value="' . $row['budget_amount'] . '">
              </div>

              <div class="col-6">
                <label for="amount">Actual Amt:</label>
                <input type="text" name="act_amount" class="w-100 mb-2" id="amount" value="' . $row['actual_amount'] . '">
              </div>
            </div>

            <div class="row px-3 mt-1 mb-0">
              <div class="col-12 d-flex justify-content-end align-items-center">
                <input type="checkbox" name="income_amount" class="m-0" id="income">
                <span class="text-dark font-italic ml-1" style="font-size:13px; font-weight:600">Income Type</span>
              </div>
            </div>

            <input type="hidden" name="budget_id" value="' . $row['id'] . '">
          
            <div class="col-12 mt-2">
              <input type="submit" id="update_budget" name="update_budget" value="Update Budget Item" class="w-100 text-dark bg-warning border-0" style="font-weight:600" onclick="editBudget(event)">
            </div>
          </form>
        </div>
    ';
    }

    
  }

  echo $output;
?>