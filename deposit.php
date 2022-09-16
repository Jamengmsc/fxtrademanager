<?php
   $caption = "Deposit Fund";

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
            <span class="ml-2" style="font-size: 16px; color: #e7781c;">Deposit Funds</span>
        </div>
      </div>

      <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
        <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

        <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
      </div>
    </div>

      <!-- Get Live accounts details from database -->
      <?php
        $acct_details = mysqli_query($conn, "SELECT * FROM record_acct WHERE user_id=$id AND acct_type='Live'");
      ?>

      <div class="bg-dark p-3 rounded-lg deposit_form">
        <form action="" method="POST" autocomplete="off">
          <div class="row">
            <div class="col-12">
              <label for="acct_no" class="mb-1">Accout No. &nbsp; <span class="text-gray font-italic small">(Live Accounts Only)</span></label>
              <select name="acct_id" id="" class="w-100">
                <?php
                  if(mysqli_num_rows($acct_details) > 0){
                    while($row = mysqli_fetch_assoc($acct_details)){
                      ?>
                        <option value="<?= $row['id'] ?>"><?= "FxTrade Wallet - " . $row['acct_no'] ?></option>
                      <?php
                    }
                  }
                ?>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <label for="pair">Deposit Amount</label>
              <input type="text" name="amount" class="w-100" placeholder="Amount to Deposit..." inputmode="numeric" pattern="[0-9]*">
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <input type="submit" name="deposit" value="Deposit Fund" class="w-100 mt-3 bg-warning border-warning text-dark font-weight-bold">
            </div>
          </div>
        </form>

        <!-- Add form details to database -->
        <?php
          if(isset($_POST['deposit'])){
            $acct_id = mysqli_real_escape_string($conn, $_POST['acct_id']);
            $amount = mysqli_real_escape_string($conn, $_POST['amount']);
            $trans_type = "Deposit";
            $trans_date = date("Y-m-d");

            if($amount !== ""){
              // Insert to database
              $deposit = "INSERT INTO transfers SET
                user_id=$id,
                acct_id=$acct_id,
                trans_type='$trans_type',
                amount='$amount',
                trans_date='$trans_date'
              ";

              // Query against the database
              $deposit_res = mysqli_query($conn, $deposit);

              if($deposit_res == false){
                echo "<div class='text-warning font-italic small d-block mb-3'>Failed to Deposit Amount!</div>";
              }
              else{
                echo "<script>location.href='index.php'</script>";
              }
            }
            else{
              echo "<div class='text-warning font-italic small d-block mb-3'>Enter a deposit amount!</div>";
            }
          }
        ?>

      </div>
      
      <!-- Important Note -->
      <p id="withdraw_note" class="small text-secondary m-0 mt-1"><span class="font-weight-bold text-dark">Attention:</span> Deposits are made to your FxTrade Wallet and then transferred to your live account only.</p>

   </div>
</section>



   <script src="./script.js"></script>
</body>
</html>



<script>
   // function acctDetails(event, str){
   //    alert(str);
   // }
</script>