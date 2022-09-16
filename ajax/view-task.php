<?php
  include "../config/constants.php";

  if(isset($_GET['id'])){
    $task_id = $_GET['id'];

    $upd_task = "UPDATE tasks SET
      viewed = 1

      WHERE id=$task_id
    ";

    $upd_task_res = mysqli_query($conn, $upd_task);
  }