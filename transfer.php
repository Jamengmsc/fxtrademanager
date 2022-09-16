<?php
   $caption = "Transfer Fund";

   include "partials/header.php";
   include "config/check-login.php";
?>

<!-- Main section -->
<section class="home mb-5">
   <div class="container-fluid px-md-5">
      <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex flex-column">
          <div class="home-icon d-flex justify-content-start align-items-center">
              <i class="fa fa-refresh" style="font-size: 18px; color: #e7781c;"></i>
              <span class="ml-2" style="font-size: 16px; color: #e7781c;">Transfer Funds</span>
          </div>
        </div>

        <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
          <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

          <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
         </div>
      </div>

      <hr class="mt-2 mb-4 br-secondary">
      <h6 class="text-dark my-2">Transfer funds between your Trading Accounts and your FX Wallet</h6>
      
      <!-- Get Live accounts details from database -->
      <?php
        $acct_details = mysqli_query($conn, "SELECT * FROM record_acct WHERE user_id=$id AND acct_type='Live'");     
      ?>

      <div class="bg-dark p-3 rounded-lg deposit_form">
        <form action="" method="POST" autocomplete="off">
          <!-- Select account to transfer funds -->
          <div class="row">
            <div class="col-12">
              <label for="acct_sel">Select Account &nbsp;<span class="text-gray font-italic small">(Live Accounts Only)</span></label>
              <select name="acct_sel" id="acct_sel" class="w-100 mb-4" onchange="loadTransAcct(event, this.value)">
                <option value="">-- Select Account --</option>

                <?php
                  if(mysqli_num_rows($acct_details) > 0){
                    while($row_from = mysqli_fetch_assoc($acct_details)){
                      ?>
                        <option value="<?= $row_from['id'] ?>"><?php echo "Trading Account: " . $row_from['acct_no'] ?></option>
                      <?php
                    }
                  }
                ?>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <label for="acct_from" class="mb-1">From</label>
              <select name="acct_from" id="load_acct" class="w-100">
              </select>
            </div>

            <div class="col-12">
              <label for="acct_to" class="mb-1">To</label>
              <select name="acct_to" id="load_acct2" class="w-100">
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <label for="amount">Amount</label>
              <input type="text" name="amount" class="w-100" placeholder="Transfer Amount..." numeric="0-9">
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <input type="submit" name="transfer" value="Transfer Funds" class="w-100 mt-3 bg-warning border-warning text-dark font-weight-bold">
            </div>
          </div>
        </form>

        <!-- Add form details to database -->
        <?php
          if(isset($_POST['transfer'])){
            $acct_sel = mysqli_real_escape_string($conn, $_POST['acct_sel']);
            $acct_from = mysqli_real_escape_string($conn, $_POST['acct_from']);
            $acct_to = mysqli_real_escape_string($conn, $_POST['acct_to']);
            $amount = mysqli_real_escape_string($conn, $_POST['amount']);
            $trans_date = date("Y-m-d");



            // Get the current trading account balance
            $get_acct_bal = mysqli_query($conn, "SELECT acct_no FROM record_acct WHERE id=$acct_sel");
            $acct_no = mysqli_fetch_assoc($get_acct_bal)['acct_no'];

            $balance = mysqli_query($conn, "SELECT balance FROM new_account WHERE acct_no=$acct_no");
            $acct_balance = mysqli_fetch_assoc($balance)['balance'];

            // Get the total profit for this account
            $sum_profit = mysqli_query($conn, "SELECT SUM(profit) AS total FROM records WHERE user_id=$id AND acct_id=$acct_sel");
              while($row = mysqli_fetch_assoc($sum_profit)){
                $total_profit = $row['total'];
              }

            // Current trading account balance
            $current_bal = $acct_balance + $total_profit;



            // Check the 2 selected accounts are the same
            if($acct_from == $acct_to){
              echo "<div class='text-warning font-italic small d-block mb-0 mt-2'>You are transferring between two same accounts</div>";
            }
            else{
              if($acct_from == $acct_sel){
                $trans_type = "Trans_in"; // Means, transferring into Wallet account

                if($amount !== ""){
                  // Now check if amount is more than available to transfer
                  if($amount <= $current_bal){
                    // Now, transfer or credit the wallet
                    $to_wallet = "INSERT INTO transfers SET
                      user_id=$id,
                      acct_id=$acct_from,
                      trans_type='$trans_type',
                      amount='$amount',
                      trans_date='$trans_date'
                    ";

                    $to_wallet_res = mysqli_query($conn, $to_wallet); //Query against the database

                    if($to_wallet_res == false){
                      echo "<div class='text-warning font-italic small d-block mb-0 mt-2'>Failed to transfer to your Fx Wallet</div>";
                    }
                    else{
                      // Debit Trading Account
                      $curr_bal = $acct_balance - $amount;

                      // Update Balance in the Trading Account. That is, balance in new account table in DB
                      $upd_bal = "UPDATE new_account SET
                        balance='$curr_bal'

                        WHERE acct_no=$acct_no
                      ";
                      $upd_bal_res = mysqli_query($conn, $upd_bal); //Query against the database

                      if($upd_bal_res == false){
                        echo "<div class='text-warning font-italic small d-block mb-0 mt-2'>Failed to Update Trading Account</div>";
                      }
                      else{
                        echo "<script>location.href='index.php'</script>";
                      }
                    }
                  }
                  else{
                    echo "<div class='text-warning font-italic small d-block mb-0 mt-2'>You are transferring more than your available balance</div>";
                  }
                }
                else{
                  echo "<div class='text-warning font-italic small d-block mb-0 mt-2'>Enter an amount to transfer to your FxWallet</div>";
                }
              }



              // Transferring From Wallet
              else{
                if($acct_to == $acct_sel){
                  $trans_type = "Trans_out"; //Means, transferring from wallet

                  if($amount !== ""){
                    // Get the credits amount in wallet
                    $wallet_deposits = mysqli_query($conn, "SELECT SUM(amount) AS wallet_deposits FROM transfers WHERE acct_id=$acct_sel AND trans_type='Deposit'");
                    while($wallet_depo = mysqli_fetch_assoc($wallet_deposits)){
                      $total_depo = $wallet_depo['wallet_deposits'];
                    }

                  // Get the credits amount in wallet
                    $wallet_trans_in = mysqli_query($conn, "SELECT SUM(amount) AS wallet_trans_in FROM transfers WHERE acct_id=$acct_sel AND trans_type='Trans_in'");
                    while($wallet_row_in = mysqli_fetch_assoc($wallet_trans_in)){
                      $total_trans_in = $wallet_row_in['wallet_trans_in'];
                    }

                  // Get the debits amount in wallet
                  $wallet_withdraws = mysqli_query($conn, "SELECT SUM(amount) AS wallet_withdraws FROM transfers WHERE acct_id=$acct_sel AND trans_type='Withdrawal'");
                    while($wallet_wit = mysqli_fetch_assoc($wallet_withdraws)){
                      $total_wit = $wallet_wit['wallet_withdraws'];
                    }
                  // Get the debits amount in wallet
                  $wallet_trans_out = mysqli_query($conn, "SELECT SUM(amount) AS wallet_trans_out FROM transfers WHERE acct_id=$acct_sel AND trans_type='Trans_out'");
                    while($wallet_row_out = mysqli_fetch_assoc($wallet_trans_out)){
                      $total_trans_out = $wallet_row_out['wallet_trans_out'];
                    }


                    $wallet_amount = $total_depo + $total_trans_in - $total_wit - $total_trans_out;

                    //  Check if amount is more than to be transferred
                    if($amount <= $wallet_amount){
                      // Debit fx wallet
                      $debit_wallet = "INSERT INTO transfers SET
                        user_id=$id,
                        acct_id=$acct_to,
                        trans_type='$trans_type',
                        amount='$amount',
                        trans_date='$trans_date'
                      ";

                      $debit_wallet_res = mysqli_query($conn, $debit_wallet);

                      if($debit_wallet_res == false){
                        echo "<div class='text-warning font-italic small d-block mb-0 mt-2'>Failed to transfer from your Fx Wallet</div>";
                      }
                      else{
                        // Update Trading Account Balance
                        $trad_bal = $acct_balance + $amount;

                        $trad_acct = "UPDATE new_account SET
                          balance='$trad_bal'

                          WHERE acct_no=$acct_no
                        ";

                        $trad_acct_res = mysqli_query($conn, $trad_acct);

                        if($trad_acct_res == false){
                          echo "<div class='text-warning font-italic small d-block mb-0 mt-2'>Failed to transfer to your trading account</div>";
                        }
                        else{
                          echo "<script>location.href='index.php'</script>";
                        }
                      }
                    }
                    else{
                      echo "<div class='text-warning font-italic small d-block mb-0 mt-2'>You are transferring more than your Fx Wallet Balance</div>";
                    }
                  }
                  else{
                    echo "<div class='text-warning font-italic small d-block mb-0 mt-2'>Enter an amount to transfer from your FxWallet</div>";
                  }
                }
              }
            }
          }
        ?>

      </div>

      <p class="small text-secondary m-0 mt-3"><span class="font-weight-bold text-dark">Attention:</span> To transfer funds between Trading Accounts firstly, move them into your FxTrade Wallet and then the trading account of your choice.</p>

   </div>
</section>



   <script src="./script.js"></script>
</body>
</html>



<script>
   function loadTransAcct(event, str){
      event.preventDefault();

      var xhr = new XMLHttpRequest();
        xhr.onload = function(){
          if(this.status == 200){
              // console.log(this.response);
              document.getElementById("load_acct").innerHTML = this.responseText;
              document.getElementById("load_acct2").innerHTML = this.responseText;
          }
        }
        xhr.open("POST", "ajax/get_trans_acct.php?acct_id=" + str, true);

        xhr.send();
   }
</script>