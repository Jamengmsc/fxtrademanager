<?php
  include "../config/constants.php";
  include "../config/session.php";

  if(isset($_GET['acct_id'])){
    $acct_id = $_GET['acct_id'];

    $output = "";

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


  $wallet_amount = $total_depo + $total_trans_in - $total_wit - $total_trans_out;


    // Get Account No. and currency
    $get_acct = mysqli_query($conn, "SELECT acct_no FROM record_acct WHERE id=$acct_id");
    $acct_no = mysqli_fetch_assoc($get_acct)['acct_no'];

    // Get full account details from new_account table in database
    $acct_details = mysqli_query($conn, "SELECT * FROM new_account WHERE acct_no=$acct_no");
    $row = mysqli_fetch_assoc($acct_details);



    // Get the total profit for this account
    $sum_profit = mysqli_query($conn, "SELECT SUM(profit) AS total FROM records WHERE user_id=$id AND acct_id=$acct_id");

    while($prof_row = mysqli_fetch_assoc($sum_profit)){
        $total_profit = $prof_row['total'];
    }

    // Trading Account Balance
    $trading_acct_bal = $row['balance'] + $total_profit;

    $output = "
        <option value='" . $wallet_amount . "'>FxTrade Wallet - " . $row['currency'] . number_format($wallet_amount, 2, ".", ",") . "</option>

        <option value='" . $acct_id . "'>Trading Account: " . $row['acct_no'] . " - " . $row['currency'] . number_format($trading_acct_bal, 2, ".", ",") . "</option>
    ";
    
    echo $output;
  }

?>