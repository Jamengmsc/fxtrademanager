<?php
  include "../config/constants.php";
  include "../config/session.php";

  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $description = mysqli_real_escape_string($conn, $_POST['description']);
  $schedule = mysqli_real_escape_string($conn, $_POST['schedule']);
  $done = mysqli_real_escape_string($conn, $_POST['done']);

  if(isset($_POST['done'])){
    $status = 1;
  }
  else{
    $status = 0;
  }

    // Save Transaction to DB
    // Check for empty transaction type and amount form values
    if(empty($title) || empty($description) || empty($schedule)){
      $_SESSION['todo-failed'] = "<div class='error'>Incomplete ToDo item details!</div>";
      die();
    }
    else{
      $save_todo_item = "INSERT INTO tasks SET
        user_id = $id,
        title = '$title',
        description = '$description',
        task_date = '$schedule',
        status = $status
      ";

      $save_todo_item_res = mysqli_query($conn, $save_todo_item);

      if($save_todo_item_res == true){
        $_SESSION['todo-success'] = "<div class='error'>ToDo item successfully added</div>";
      }
    }
