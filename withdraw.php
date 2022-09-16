<?php
   $caption = "Withdraw Fund";

   include "partials/header.php";
   include "config/check-login.php";
?>

<!-- Main section -->
<section class="home mb-5">
   <div class="container-fluid px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div class="d-flex flex-column">
        <div class="home-icon d-flex justify-content-start align-items-center">
            <i class="fa fa-dollar-sign" style="font-size: 18px; color: #e7781c;"></i>
            <span class="ml-2" style="font-size: 16px; color: #e7781c;">Withdraw Funds</span>
        </div>
      </div>

      <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
        <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

        <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
      </div>
    </div>

     
    <div class="bg-dark p-3 pt-4 rounded-lg deposit_form">
      <!-- <h5 class="text-light font-weight-normal text-center pb-1" style="font-size:15px;">Withdrawal Funds</h5> -->
      <form action="" method="POST" autocomplete="off">        
        <div class="row">
          <div class="col-12">
            <label for="pair">Account No.</label>
            <select name="acct_id" class="w-100" onchange="getCurrBal(this.value)">
              <option value="">-- Select Account --</option>
              <?php
                // Get Live accounts details from database
                $acct_details = mysqli_query($conn, "SELECT * FROM record_acct WHERE user_id=$id AND acct_type='Live'");
                if(mysqli_num_rows($acct_details) > 0){
                  while($row = mysqli_fetch_assoc($acct_details)){
                    ?>
                      <option value="<?= $row['id'] ?>"><?= "FxTrade Wallet - " . $row['acct_no'] . " (" . $row['acct_type'] . ")" ?></option>
                    <?php
                  }
                }
              ?>
            </select>

            <!-- <span class="text-white">Note:</span> -->
            <div class="text-light mb-1 mt-n2 d-none" id="withdraw_rate" style="font-size:12px;">(The rate for withdrawal transaction is: 
            <span class="text-warning" style='font-weight:500'><span id="rate"></span> = 1 USD)</span></div>

            <?php
              if(isset($_SESSION['sel-acct'])){
                echo "<div class='sel-acct text-warning font-italic d-block mt-n1' style='font-size:11px'>" . $_SESSION['sel-acct'] . "</div>";
                unset($_SESSION['sel-acct']);
              }
            ?>
          </div>
        </div>

        <div class="row no-gutters d-flex align-items-center">
          <div class="col-5">
            <label for="wallet" class="m-0 p-0 text-light">FxWallet Balance:</label>
          </div>
          <div class="col-7">
            <input type="text" class="text-light m-0 p-0 w-100 border-0 pl-3" name="wallet" id="curr_balance" style="font-size:18px">
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <label for="amount" class="d-block">Amount</label>
            <input type="text" name="amount" class="w-100" placeholder="Amount...">
            <?php
              if(isset($_SESSION['enter-amt'])){
                echo "<div class='text-light font-italic d-block mt-n2' style='font-size:11px'>" . $_SESSION['enter-amt'] . "</div>";
                unset($_SESSION['enter-amt']);
              }
            ?>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <input type="submit" name="withdraw" value="Withdraw Fund" class="w-100 mt-3 bg-warning border-warning text-dark font-weight-bold">
          </div>
        </div>
      </form>

    <!-- Add form details to database -->
    <?php
      if(isset($_POST['withdraw'])){
        $acct_id = mysqli_real_escape_string($conn, $_POST['acct_id']);
        $wallet = mysqli_real_escape_string($conn, $_POST['wallet']);
        $amount = mysqli_real_escape_string($conn, $_POST['amount']);
        $trans_type = "Withdrawal";
        $trans_date = date("Y-m-d");

        if($acct_id !== ""){
          if($amount !== ""){
            if($amount <= $wallet){
              // Insert withdraw amount into database
              $ins_withdrawal = "INSERT INTO transfers SET
                user_id=$id,
                acct_id=$acct_id,
                trans_type='$trans_type',
                amount='$amount',
                trans_date='$trans_date'
              ";

              // Query database
              $ins_withdrawal_res = mysqli_query($conn, $ins_withdrawal);

              if($ins_withdrawal_res == false){
                echo "<div class='text-warning font-italic small d-block mb-3'>Failed to complete withdrawal!</div>";
              }
              else{
                echo "<script>location.href='index.php'</script>";
              }
            }
            else{
              $_SESSION['enter-amt'] = "You cannot withdraw more than " . $wallet;
              echo "<script>location.href='withdraw.php'</script>";
              die();
            }
          }
          else{
            $_SESSION['enter-amt'] = "Enter Amount to Withdraw";
            echo "<script>location.href='withdraw.php'</script>";
            die();
          }
        }
        else{
          $_SESSION['sel-acct'] = "Select an account to withdraw";
          echo "<script>location.href='withdraw.php'</script>";
          die();
        }
      }
    ?>

    </div>

    <p id="withdraw_note" class="small text-secondary m-0 mt-2"><span class="font-weight-bold text-dark">Attention:</span> To withdraw funds, transfer funds from your trading account to FxTrade Wallet.</p>

   </div>
</section>



   <script src="./script.js"></script>
</body>
</html>



<script>
   function getCurrBal(str){

    if(str == ""){
      document.getElementById("withdraw_rate").classList.add("d-none");
      document.getElementById("curr_balance").value = "";
      return false;
    }

    //  Send XML Request with AJAX
    var xhr = new XMLHttpRequest();
      xhr.onload = function(){
         if(this.status == 200){
          var myObj = JSON.parse(this.responseText);
          document.getElementById("curr_balance").value = myObj[0];
          document.getElementById("rate").innerHTML = myObj[1];

          document.getElementById("withdraw_rate").classList.remove("d-none");
          document.querySelector(".sel-acct").innerHTML = "";
         }
      }
      xhr.open("GET", "ajax/wallet_bal.php?acct_id=" + str, true);

      xhr.send();
   }
</script>