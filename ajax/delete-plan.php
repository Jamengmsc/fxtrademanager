<?php
  include "../config/constants.php";
  include "../config/session.php";

  if(isset($_GET['plan_id'])){
    $plan_id = $_GET['plan_id'];


    // Delete plan items
    $delItems = mysqli_query($conn, "DELETE FROM compounding_items WHERE plan_id=$plan_id AND user_id=$id");
    
    if($delItems == true){
      // Delete main compoundin plan
      $delPlan = mysqli_query($conn, "DELETE FROM compounding WHERE id=$plan_id and user_id=$id");

      if($delPlan == true){
        // echo "success";
        $_SESSION['deleted-plan'] = "Deleted Successfully";
        // echo "<script>location.href='new_plan.php'</script>";
      }
      else{
        echo "Failed to delete plan";
      }
    }
  }
?>