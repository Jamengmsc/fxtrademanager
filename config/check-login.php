<?php
  if(!isset($_SESSION['id'])){
    // header("location: login.php");

    echo "<script>location.href='" . SITEURL . "login.php'</script>";
  }
?>