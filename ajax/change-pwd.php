<?php
  include "../config/constants.php";
  include "../config/session.php";

  // Get form data
  // $user_id = mysqli_real_escape_string($conn, $_POST['pwd_id']);
  $curr_pwd = mysqli_real_escape_string($conn, $_POST['curr_pass']);
  $new_pwd = mysqli_real_escape_string($conn, $_POST['new_pass']);
  $confirm_pwd = mysqli_real_escape_string($conn, $_POST['retype_pass']);

  $currPwd = mysqli_query($conn, "SELECT password FROM user_reg WHERE id=$id");
  $pwd_row = mysqli_fetch_assoc($currPwd);
  $password = $pwd_row['password']; // Current login password

  // Check for empty input password
  if(empty($curr_pwd)){
    $_SESSION['change-pass'] = "Please, enter your current password";
  }
  else{
    if($curr_pwd !== $password){
      $_SESSION['change-pass'] = "Incorrect Password";
    }
    else{ // Check that new password is same as confirm new password
      if($new_pwd !== "" && $confirm_pwd !== ""){
        if($new_pwd !== $confirm_pwd){
          $_SESSION['change-pass'] = "Passwords do not match";
        }
        else{
          // Update user password
          $changePwd = "UPDATE user_reg SET password='$new_pwd' WHERE id=$id";
          $changePwdResult = mysqli_query($conn, $changePwd);
  
          if($changePwdResult == true){
            echo "success";
          }
          else{
            echo "failed";
          }
        }
      }
      else{
        $_SESSION['change-pass'] = "Enter new password";
      }
    }
  }
  
?>