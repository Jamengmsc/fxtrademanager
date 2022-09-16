<?php
  include "../config/constants.php";
  include "../config/session.php";

  if(isset($_GET['acct_no'])){
    $acct_no = $_GET['acct_no'];

    // Get broker for selected account
    $broker = mysqli_query($conn, "SELECT broker FROM new_account WHERE acct_no='$acct_no'");
    echo $broker_name = mysqli_fetch_assoc($broker)['broker'];
  }