<?php
  // Start a session
  session_start();

  // Define some constants in the project
  define("SITEURL", "http://localhost/fxtrade/");
  define("DB_HOST", "localhost");
  define("DB_USERNAME", "root");
  define("DB_PASSWORD", "");
  define("DB_NAME", "trading");
  define("EMAIL", "fxtraderecord36@gmail.com");
  define("EMAILPASS", "FX_trade123");

  date_default_timezone_set('Africa/Lagos');

  // Connection to database
  $conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD);
   $db_select = mysqli_select_db($conn, DB_NAME);

   if($conn == "false"){
     echo "Error in connection";
   }



  function fxTotal($acct_id, $conn){
    // Get the credits amount in wallet
    $wallet_deposits = mysqli_query($conn, "SELECT SUM(amount) AS wallet_deposits FROM transfers WHERE acct_id=$acct_id AND trans_type='Deposit'");
    while($wallet_depo = mysqli_fetch_assoc($wallet_deposits)){
       $total_depo = $wallet_depo['wallet_deposits'];
    }

    // Get the credits amount in wallet
    $wallet_trans_in = mysqli_query($conn, "SELECT SUM(amount) AS wallet_trans_in FROM transfers WHERE acct_id=$acct_id AND trans_type='Trans_in'");
    while($wallet_row_in = mysqli_fetch_assoc($wallet_trans_in)){
       $total_trans_in = $wallet_row_in['wallet_trans_in'];
    }

    // Get the debits amount in wallet
    $wallet_withdraws = mysqli_query($conn, "SELECT SUM(amount) AS wallet_withdraws FROM transfers WHERE acct_id=$acct_id AND trans_type='Withdrawal'");
    while($wallet_wit = mysqli_fetch_assoc($wallet_withdraws)){
       $total_wit = $wallet_wit['wallet_withdraws'];
    }
    // Get the debits amount in wallet
    $wallet_trans_out = mysqli_query($conn, "SELECT SUM(amount) AS wallet_trans_out FROM transfers WHERE acct_id=$acct_id AND trans_type='Trans_out'");
    while($wallet_row_out = mysqli_fetch_assoc($wallet_trans_out)){
       $total_trans_out = $wallet_row_out['wallet_trans_out'];
    }


    $wallet_bal = $total_depo + $total_trans_in - $total_wit - $total_trans_out;
    echo number_format($wallet_bal, 2, ".", ",");
 };

?>