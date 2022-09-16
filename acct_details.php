<?php
   $caption = "Account Details";

   include "partials/header.php";
   include "config/check-login.php";
?>

<!-- Main section -->
<section class="container-fluid px-md-5">
  <div class="d-flex justify-content-between align-items-center">
    <div class="d-flex flex-column">
      <div class="home-icon d-flex justify-content-start align-items-center">
          <i class="fa fa-edit" style="font-size: 18px; color: #e7781c;"></i>
          <span class="ml-2" style="font-size: 16px; color: #e7781c;">Account Details</span>
      </div>
    </div>

    <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
      <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

      <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
    </div>
  </div>

  <hr class="m-0 my-2">
</section>

<?php
  if(isset($_GET['acct_id'])){
    $acct_id = $_GET['acct_id'];
  }

  // Get account no from records table on database
  $get_acct_no = mysqli_query($conn, "SELECT acct_no FROM record_acct WHERE id=$acct_id");
  $acct_no = mysqli_fetch_assoc($get_acct_no)['acct_no'];

  // Get full account details from new_account table
  $get_acct_details = mysqli_query($conn, "SELECT * FROM new_account WHERE acct_no=$acct_no");
  if(mysqli_num_rows($get_acct_details) == 1){
    $row = mysqli_fetch_array($get_acct_details);
  }

  $totalProfit = mysqli_query($conn, "SELECT SUM(profit) AS total FROM records WHERE acct_id=$acct_id AND user_id=$id");
    while($prof_row = mysqli_fetch_assoc($totalProfit)){
      $profit = $prof_row['total'];
  }
?>


<section class="mb-5 acct_details_main" style="position:relative">
  <div class="container-fluid px-md-5">
    <div class="dashboard mb-3">
      <a href="<?= SITEURL ?>dashboard.php?acct_id=<?= $acct_id ?>">DASHBOARD</a>
    </div>


    <div class="pre_head">
      <div class="detail_wrap trans_title small d-none">
        <h5 class="m-0 text-dark text-uppercase font-weight-bold">Transactions</h5>
        <i class="fa fa-exchange text-dark" style="font-size: 20px"></i>
      </div>

      <div class="detail_wrap acct_created small">
        <div class="d-flex flex-column align-items-start">
          <span class="mb-n1" style="color:#e7781c; font-weight:600">Account Created On</span>
          <span style="color:#333; font-weight:500"><?= date("d/m/Y", strtotime($row['date_added'])) ?></span>
        </div>
        <i class="far fa-calendar-check text-dark" style="font-size: 20px"></i>
      </div>

      <div class="detail_wrap acct_no_type">
        <div>
          <div class="small font-weight-bold text-secondary mb-n1">Account No./Type</div>
          <div class=""><?php echo "<div class='font-weight-bold d-inline'>" . $row['acct_no'] . "</div> <span class='small font-italic'>(" . $row['acct_type'] . ")</span>" ?></div>
        </div>
          <i class="fa fa-chalkboard-teacher text-dark" style="font-size:18px"></i>
      </div>

      <div class="detail_wrap">
        <div>
          <div class="small font-weight-bold text-secondary mb-n1">Account Broker</div>
          <div class=""><?php echo "<div class='font-weight-bold d-inline'>" . $row['broker'] . "</div>" ?></div>
        </div>
          <i class="fa fa-user-friends text-dark" style="font-size:18px"></i>
      </div>

      <?php
        if($row['acct_type'] == "Live"){
          ?>
            <div class="detail_wrap" style="font-size:18px">
              <div class="font-weight-bold text-secondary text-uppercase">Wallet Bal<span class="d-none d-md-inline">.</span><span class="d-md-none d-inline">ance</span></div>
              
              <div class="font-weight-bold text-dark" style="font-size:20px"><?php echo $row['currency']; ?><?php echo fxTotal($acct_id, $conn) ?></div>
            </div>
          <?php
        }
      ?>  

      <div class="detail_wrap" style="font-size:18px">
        <div class="font-weight-bold text-secondary text-uppercase">Trading Bal<span class="d-none d-md-inline">.</span><span class="d-md-none d-inline">ance</span></div>
        <?php echo "<div class='font-weight-bold text-dark' style='color: #e7781c; font-size:20px'>" . $row['currency'] . number_format($row['balance'] + $profit, 2, ".", ",") . "</div>" ?>
      </div>
    </div>
    
    <div class="trade_tbl small text-center mt-4">
      <?php
        $noRecords = mysqli_query($conn, "SELECT pair FROM records WHERE acct_id=$acct_id AND user_id=$id");
        $no_of_records = mysqli_num_rows($noRecords);
        
        $noTrans = mysqli_query($conn, "SELECT acct_id FROM transfers WHERE acct_id=$acct_id AND user_id=$id");
        $no_of_transfer = mysqli_num_rows($noTrans);
      ?>

      <div class="acct_tbl_head">
        <div class="d-flex justify-content-between align-items-center py-1" style="background:#F0F0F0">
          <div class="text-left">
            <div class="font-weight-bold text-uppercase mb-n1" style="color:#e7781c">Trades History</div>
            <div class="text-secondary" style="font-weight:600">Total Trades: <span class="text-dark" style="font-weight:bold; font-size:14px"><?= $no_of_records ?></span></div>
          </div>

          <?php
            if($row['acct_type'] == "Live"){
              ?>
                <div class="trans_hist btn btn-dark">Trans. History</div>
              <?php
            }
          ?>
  
        </div>

        <ul class="trade_head">
          <li>Date</li>
          <li>Pair</li>
          <li>Position</li>
          <li>Lotsize</li>
          <li>P/L</li>
        </ul>
      </div>

      <div class="trade_item_list">
        <?php
          $getDate = mysqli_query($conn, "SELECT DISTINCT record_date FROM records WHERE acct_id=$acct_id GROUP BY record_date ORDER BY record_date DESC");
          if(mysqli_num_rows($getDate) > 0){
            while($date_row = mysqli_fetch_assoc($getDate)){

              $record_date = $date_row['record_date'];
              $date = date("d M Y", strtotime($date_row['record_date']));

            
              ?>
              <div class="trade_item">
                <div class="text-left font-italic" style="padding:5px 10px; font-weight:600; border-bottom:1px solid rgb(150,150,150)"><?= $date ?></div>

                <?php
                  $getPair = mysqli_query($conn, "SELECT * FROM records WHERE record_date='$record_date' AND user_id=$id AND acct_id=$acct_id");
                  if(mysqli_num_rows($getPair) > 0){
                    while($trade_row = mysqli_fetch_array($getPair)){
                      ?>
                        <ul class="each_trade" onclick="showTrade(<?php echo $trade_row['id'] ?>)">
                          <li></li>
                          <li style="font-weight:500"><?= $trade_row['pair'] ?></li>
                          <li><?= $trade_row['position'] ?></li>
                          <li><?= $trade_row['lotsize'] ?></li>
                          <li><?= $row['currency'] . " " . $trade_row['profit'] ?></li>
                        </ul>
                      <?php
                    }
                  }
                ?>

                <ul class="trade_total">
                  <li></li>
                  <li></li>
                  <li></li>
                  <li>Total:</li>
                  <li>
                    <?php
                      echo $row['currency'] . " ";
                      // Get total profit on trading on this account
                      $total_profit = mysqli_query($conn, "SELECT SUM(profit) AS profits FROM records WHERE acct_id=$acct_id AND record_date='$record_date'");

                      while($prof_row = mysqli_fetch_assoc($total_profit)){
                        echo $total_prof = $prof_row['profits'];
                      }
                    ?>
                  </li>
                </ul>
              </div>
              <?php
            }
          }
          else{
              echo "<div class='p-2 text-danger font-italic text-left text-md-center'>No trade record</div>";
          }
        ?>
      </div>

      <div class="net_profit font-weight-bold" style="font-size: 18px"><span class="text-secondary">NET PROFIT</span> = &nbsp;  
        <span>
          <?php
            if($profit == 0){
              echo $row['currency'] . " 0.00";
            }
            else{
              echo $row['currency'] . " " . $profit;
            }
          ?>
        </span>
      </div>
    </div>


    <!-- TRANSACTION HISTORY -->
    <div class="acct_trans_hist text-center mt-4 small d-none">
      <div class="trans_tbl_head">
        <div class="d-flex justify-content-between align-items-center py-1" style="background:#F0F0F0">
          <div class="text-left">
            <div class="font-weight-bold text-uppercase mb-n1" style="color:#e7781c">Transaction History</div>
            <div class="text-secondary" style="font-weight:600">Transactions: <span class="text-dark" style="font-weight:bold; font-size:14px"><?= $no_of_transfer ?></span></div>
          </div>

          <div class="trans_hist back_to_acct btn btn-dark">Account Details</div>
        </div>

        <ul class="trade_head">
          <!-- <li>Date</li> -->
          <li class="text-left p-0 pl-2">Trans. Type</li>
          <li>Credit</li>
          <li>Debit</li>
          <li>Subtotal</li>
        </ul>
      </div>

      <!-- Transactions Table -->
      <div class="trade_item_list">
        <?php
          $getDate = mysqli_query($conn, "SELECT DISTINCT trans_date FROM transfers WHERE acct_id=$acct_id GROUP BY trans_date ORDER BY trans_date DESC");
          if(mysqli_num_rows($getDate) > 0){
            while($date_row = mysqli_fetch_assoc($getDate)){

              $trans_date = $date_row['trans_date'];
              $date = date("d M Y", strtotime($date_row['trans_date']));
            
              ?>
              <div class="trade_item">
                <div class="text-left font-italic text-secondary mt-2" style="padding:5px 10px; font-size:12px; font-weight:600; border-bottom:1px solid rgb(150,150,150)"><?= $date ?></div>

                <?php
                  $transactions = mysqli_query($conn, "SELECT * FROM transfers WHERE trans_date='$trans_date' AND user_id=$id AND acct_id=$acct_id");
                  if(mysqli_num_rows($transactions) > 0){
                    while($trans_row = mysqli_fetch_array($transactions)){
                      ?>
                        <ul class="each_trade" style="border-bottom: 1px solid rgb(219, 219, 219)" onclick="showTrade(<?php echo $trans_row['id'] ?>)">
                          <!-- <li></li> -->
                          <li class="text-left pl-2" style="font-weight:500;">
                            <?php
                              if($trans_row['trans_type'] == "Trans_out" || $trans_row['trans_type'] == "Trans_in"){
                                echo "Int. Transfer";
                              }
                              else{
                                echo $trans_row['trans_type'];
                              }
                            ?>
                          </li>
                          <li>
                            <?php
                              if($trans_row['trans_type'] == "Trans_in" || $trans_row['trans_type'] == "Deposit"){
                                echo $row['currency'] . " " . $trans_row['amount'];
                              }
                            ?>
                          </li>
                          <li>
                            <?php
                              if($trans_row['trans_type'] == "Trans_out" || $trans_row['trans_type'] == "Withdrawal"){
                                echo "<div class='font-italic' style='color:#e7781c'>" . $row['currency'] . " -" . $trans_row['amount'] . "</div>";
                              }
                            ?>
                          </li>
                          <li></li>
                        </ul>
                      <?php
                    }
                  }
                ?>

                <ul class="trans_total">
                  <li></li>
                  <!-- <li class="text-uppercase font-weight-normal text-left" style="color:#e7781c">Sub-Total</li> -->

                  <li>
                    <?php
                       // Get total withdrawal amount on this account
                       $deposits = mysqli_query($conn, "SELECT SUM(amount) AS tot_deposits FROM transfers WHERE acct_id=$acct_id AND trans_date='$trans_date' AND trans_type='Deposit'");

                       while($trf_row = mysqli_fetch_assoc($deposits)){
                         $total_deposit = $trf_row['tot_deposits'];
                       }
 
                       // Get total Transfer outward amount on this account
                       $trans_in = mysqli_query($conn, "SELECT SUM(amount) AS tot_trans_in FROM transfers WHERE acct_id=$acct_id AND trans_date='$trans_date' AND trans_type='trans_in'");
 
                       while($trf_row = mysqli_fetch_assoc($trans_in)){
                         $total_trans_in = $trf_row['tot_trans_in'];
                       }
 
                       $total_credit = $total_deposit + $total_trans_in;
                       echo   $row['currency'] . number_format($total_credit, 2, ".", ",") ;
                    ?>
                  </li>

                  <li class="font-italic" style="color:#e7781c; font-size: 13px;">
                    <?php
                      // Get total withdrawal amount on this account
                      $withdrawal = mysqli_query($conn, "SELECT SUM(amount) AS tot_withdraw FROM transfers WHERE acct_id=$acct_id AND trans_date='$trans_date' AND trans_type='Withdrawal'");

                      while($trf_row = mysqli_fetch_assoc($withdrawal)){
                        $total_withdrawal = $trf_row['tot_withdraw'];
                      }

                      // Get total Transfer outward amount on this account
                      $trans_out = mysqli_query($conn, "SELECT SUM(amount) AS tot_trans_out FROM transfers WHERE acct_id=$acct_id AND trans_date='$trans_date' AND trans_type='Trans_out'");

                      while($trf_row = mysqli_fetch_assoc($trans_out)){
                        $total_trans_out = $trf_row['tot_trans_out'];
                      }

                      $total_debit = $total_withdrawal + $total_trans_out;
                      echo $row['currency'] . " -" . number_format($total_debit, 2, ".", ",");
                    ?>
                  </li>

                  <li>
                    <?php
                      $subtotal = $total_credit - $total_debit;

                      if($subtotal < 0){
                        echo "<div class='font-italic' style='color:#e7781c'>" . $row['currency'] . " " . number_format($subtotal, 2, ".", ",") . "</div>";
                      }
                      else{
                        echo $row['currency'] . " " . number_format($subtotal, 2, ".", ",");
                      }
                    ?>
                  </li>
                </ul>
              </div>
              <?php
            }
          }
          else{
              echo "<div class='p-2 text-danger font-italic text-left text-md-center'>No transaction record</div>";
          }
        ?>
      </div>

      <ul class="total mt-2 font-weight-bold" style="font-size: 16px">
        <li></li>
        <li></li>
        <li class="text-secondary">TOTAL</li>
        <li style="font-size: 16px">
          <?php
            // call the fxTotal function
            echo $row['currency'] . " ";
            echo fxTotal($acct_id, $conn);
          ?>
        </li>
      </ul>

    </div>

    
</section>



<!-- Pop Trade Item on click -->
  <div class="trade_pop_bg">
    <div class="trade_pop">
      <div class="head">
        <h5 class="m-0"><span class="text-secondary font-italic">Trade</span> Details</h5>
      </div>
      <hr class="m-0 mt-1 mb-2">

      <div class="body mb-4">
        <p id="trade_date" class="text-right m-0 p-0 font-italic mb-n2 text-secondary" style="font-weight:600; font-size: 14px">24 Apr 2022</p>

        <div class="trade_size mb-3">
          <div class="small font-weight-bold text-dark mb-n1">PAIR</div>
          <h4 id="pair" style="color:#e7781c">EURUSD</h4>
        </div>

        <div class="trade_size d-flex justify-content-between align-items-center">
          <div class="detail_wrap">
            <div class="small font-weight-bold text-secondary mb-n1">Position</div>
            <div id="position" class="font-weight-bold text-uppercase">SELL</div>
          </div>

          <div class="detail_wrap">
            <div class="small font-weight-bold text-secondary mb-n1">Lotsize</div>
            <div id="lotsize" class="font-weight-bold">0.03</div>
          </div>

          <div class="detail_wrap">
            <div class="small font-weight-bold text-secondary mb-n1">Profit/Loss</div>
            <div id="profit" class="font-weight-bold">$54.84</div>
          </div>
        </div>
      </div>

      <div class="dismiss_pop">
        <p class="">Dismiss</p>
      </div>
    </div>
  </div>

  <script src="./script.js"></script>
</body>
</html>


<script>
  // Account Details Transactions History
  var transHist = document.querySelector(".trans_hist");
      transHist.onclick = () => {
        var transTitle = document.querySelector(".trans_title");
        var acctCreated = document.querySelector(".acct_created");
        var acctTradeTbl = document.querySelector(".trade_tbl");
        var acctTransHist = document.querySelector(".acct_trans_hist");

        transTitle.classList.remove("d-none");
        acctCreated.classList.add("d-none");


        acctTradeTbl.classList.add("d-none");
        acctTransHist.classList.remove("d-none");


        // Back to Account Details
        document.querySelector(".back_to_acct").onclick = () => {
          transTitle.classList.add("d-none");
          acctCreated.classList.remove("d-none");

          acctTradeTbl.classList.remove("d-none");
          acctTransHist.classList.add("d-none");
        }
      }

</script>