<?php
   $caption = "Financial Budgets";

   include "partials/header.php";
   include "config/check-login.php";
?>

<!-- Main section -->
<section class="container-fluid px-md-5">
  <div class="d-flex justify-content-between align-items-center">
    <div class="d-flex flex-column">
      <div class="home-icon d-flex justify-content-start align-items-center">
          <i class="fa fa-credit-card" style="font-size: 18px; color: #e7781c;"></i>
          <span class="ml-2" style="font-size: 16px; color: #e7781c;">Budgets</span>
      </div>
    </div>

    <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
      <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

      <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
    </div>
  </div>

  <hr class="m-0 my-2">
</section>

<style>
  .plan_name{
    color:#FFC107;
    font-size:16px;
    font-weight:500;
  }
</style>

<section class="mb-4 mx-auto" style="max-width:400px">
  <div class="container-fluid px-md-3">
    <h6 class="m-0 text-dark mt-2 text-uppercase font-weight-bold">Financial <span class="text-secondary font-italic">Budgets</span></h6>
    <p class="mb-3 text-secondary" style="font-size:10px; color: #e7781c;">(Monthly budgets showing budgeted and actual price amount with cummulative annual total)</p>
  </div>

  <!-- Confirmations -->
  <div class="confirmations">
    <?php
      if(isset($_SESSION['budget-success'])){
        echo "<div class='alert alert-success alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['budget-success'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
        unset($_SESSION['budget-success']);
      }

      if(isset($_SESSION['budget-failed'])){
        echo "<div class='alert alert-danger alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['budget-failed'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
        unset($_SESSION['budget-failed']);
      }

      if(isset($_SESSION['budg-update-success'])){
        echo "<div class='alert alert-success alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['budg-update-success'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
        unset($_SESSION['budg-update-success']);
      }

      if(isset($_SESSION['budg-update-failed'])){
        echo "<div class='alert alert-danger alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['budg-update-failed'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
        unset($_SESSION['budg-update-failed']);
      }

      if(isset($_SESSION['del-budget-success'])){
        echo "<div class='alert alert-success alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['del-budget-success'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
        unset($_SESSION['del-budget-success']);
      }

      if(isset($_SESSION['del-budget-failed'])){
        echo "<div class='alert alert-danger alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['del-budget-failed'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
        unset($_SESSION['del-budget-failed']);
      }
    ?>
  </div>


  <!-- PLAN TABLE OF DETAILS -->
  <div class="saving_table budget">
    <div class="container-fluid px-md-3 btn_container">
      <!-- BUTTONS -->
      <div class="trans">
        <h6 class="m-0 text-dark" style="font-weight:500">Budgets Details</h6>

        <div class="div">
          <button type="button" id="edit_budget_item" onclick="editBudgetItem(event)">Edit</button>

          <button type="button" id="delete_budget_item" onclick="deleteBudgetItem(event)">Delete</button>

          <button type="button" onclick="newBudgetItem(event)">New</button>
        </div>
      </div>
    </div>

    <div style="overflow-x:auto">
      <table class="table-sm">
        <tr>
          <th class="px-2"><input type="checkbox" id="sel_all"></th>
          <th class="p-0 text-left">BUDGET <span class="font-italic">Description</span></th>
          <th class="px-2 text-info" style="font-weight:600">BUDGET</th>
          <th class="px-2 text-warning font-italic">ACTUAL</th>
          <th class="px-2">AMOUNT <br> <span class="font-italic text-gray">Difference</span></th>
        </tr>

        <!-- Loop start -->
        <?php
          $this_month = date("m");
          $this_year = date("Y");

          $getTrans = mysqli_query($conn, "SELECT DISTINCT MONTH(date_added) AS month, YEAR(date_added) AS year FROM budgets WHERE user_id=$id GROUP BY month ORDER BY month DESC");

          if(mysqli_num_rows($getTrans) > 0){
            while($month_row = mysqli_fetch_assoc($getTrans)){
              $month = $month_row['month'];
              $the_month = date("F", mktime(0, 0, 0, $month, 10));
  
              $year = $month_row['year'];
  
              ?>
                <tr class="saving_month bg-light">
                  <td colspan="5" class="text-dark font-weight-bold text-uppercase py-2" style="color: #e7781c;">
                    <?php echo "<span class='font-italic' style='color: #e7781c;'>" . $the_month . " " . $year . "</span> Budget" ?>
                  </td>
                </tr>
              <?php
  
  
              // INCOME ITEMS
              $getMonthTrans = mysqli_query($conn, "SELECT * FROM budgets WHERE MONTH(date_added) = $month AND YEAR(date_added) = $year AND user_id=$id AND income=1 ORDER BY date_added DESC");
  
              ?>
                <tr class="inc_exp bg-light">
                  <td></td>
                  <td class="text-left p-0">INCOME</td>
                  <td class="font-weight-normal">Estimated</td>
                  <td class="font-weight-normal">Actual</td>
                  <td class="font-weight-normal pr-2">Difference</td>
                </tr>
              <?php
  
              if(mysqli_num_rows($getMonthTrans) > 0){
                while($trans_row = mysqli_fetch_assoc($getMonthTrans)){
                  $item_id = $trans_row['id'];
    
                  ?>
                    <tr>
                      <td class="pl-2">
                        <div class="text-center"><input type="checkbox" class="checked_items" name="sel_items[]" value="<?= $trans_row['id'] ?>">
                        </div>
                      </td>
    
                      <!-- Budgeted Amount -->
                      <td class="text-dark text-left p-0">
                        <?= "<span style='font-weight:600'>" . $trans_row['description'] . "</span>" ?>
                      </td>
    
                      <!-- Actual expended amount -->
                      <td class="text-right"><?php echo number_format($trans_row['budget_amount'], 2, ".", ",") ?></td>
    
                      <!-- Difference betweeen budgeted and actual amount -->
                      <td style="color:gray;font-style:italic; text-align:right">
                        <?php
                          if($trans_row['actual_amount'] == 0){
                            echo "--";
                          }
                          else{
                            echo number_format($trans_row['actual_amount'], 2, ".", ",");
                          }
                        ?>
                      </td>
    
                      <td class="pr-2" style="font-weight:600; text-align:right">
                        <?php
                          $diff = mysqli_query($conn, "SELECT budget_amount, actual_amount FROM budgets WHERE id=$item_id AND income=1");
                          if(mysqli_num_rows($diff) == 1){
                            $inc_row = mysqli_fetch_assoc($diff);
    
                            if($inc_row['actual_amount'] == 0){
                              echo "--";
                            }
                            else{
                              $budget_diff = $inc_row['actual_amount'] - $inc_row['budget_amount'];
                              
                              if($budget_diff >= 0){
                                echo number_format($budget_diff, 2, ".", ",");
                              }
                              else{
                                echo "<div style='color:#e7781c' class='font-italic'>(" . number_format(-1 * $budget_diff, 2, ".", ",") . ")</div>";
                              }
                            }
                          }
                        ?>
                      </td>
                    </tr>
                  <?php
                }
              }
              else{
                ?>
                  <tr><td colspan="5" class="text-center text-danger font-italic font-weight-bold">-- No income record found --</td></tr>
                <?php
              }
  
              // TOTAL INCOME
              ?>
                <tr class="inc_exp_total bg-dark">
                  <td></td>
                  <td class="text-left text-light font-weight-bold py-2">TOTAL INCOME =</td>
                  <td class="font-weight-bold text-right text-light">
                    <?php
                      // Get SUM of total deposited amount for a month
                      $budg_income = mysqli_query($conn, "SELECT SUM(budget_amount) AS budget_income FROM budgets WHERE MONTH(date_added) = $month AND YEAR(date_added) = $year AND user_id=$id AND income=1");
  
                      while($budg_inc_row = mysqli_fetch_assoc($budg_income)){
                        $total_budg_income = $budg_inc_row['budget_income'];
                      }
  
                      echo number_format($total_budg_income, 2, ".", ",");
                    ?>
                  </td>
  
                  <td class="font-weight-bold text-light text-right">
                    <?php
                      // Get SUM of total deposited amount for a month
                      $act_income = mysqli_query($conn, "SELECT SUM(actual_amount) AS actual_income FROM budgets WHERE MONTH(date_added) = $month AND YEAR(date_added) = $year AND user_id=$id AND income=1");
  
                      while($act_inc_row = mysqli_fetch_assoc($act_income)){
                        $total_act_income = $act_inc_row['actual_income'];
                      }
  
                      if($total_act_income == 0){
                        echo "--";
                      }
                      else{
                        echo number_format($total_act_income, 2, ".", ",");
                      }
                    ?>
                  </td>
  
                  <td class="text-right pr-2">
                    <?php
                      $inc_diff = mysqli_query($conn, "SELECT SUM(budget_amount) AS diff_inc FROM budgets WHERE MONTH(date_added) = $month AND YEAR(date_added) = $year AND user_id=$id AND actual_amount='0.00' AND income=1");
  
                      while($inc_budg_row = mysqli_fetch_assoc($inc_diff)){
                        $total_inc_budget = $inc_budg_row['diff_inc'];
                      }
                      // echo $total_new_budget;
                      $total_inc_diff = $total_act_income - $total_budg_income + $total_inc_budget;
  
                      if($total_inc_diff == 0){
                        echo "--";
                      }
                      elseif($total_inc_diff < 0){
                        echo "<div class='text-warning font-italic'>(" . number_format(-1 * $total_inc_diff, 2, ".", ",") . ")</div>";
                      }
                      else{
                        echo "<div class='text-light'>". number_format($total_inc_diff, 2, ".", ",") . "</div>";
                      }
                    ?>
                  </td>
                </tr>
  
                <tr class="bg-light border-0"><td colspan="5" style="padding:2px"></td></tr>
              <?php
  
  
  

              // EXPENSES ITEMS
              $getMonthTrans = mysqli_query($conn, "SELECT * FROM budgets WHERE MONTH(date_added) = $month AND YEAR(date_added) = $year AND user_id=$id AND income=0 ORDER BY date_added DESC");
  
              ?>
                <tr class="inc_exp bg-light" style="border-top:none"><td></td><td colspan="8" class="text-left px-0 pt-2">BUDGET EXPENSES</td></tr>
              <?php
  
              if(mysqli_num_rows($getMonthTrans) > 0){
                while($trans_row = mysqli_fetch_assoc($getMonthTrans)){
                  $item_id = $trans_row['id'];
    
                  $week_day = date("l", strtotime($trans_row['date_added']));
                  $week_day = substr($week_day, 0, 3); // Truncate to Sun, Mon, Tues etc
    
                  ?>
                    <tr>
                      <td class="pl-2">
                        <div class="text-center"><input type="checkbox" class="checked_items" name="sel_items[]" value="<?= $trans_row['id'] ?>">
                        </div>
                      </td>
    
                      <!-- Budgeted Amount -->
                      <td class="text-dark text-left p-0">
                        <?= "<span style='font-weight:600'>" . $trans_row['description'] . "</span>" ?>
                      </td>
    
                      <!-- Actual expended amount -->
                      <td class="text-right"><?php echo number_format($trans_row['budget_amount'], 2, ".", ",") ?></td>
    
                      <!-- Difference betweeen budgeted and actual amount -->
                      <td style="color:gray;font-style:italic;text-align:right">
                        <?php
                          if($trans_row['actual_amount'] == 0){
                            echo "--";
                          }
                          else{
                            echo number_format($trans_row['actual_amount'], 2, ".", ",");
                          }
                        ?>
                      </td>
    
                      <td class="pr-2" style="font-weight:600;text-align:right">
                        <?php
                          $diff = mysqli_query($conn, "SELECT budget_amount, actual_amount FROM budgets WHERE id=$item_id");
                          if(mysqli_num_rows($diff) == 1){
                            $item_row = mysqli_fetch_assoc($diff);
    
                            if($item_row['actual_amount'] == 0){
                              echo "--";
                            }
                            else{
                              $budget_diff = $item_row['budget_amount'] - $item_row['actual_amount'];
                              
                              if($item_row['actual_amount'] > $item_row['budget_amount']){
                                echo "<div style='color:#e7781c' class='font-italic'>(" . number_format(-1 * $budget_diff, 2, ".", ",") . ")</div>";
                              }
                              else{
                                echo number_format($budget_diff, 2, ".", ",");
                              }
                            }
                          }
                        ?>
                      </td>
                    </tr>
                  <?php
                }
              }
              else{
                ?>
                  <tr><td colspan="5" class="text-center text-danger font-italic font-weight-bold">-- No expense record found --</td></tr>
                <?php
              }
  
              // TOTAL EXPENSES
              ?>
                <tr class="inc_exp_total bg-dark">
                  <td></td>
                  <td class="text-left text-light font-weight-bold py-2">TOTAL EXPENSES =</td>
                  <td class="font-weight-bold text-right text-light">
                    <?php
                      // Get SUM of total deposited amount for a month
                      $expense = mysqli_query($conn, "SELECT SUM(budget_amount) AS expense_total FROM budgets WHERE MONTH(date_added) = $month AND YEAR(date_added) = $year AND user_id=$id AND income=0");
  
                      while($dep_row = mysqli_fetch_assoc($expense)){
                        $total_budg_expense = $dep_row['expense_total'];
                      }
  
                      echo number_format($total_budg_expense, 2, ".", ",");
                    ?>
                  </td>
                  
                  <td class="font-weight-bold text-light text-right">
                    <?php
                      // Get SUM of total deposited amount for a month
                      $act_expense = mysqli_query($conn, "SELECT SUM(actual_amount) AS actual_expense FROM budgets WHERE MONTH(date_added) = $month AND YEAR(date_added) = $year AND user_id=$id AND income=0");
  
                      while($act_exp_row = mysqli_fetch_assoc($act_expense)){
                        $total_act_expense = $act_exp_row['actual_expense'];
                      }
  
                      if($total_act_expense == 0){
                        echo "--";
                      }
                      else{
                        echo number_format($total_act_expense, 2, ".", ",");
                      }
                    ?>
                  </td>
  
                  <td class="text-right pr-2">
                    <?php
                      $exp_diff = mysqli_query($conn, "SELECT SUM(budget_amount) AS diff_exp FROM budgets WHERE MONTH(date_added) = $month AND YEAR(date_added) = $year AND user_id=$id AND actual_amount='0.00' AND income=0");
  
                      while($exp_budg_row = mysqli_fetch_assoc($exp_diff)){
                        $total_exp_budget = $exp_budg_row['diff_exp'];
                      }
                      // echo $total_new_budget;
                      $total_exp_diff = $total_budg_expense - $total_act_expense - $total_exp_budget;
  
                      if($total_exp_diff == 0){
                        echo "--";
                      }
                      elseif($total_exp_diff < 0){
                        echo "<div class='text-warning font-italic'>(" . number_format(-1 * $total_exp_diff, 2, ".", ",") . ")</div>";
                      }
                      else{
                        echo "<div class='text-light'>" . number_format($total_exp_diff, 2, ".", ",") . "</div>";
                      }
                    ?>
                  </td>
                </tr>
              <?php
  
  
              // MONTHLY SUMMARY
              ?>
                <tr class="month_total border-0">
                  <td colspan="5" class="text-uppercase"><p class="mt-1 text-info" style="text-decoration:underline;">SUMMARY FOR MONTH OF <?php echo $the_month . " " . $year ?></p></td>
                </tr>

                <tr class="month_total border-0">
                  <td colspan="2" class="text-left text-secondary"><p class="pr-2 pl-4 font-italic" style="font-size:11px; font-weight:500">Opening Balance:</p></td>
                  <td class="text-right"><span class="font-weight-bold">0.00</span></td>
                  <td colspan="2"></td>
                </tr>

                <tr class="month_total border-0">
                  <td colspan="2" class="text-left text-secondary"><p class="pr-2 pl-4 font-italic" style="font-size:11px;font-weight:500">Actual Balance:</p></td>
                  <td class="text-right">
                    <span class="font-weight-bold text-dark">
                      <?php
                        $month_act_total = $total_act_income - $total_act_expense;
                        echo number_format($month_act_total, 2, ".", ",");
                      ?>
                    </span>
                  </td>
                  <td colspan="2"></td>
                </tr>

                <tr class="month_total border-0">
                  <td colspan="2" class="text-left text-secondary"><p class="pr-2 pl-4 font-italic" style="font-size:11px;font-weight:500">Estimated Balance:</p></td>
                  <td class="text-right">
                    <span class="font-weight-bold">
                      <?php
                        // Estimate Income where actual income has been enteredd
                        $estimate_income = mysqli_query($conn, "SELECT SUM(budget_amount) AS inc_est_total FROM budgets WHERE MONTH(date_added) = $month AND YEAR(date_added) = $year AND user_id=$id AND income=1 AND actual_amount>0");
  
                        while($inc_est_row = mysqli_fetch_assoc($estimate_income)){
                          $total_est_income = $inc_est_row['inc_est_total'];
                        }

                        // Estimate Expense where actual income has been enteredd
                        $estimate_expense = mysqli_query($conn, "SELECT SUM(budget_amount) AS exp_est_total FROM budgets WHERE MONTH(date_added) = $month AND YEAR(date_added) = $year AND user_id=$id AND income=0 AND actual_amount>0");
  
                        while($exp_est_row = mysqli_fetch_assoc($estimate_expense)){
                          $total_est_expense = $exp_est_row['exp_est_total'];
                        }

                        $month_total = $total_est_income - $total_est_expense;
                        echo number_format($month_total, 2, ".", ",");
                      ?>
                    </span>
                  </td>
                  <td colspan="2"></td>
                </tr>

                <tr class="month_total border-0">
                  <td colspan="2" class="text-left text-secondary"><p class="pr-2 pl-4 font-italic" style="font-size:11px;font-weight:500">Budget gained by:</p></td>
                  <td class="text-right">
                    <span class="font-weight-bold">
                      <?php
                        $month_total_diff = $total_exp_diff + $total_inc_diff;
    
                        if($month_total_diff < 0){
                          echo "<div class='font-italic' style='color:#e7781c'>(" . number_format(-1 * $month_total_diff, 2, ".", ",") . ")</div>";
                        }
                        else{
                          echo number_format($month_total_diff, 2, ".", ",");
                        }
                      ?>
                    </span>
                  </td>
                  <td colspan="2"></td>
                </tr>

                <tr class="month_total last border-0">
                  <td colspan="2" class="text-left text-secondary"><p class="pr-2 pl-4 font-italic" style="font-size:11px;font-weight:500">Closing Balance:</p></td>
                  <td class="text-right">
                    <span class="font-weight-bold">
                      <?= number_format($month_act_total, 2, ".", ","); ?>
                    </span>
                  </td>
                  <td colspan="2"></td>
                </tr>
              <?php
            }
          }
          else{
            ?>
              <tr><td colspan="5" class="text-center text-secondary font-italic font-weight-bold py-4">Click on the <span class="text-info" style="font-style:normal">New</span> button to begin to record your budgets...</td></tr>
            <?php
          }    
        ?>
        <!-- Loop ends -->

      </table>
    </div>
  </div>
</section>


<!-- New Budget Item Form -->
<div class="popup_new_budget">
  <div id="new_budget" class="p-3">
    <div id="" class="bg-light p-1 pb-2 rounded-lg">
      <form method="post" class="new_budget" autocomplete="off">
        <div class="d-flex justify-content-between align-items-center">
          <h6 class="m-0 p-3 pb-2" style="font-size:18px;color:#e7781c;">New Budget Item</h6>

          <i class="fa fa-times mr-3" style="font-size:20px;color:#e7781c;cursor:pointer"></i>
        </div>

        <div class="col-12">
          <label for="amount">Item Description:</label>
          <input type="text" name="item_desc" class="w-100 mb-2" id="trans_desc" placeholder="Description...">
        </div>

        <div class="col-12">
          <label for="amount">Budget Amount:</label>
          <input type="text" name="budg_amount" class="w-100 mb-2" id="budg_amount" placeholder="Budgeted Amount...">
        </div>

        <div class="row px-3 mt-1 mb-0">
          <div class="col-12 d-flex justify-content-end align-items-center">
            <input type="checkbox" name="income_amount" class="m-0" id="income">
            <span class="text-dark font-italic ml-1" style="font-size:13px; font-weight:600">Income Type</span>
          </div>
        </div>
      
        <div class="col-12 mt-2">
          <input type="submit" id="add_budget" name="save_item" value="Save Budget Item" class="w-100 text-dark bg-warning border-0" style="font-weight:600" onclick="saveBudgetItem(event)">
        </div>
      </form>
    </div>
   </div>
</div>


<!-- Edit Transaction Form -->
<div class="popup_edit_budget">
  <div id="edit_budget" class="p-3"></div>

  <?php
    if(isset($_POST['update_budget'])){
      $budget_id = mysqli_real_escape_string($conn, $_POST['budget_id']);
      $item_desc = mysqli_real_escape_string($conn, $_POST['item_desc']);
      $budget_amount = mysqli_real_escape_string($conn, $_POST['budg_amount']);
      $act_amount = mysqli_real_escape_string($conn, $_POST['act_amount']);

      if(isset($_POST['income_amount'])){
        $income = 1;
      }
      else{
        $income = 0;
      }


    //  Update Transaction to DB
    // Check for empty transaction type and amount form values
    if(empty($item_desc) || empty($budget_amount)){
      $_SESSION['budg-update-failed'] = "<div class='error'>Item description/bugeted amount has been cleared</div>";
      echo "<script>location.href='budgets.php'</script>";
      die();
    }
    else{
      if($act_amount == ""){
        $act_amount = 0;
      }
      else{
        $act_amount = $act_amount;
      }

      // Query DB to save budget item
      $updateBudget = "UPDATE budgets SET
        user_id = $id,
        description = '$item_desc', 
        budget_amount = '$budget_amount',
        actual_amount = '$act_amount',
        income = $income

        WHERE id=$budget_id
      ";

        $updateBudget_res = mysqli_query($conn, $updateBudget);

        if($updateBudget_res == true){
          $_SESSION['budg-update-success'] = "<div class='error'>Budget Item Updated Successful</div>";
          echo "<script>location.href='budgets.php'</script>";
        }
      }
    }
  ?>
</div>


<!-- Delete Transaction -->
<div class="popup_budget_del">
   <div id="budget_del" class="p-3">
      <h5 class="font-weight-normal" style="color:#000">Delete Budget Item</h5>
      <p class="mt-1" style="color:rgba(0,0,0,0.8); line-height:20px;">Are you sure you want to delete this item? You will lost the item permanently. Continue?</p>

      <div id="popup_btn">
         <button id="cancel_budg_del">No</button>
         <button id="ok_budg_del">Yes, Delete</button>
      </div>
   </div>
</div>


<script src="./script.js"></script>
</body>
</html>



<script> 
  var transDesc = document.getElementById("trans_desc");
    transDesc.addEventListener("input", function(){
      transDesc.value = transDesc.value.substr(0, 30);
    });


  // SCRIPT TO SELECT MULTIPLE CHECKBOXES TO DELETE OR GENERATE SALES INVOICE
  var selectAll = document.getElementById("sel_all");
   var checkboxes = document.getElementsByClassName("checked_items");
   var editSavingItem = document.querySelector("#edit_budget_item"),
       deleteSavingItem = document.querySelector("#delete_budget_item");

   var checkedList = [];

    for(var checkbox of checkboxes){
      checkbox.addEventListener("click", function(){
        if(this.checked == true){
          checkedList.push(this.value);

          if(checkedList.length > 0){
            editSavingItem.classList.add("active");
            deleteSavingItem.classList.add("active");

            deleteSavingItem.innerHTML = "Delete" + " <span class='font-italic' style='font-size:12px'>(" + checkedList.length + ")</span>";
          }

          if(checkedList.length > 1){
            editSavingItem.classList.remove("active");
          }          

          // CHECK IF TOTAL CHECKED CHECKBOXES = THE TOTAL CHECKBOX ELEMENTS
          if(checkedList.length === checkboxes.length){
              selectAll.checked = true;
              deleteSavingItem.innerHTML = "Delete All";
          }
        }
        else{ // if the element is unchecked, remove it from the checked list array
          checkedList.pop(this.value);

          

          if(checkedList.length < 1){
            editSavingItem.classList.remove("active");
            deleteSavingItem.classList.remove("active");
          }

          if(checkedList.length == 1){
            editSavingItem.classList.add("active");
          }

          if(checkedList.length < checkboxes.length){
              selectAll.checked = false;
              deleteSavingItem.innerHTML = "Delete" + " <span class='font-italic' style='font-size:12px'>(" + checkedList.length + ")</span>";
          }
        }
      })
    }


   // SELECT ALL CHECKBOXES
   selectAll.onclick = () => {
    for (let i = 0; i < checkboxes.length; i++){
      if(selectAll.checked){
          checkboxes[i].checked = true;
          checkedList.length = checkboxes.length;

          deleteSavingItem.classList.add("active");
          editSavingItem.classList.remove("active");
          deleteSavingItem.innerHTML = "Delete All";
      }
      else{
          checkboxes[i].checked = false;
          checkedList.length = 0;

          editSavingItem.classList.remove("active");
          deleteSavingItem.classList.remove("active");

          deleteSavingItem.innerHTML = "Delete";
      }
    }
   }


  // Open Edit Savings Dialog Box
  function editBudgetItem(event){
    event.preventDefault();

    // Open background and dialog box
    var editBudgetPopup = document.querySelector(".popup_edit_budget");
        editBudgetPopup.classList.add("active");

    var editBudget = document.getElementById("edit_budget");
        editBudget.classList.add("active");


    // Get ID of selected transaction itema and display edit form for the item
    var checkedItems = [];
    for(i = 0; i < checkboxes.length; i++){
      if(checkboxes[i].checked == true){
        checkedItems.push(checkboxes[i].value);
      }
    }
    
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "./ajax/edit_budget_item.php?sel_id=" + checkedItems, true);
    xhr.onload = function(){
      if(this.status === 200){
        document.getElementById("edit_budget").innerHTML = this.responseText;

        // Close Dialog Box to edit savings transaction
        document.querySelector(".edit_budget .fa-times").addEventListener("click", function(){
          editBudgetPopup.classList.remove("active");
          editBudget.classList.remove("active");
        })
      }
    }
    
    xhr.send();
  }


  // Delete transaction
  function deleteBudgetItem(event){
    event.preventDefault();

    // Open background and dialog box
    var delBudgetDialog = document.querySelector(".popup_budget_del");
        delBudgetDialog.classList.add("active");

    var delBudget = document.getElementById("budget_del");
        delBudget.classList.add("active");


    // Cancel Delete
    var cancelBudgDelete = document.getElementById("cancel_budg_del");
        cancelBudgDelete.onclick = () => {
        delBudgetDialog.classList.remove("active");
        delBudget.classList.remove("active");
      }


    // Confirm delete
    document.getElementById("ok_budg_del").addEventListener("click", function(){
      var checkedItems = [];

      for(i = 0; i < checkboxes.length; i++){
        if(checkboxes[i].checked == true){
            checkedItems.push(checkboxes[i].value);
        }
      }
      
      var xhr = new XMLHttpRequest();
        xhr.open("GET", "./ajax/delete_budget.php?sel_items=" + checkedItems, true);
        xhr.onload = function(){
          if(this.status === 200){
            location.href = "budgets.php";                    
          }
        }
        
        xhr.send();

        delBudgetDialog.classList.remove("active");
        delBudget.classList.remove("active");
    });
  }


  // Save Transaction
  function saveBudgetItem(event){
    event.preventDefault();

    var transForm = document.querySelector(".new_budget");
    
    var xhr = new XMLHttpRequest();
    xhr.onload = function(){
      if(this.status == 200){
        location.href = "budgets.php";
      }
    }
    xhr.open("POST", "ajax/save_budget_item.php", true);

    var formData = new FormData(transForm);
    xhr.send(formData);
  }


   // Open New Savings Dialog Box
   function newBudgetItem(event){
    event.preventDefault();

    // Open background and dialog box
    var newBudgetPopup = document.querySelector(".popup_new_budget");
        newBudgetPopup.classList.add("active");

    var newBudget = document.getElementById("new_budget");
        newBudget.classList.add("active");

    // Close Dialog Box
    document.querySelector(".new_budget .fa-times").addEventListener("click", function(){
      newBudgetPopup.classList.remove("active");
      newBudget.classList.remove("active");
    });
  }

</script>

