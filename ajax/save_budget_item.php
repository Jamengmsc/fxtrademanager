<?php
  include "../config/constants.php";
  include "../config/session.php";

  $item_desc = mysqli_real_escape_string($conn, $_POST['item_desc']);
  $budg_amount = mysqli_real_escape_string($conn, $_POST['budg_amount']);

  if(isset($_POST['income_amount'])){
    $income = 1;
  }
  else{
    $income = 0;
  }

    // Save Transaction to DB
    // Check for empty transaction type and amount form values
    if(empty($item_desc) || empty($budg_amount)){
      $_SESSION['budget-failed'] = "<div class='error'>Incomplete budget item details!</div>";
      die();
    }
    else{
      $save_budget_item = "INSERT INTO budgets SET
        user_id = $id,
        description = '$item_desc', 
        budget_amount = '$budg_amount',
        income = $income
      ";

      $save_budget_item_res = mysqli_query($conn, $save_budget_item);

      if($save_budget_item_res == true){
        $_SESSION['budget-success'] = "<div class='error'>Budget item successfully added</div>";
      }
    }
