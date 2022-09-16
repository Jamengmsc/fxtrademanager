<?php
  include "../config/constants.php";

  if(isset($_GET['rec_id'])){
    $rec_id = $_GET['rec_id'];

    // Delete record 
    $del_rec = mysqli_query($conn, "DELETE FROM records WHERE id=$rec_id");
    
    if($del_rec == false){
      echo "failed";
    }
    else{
      echo "deleted";
    }
  }
?>