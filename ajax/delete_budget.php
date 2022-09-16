<?php
  include "../config/constants.php";

  $data = $_GET['sel_items'];
  $sel_items = explode(",", $data);

  // LOOP THROUGH THE SELECTED IDS AND DELETE ITEMS
  foreach($sel_items as $sel_id){
    // Delete transaction item
    $del_trans = "DELETE FROM budgets WHERE id=$sel_id";
    $del_trans_res = mysqli_query($conn, $del_trans);

    if($del_trans_res == false){
      $_SESSION['del-budget-failed'] = "Failed to delete budget items";
    }
    else{
      $_SESSION['del-budget-success'] = "Budget Items Successfully Deleted";
    }
  }

?>