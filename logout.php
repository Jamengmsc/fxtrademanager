<?php
  include "config/constants.php";

  // Destroy user logged in ID session
  session_unset();
  session_destroy();

  header("location:" . SITEURL . "login.php");
?>