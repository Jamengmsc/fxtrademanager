  <?php
    $caption = "My Accounts";

    include "partials/header.php";
    include "config/check-login.php";
  ?>

  <!-- Main section -->
  <section class="home mb-5">
    <div class="container-fluid px-md-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex flex-column">
            <div class="home-icon d-flex justify-content-start align-items-center">
              <i class="fa fa-home" style="font-size: 18px; color: #e7781c;"></i>
              <span class="ml-2" style="font-size: 16px; color: #e7781c;">My Accounts</span>
            </div>
        </div>

        <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
            <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

            <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
        </div>
      </div>

      <?php
        if(isset($_SESSION['withdraw-rate'])){
            echo "<div class='alert alert-success alert-dismissible font-italic mb-3 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['withdraw-rate'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
            unset($_SESSION['withdraw-rate']);
        }
      ?>


      <?php
        $sql = mysqli_query($conn, "SELECT * FROM new_account WHERE user_id=$id");
        if(mysqli_num_rows($sql) > 0){
          while($rows = mysqli_fetch_assoc($sql)){

          if($rows['active'] == 1){
          ?>
            <!-- Each account item -->
            <div class="acct_item mb-2 p-3 bg-dark">
              <div class="d-flex flex-column">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="">
                      <h5 class="text-warning font-weight-bold"><?= $rows['acct_no'] ?> <span class="text-light font-italic font-weight-normal small">(<?= $rows['acct_type'] ?>) <span class="text-light" style="font-style:normal; font-size:20px"><?php echo " <div class='text-gray' style='font-size:13px'>" . $rows['broker'] . "</div>" ?></span></span></h5>
                    </div>

                    <?php
                      $getAcctNo = mysqli_query($conn, "SELECT id FROM record_acct WHERE acct_no=" . $rows['acct_no'] . "");
                      $acct_id = mysqli_fetch_assoc($getAcctNo)['id'];
                    ?>

                    <div class="acct_icons d-flex justify-content-between align-items-center">
                      <div class="minimize">
                        <div class="max"></div>
                        <div class="min"></div>
                      </div>
                      <div class="acct_menu_icon">
                        <i class="fa fa-ellipsis-vertical text-warning" style="font-size:20px;"></i>
                      </div>
                    </div>

                    <!-- Account Menu -->
                    <div class="account_menu">
                      <a href="<?= SITEURL ?>edit_acct.php?acct_id=<?= $acct_id ?>">Edit Account</a>
                      <a href="<?= SITEURL ?>acct_details.php?acct_id=<?= $acct_id ?>">View Account</a>
                      <a href="#" onclick="deleteAcct(event, <?= $acct_id ?>)">Delete Permanently</a>
                      <a href="#" onclick="disableAcct(event, <?= $acct_id ?>)">Disable Account</a>
                      <a href="#" onclick="resetAcct(event, <?= $acct_id ?>)">Reset Account</a>
                    </div>
                  </div>

                  <!-- Get total deposits and withdrawals to compute the net profit -->
                  <?php
                    // Total deposit
                    $deposits = mysqli_query($conn, "SELECT SUM(amount) AS total_depo FROM transfers WHERE acct_id=$acct_id AND trans_type='Deposit'");

                    while($dep_total = mysqli_fetch_assoc($deposits)){
                        $total_deposits = $dep_total['total_depo'];
                    }

                    // Total withdrawals
                    $withdrawals = mysqli_query($conn, "SELECT SUM(amount) AS total_withdraw FROM transfers WHERE acct_id=$acct_id AND trans_type='Withdrawal'");

                    while($wit_total = mysqli_fetch_assoc($withdrawals)){
                        $total_withdrawals = $wit_total['total_withdraw'];
                    }
                  ?>

                  <?php
                    // Get the total profit for this account
                    $sum_profit = mysqli_query($conn, "SELECT SUM(profit) AS total FROM records WHERE user_id=$id AND acct_id=$acct_id");

                    while($row = mysqli_fetch_assoc($sum_profit)){
                        $total_profit = $row['total'];
                    }
                  ?>


                  <div class="acct_item_down row mt-3">
                    <div class="col-md-9 col-12">
                      <div class="row mb-md-0 mb-3">
                        <!-- Fx Wallet Account -->
                        <?php
                          if($rows['acct_type'] == "Live"){
                            ?>
                              <div class="col-md-4 col-12 mb-3 curr-bal border-right border-secondary">
                                <h5 class="text-warning m-0" style="font-style:normal; font-weight:400; font-size:17px">Fx Wallet</h5>
                                <h5 class="">
                                  <?php
                                    echo $rows['currency'];
                                    // echo number_format($wallet_bal, 2, ".", ",");
                                    fxTotal($acct_id, $conn);
                                  ?>
                                </h5>
                              </div>
                            <?php
                          }
                        ?>

                        <div class="col-md-4 col-6 curr-bal border-right border-secondary">
                            <h6 style="font-size:13px;">Trading Balance</h6>
                            <h5 class="">
                              <?php
                                  echo $rows['currency'];
                                  echo number_format($total_profit + $rows['balance'], 2, ".", ",");
                              ?>
                            </h5>
                        </div>

                        <!-- Get total profit/loss -->
                        <div class="col-md-4 col-6 curr-bal border-right border-secondary">
                            <h6 style="font-size:13px">Profit/Loss</h6>

                            <h5 class=""><?= $rows['currency']; ?>
                              <?php
                                  if($rows['balance'] <= 0){
                                    if($total_profit == 0){
                                        echo 0.00;
                                    }
                                    else{
                                        echo number_format($total_profit, 2, ".", ",");
                                    }
                                  }
                                  else{
                                    $perc_acct_profit = ($total_profit/$rows['balance']) * 100;
                                    if($total_profit == 0){
                                        echo 0.00;
                                    }
                                    else{
                                        echo number_format($total_profit, 2, ".", ",");
                                        echo "<br>";
                                        
                                        if($perc_acct_profit > 100){
                                          echo "<div style='color:lightgray; font-size:10px;'>(+100% ROI)</div>";
                                        }
                                        else{
                                          echo "<div style='color:lightgray; font-size:10px;'>(" . number_format($perc_acct_profit, 2, ".", ",") . "% ROI)</div>";
                                        }
                                    }
                                  }
                                  
                              ?>
                            </h5>
                        </div> 
                      </div>


                      <!-- Check if account is a live account -->
                      <?php
                        if($rows['acct_type'] == "Live"){
                          ?>
                            <!-- Deposits and withdrawals -->
                            <div class="row deposit bg-secondary pt-md-1 mb-md-n2 py-2">
                              <div class="col-6">
                                <span class="text-light font-italic">Deposited:</span>
                                <span class="text-warning">
                                  <?php
                                    if($total_deposits == 0){
                                      echo $rows['currency'];
                                      echo "0.00";
                                    }
                                    else{
                                      echo $rows['currency'];
                                      echo $total_deposits;
                                    }
                                  ?>
                                </span>
                              </div>

                              <div class="col-6">
                                <span class="text-light font-italic">Withdrawn:</span>
                                <span class="text-warning">
                                  <?php
                                    if($total_withdrawals == 0){
                                      echo $rows['currency'];
                                      echo "0.00";
                                    }
                                    else{
                                      echo $rows['currency'];
                                      echo $total_withdrawals;
                                    }
                                  ?>
                                </span>
                              </div>
                            </div>
                          <?php
                        }
                      ?>
                    </div>


                    <div class="col-md-3 col-12 mt-4 mt-md-0">
                      <?php
                        $trades = mysqli_query($conn, "SELECT * FROM records WHERE user_id=$id AND acct_id=$acct_id");
                        $total_trades = mysqli_num_rows($trades);

                        // Get all trades with profits
                        $profits = mysqli_query($conn, "SELECT COUNT(profit) AS rec_profit FROM records WHERE profit >= 0 AND acct_id=$acct_id");
                        while($prof_row = mysqli_fetch_assoc($profits)){
                            $pos_profit = $prof_row['rec_profit'];
                        }

                        // Get all trades with losses
                        $losses = mysqli_query($conn, "SELECT COUNT(profit) AS rec_loss FROM records WHERE profit < 0 AND acct_id=$acct_id");
                        while($loss_row = mysqli_fetch_assoc($losses)){
                            $neg_profit = $loss_row['rec_loss'];
                        }
                        
                      ?>

                      <div class="d-flex justify-content-between align-items-center mb-2 pb-1" style="border-bottom:1px solid rgba(255,255,255,0.1)">
                        <span class="text-warning">Total Trades</span>

                        <span style="font-style:bold; color:rgba(255,255,255,.6)">
                            <?php
                            if($pos_profit == 0 && $neg_profit == 0){
                              echo "(0% <span class='small font-italic'>Win Rate</span>)";
                            }
                            else{
                              $percent = ($pos_profit/$total_trades)*100;

                              echo "(" . number_format($percent, 0, ".", ",") . "% <span class='small font-italic'>Win Rate</span>)";
                            }
                            
                            ?>
                        </span>
                      </div>
                      <!-- <br> -->

                      <span class="mr-3 font-italic text-white" style="font-size:15px">Trades: <span style="font-style:bold; color:orange"><?= $total_trades ?></span></span>
                      <span class="mr-3 font-italic text-gray" style="font-size:15px">Wins: <span style="font-style:normal; color:white"><?= $pos_profit ?></span></span>
                      <span class="mr-3 font-italic text-gray" style="font-size:15px">Losses: <span style="font-style:normal; color:gray"><?= $neg_profit ?></span></span>
                    </div>
                  </div>
              </div>
            </div>
          <?php
          }


          // FOR DISABLED ACCOUNTS........................................
          elseif($rows['active'] == 0){
          ?>
            <!-- Each account item -->
            <div class="acct_item mb-3 p-3 bg-dark">
              <div class="d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="">
                    <h5 class="text-muted font-weight-bold"><?= $rows['acct_no'] ?> <span class="text-muted font-italic font-weight-normal small">(<?= $rows['acct_type'] ?>) <span class="text-muted" style="font-style:normal; font-size:20px"><?php echo " - " . $rows['broker'] ?></span></span></h5>
                  </div>

                  <?php
                    $getAcctNo = mysqli_query($conn, "SELECT id FROM record_acct WHERE acct_no=" . $rows['acct_no'] . "");
                    $acct_id = mysqli_fetch_assoc($getAcctNo)['id'];
                  ?>

                  <div class="acct_icons d-flex justify-content-between align-items-center">
                    <div class="minimize">
                      <div class="max"></div>
                      <div class="min"></div>
                    </div>
                    <div class="acct_menu_icon">
                      <i class="fa fa-ellipsis-vertical text-gray" style="font-size:20px;"></i>
                    </div>
                  </div>

                  <!-- Account Menu -->
                  <div class="account_menu">
                    <a href="<?= SITEURL ?>edit_acct.php?acct_id=<?= $acct_id ?>">Edit Account</a>
                    <a href="<?= SITEURL ?>acct_details.php?acct_id=<?= $acct_id ?>">View Account</a>
                    <a href="#" onclick="deleteAcct(event, <?= $acct_id ?>)">Delete Permanently</a>
                    <a href="#" onclick="disableAcct(event, <?= $acct_id ?>)">Enable Account</a>
                    <a href="#" onclick="resetAcct(event, <?= $acct_id ?>)">Reset Account</a>
                  </div>
                </div>

                <!-- Get total deposits and withdrawals to compute the net profit -->
                <?php
                  // Total deposit
                  $deposits = mysqli_query($conn, "SELECT SUM(amount) AS total_depo FROM transfers WHERE acct_id=$acct_id AND trans_type='Deposit'");

                  while($dep_total = mysqli_fetch_assoc($deposits)){
                      $total_deposits = $dep_total['total_depo'];
                  }

                  // Total withdrawals
                  $withdrawals = mysqli_query($conn, "SELECT SUM(amount) AS total_withdraw FROM transfers WHERE acct_id=$acct_id AND trans_type='Withdrawal'");

                  while($wit_total = mysqli_fetch_assoc($withdrawals)){
                      $total_withdrawals = $wit_total['total_withdraw'];
                  }
                ?>

                <?php
                  // Get the total profit for this account
                  $sum_profit = mysqli_query($conn, "SELECT SUM(profit) AS total FROM records WHERE user_id=$id AND acct_id=$acct_id");

                  while($row = mysqli_fetch_assoc($sum_profit)){
                      $total_profit = $row['total'];
                  }
                ?>


                <div class="acct_item_down row mt-3">
                  <div class="col-md-9 col-12">
                    <div class="row mb-md-0 mb-3">
                      <!-- Fx Wallet Account -->
                      <?php
                        if($rows['acct_type'] == "Live"){
                          ?>
                            <div class="col-md-4 col-12 mb-3 curr-bal border-right border-secondary">
                              <h5 class="text-muted m-0" style="font-style:normal; font-weight:400; font-size:17px">Fx Wallet</h5>
                              <h5 class="text-muted">
                                <?php
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

                                  echo $rows['currency'];
                                  echo number_format($wallet_bal, 2, ".", ",");
                                ?>
                              </h5>
                            </div>
                          <?php
                        }
                      ?>

                      <div class="col-md-4 col-6 curr-bal border-right border-secondary">
                        <h6 style="font-size:13px;">Trading Balance</h6>
                        <h5 class="text-muted">
                          <?php
                            echo $rows['currency'];
                            echo number_format($total_profit + $rows['balance'], 2, ".", ",");
                          ?>
                        </h5>
                      </div>

                        <!-- Get total profit/loss -->
                        <div class="col-md-4 col-6 curr-bal border-right border-secondary">
                        <h6 style="font-size:13px">Profit/Loss</h6>

                        <h5 class="text-muted"><?= $rows['currency']; ?>
                          <?php
                            if($rows['balance'] <= 0){
                              if($total_profit == 0){
                                  echo 0.00;
                              }
                              else{
                                  echo number_format($total_profit, 2, ".", ",");
                              }
                            }
                            else{
                              $perc_acct_profit = ($total_profit/$rows['balance']) * 100;
                              if($total_profit == 0){
                                  echo 0.00;
                              }
                              else{
                                  echo number_format($total_profit, 2, ".", ",");
                                  echo "<br>";
                                  
                                  if($perc_acct_profit > 100){
                                    echo "<div style='font-size:10px;'>(+100% ROI)</div>";
                                  }
                                  else{
                                    echo "<div style='font-size:10px;'>(" . number_format($perc_acct_profit, 2, ".", ",") . "% ROI)</div>";
                                  }
                              }
                            }
                          ?>
                        </h5>
                      </div> 
                
                    </div>


                    <!-- Check if account is a live account -->
                    <?php
                      if($rows['acct_type'] == "Live"){
                          ?>
                            <!-- Deposits and withdrawals -->
                            <div class="row deposit bg-secondary pt-md-1 mb-md-n2 py-2">
                                <div class="col-6">
                                  <span class="text-gray font-italic">Deposited:</span>
                                  <span class="text-gray">
                                      <?php
                                        if($total_deposits == 0){
                                            echo $rows['currency'];
                                            echo "0.00";
                                        }
                                        else{
                                            echo $rows['currency'];
                                            echo $total_deposits;
                                        }
                                      ?>
                                  </span>
                                </div>

                                <div class="col-6">
                                  <span class="text-gray font-italic">Withdrawn:</span>
                                  <span class="text-gray">
                                      <?php
                                        if($total_withdrawals == 0){
                                            echo $rows['currency'];
                                            echo "0.00";
                                        }
                                        else{
                                            echo $rows['currency'];
                                            echo $total_withdrawals;
                                        }
                                      ?>
                                  </span>
                                </div>
                            </div>
                          <?php
                      }
                    ?>
                  </div>


                  <div class="col-md-3 col-12 mt-4 mt-md-0">
                    <?php
                      $trades = mysqli_query($conn, "SELECT * FROM records WHERE user_id=$id AND acct_id=$acct_id");
                      $total_trades = mysqli_num_rows($trades);

                      // Get all trades with profits
                      $profits = mysqli_query($conn, "SELECT COUNT(profit) AS rec_profit FROM records WHERE profit >= 0 AND acct_id=$acct_id");
                      while($prof_row = mysqli_fetch_assoc($profits)){
                          $pos_profit = $prof_row['rec_profit'];
                      }

                      // Get all trades with losses
                      $losses = mysqli_query($conn, "SELECT COUNT(profit) AS rec_loss FROM records WHERE profit < 0 AND acct_id=$acct_id");
                      while($loss_row = mysqli_fetch_assoc($losses)){
                          $neg_profit = $loss_row['rec_loss'];
                      }
                    ?>

                    <div class="d-flex justify-content-between align-items-center mb-2 pb-1" style="border-bottom:1px solid rgba(255,255,255,0.1)">
                      <span class="text-muted">Total Trades</span>

                      <span style="font-style:bold; color:rgba(255,255,255,.3)">
                        <?php
                          if($pos_profit == 0 && $neg_profit == 0){
                            echo "(0% <span class='small font-italic'>Win Rate</span>)";
                          }
                          else{
                            $percent = ($pos_profit/$total_trades)*100;

                            echo "(" . number_format($percent, 0, ".", ",") . "% <span class='small font-italic'>Win Rate</span>)";
                          }
                        ?>
                      </span>
                    </div>
                    <!-- <br> -->

                    <span class="mr-3 font-italic text-muted" style="font-size:15px">Trades: <span style="font-style:bold"><?= $total_trades ?></span></span>
                    <span class="mr-3 font-italic text-muted" style="font-size:15px">Wins: <span style="font-style:normal;"><?= $pos_profit ?></span></span>
                    <span class="mr-3 font-italic text-muted" style="font-size:15px">Losses: <span style="font-style:normal;"><?= $neg_profit ?></span></span>
                  </div>
                </div>
              </div>
            </div>
          <?php
          }
        ?>

          

        <?php
          }
        }
        else{
          echo "<div class='text-danger text-md-left text-center font-italic mt-4'>You do not have any account</div>";
          // echo "</br>";

          ?>
            <div class="small mt-3 text-secondary alert alert-secondary"><a href="<?php echo  SITEURL ?>new-account.php" class="font-italic text-success" style="text-decoration:underline">Click here</a> to register a trading account and start recording your trades</div>
          <?php
        }
    ?>

    


    <!-- RESET ACCOUNT -->
    <div class="reset_popup">
        <div id="reset_acct" class="p-3">
          <h5 class="font-weight-normal" style="color:#000">Reset Account</h5>
          <p class="mt-1" style="color:rgba(0,0,0,0.8); line-height:20px;">Resetting this account will set all account values to zero. Also, delete all transactions (Deposits, Withdrawals and Transfers) and trade records.</p>

          <div id="popup_btn">
              <button id="cancel_reset">Cancel</button>   
              <button id="ok_reset">Reset Account</button>
          </div>
        </div>
    </div>

    <!-- DELETE ACCOUNT -->
    <div class="delete_popup">
        <div id="del_acct" class="p-3">
          <h5 class="font-weight-normal" style="color:#000">Delete Account</h5>
          <p class="mt-1" style="color:rgba(0,0,0,0.8); line-height:20px;">Deleting this account will permanently remove the account and all the records for this account.</p>

          <div id="popup_btn">
              <button id="cancel_delete">Cancel</button>
              <button id="ok_delete">Delete Account</button>
          </div>
        </div>
    </div>

    <!-- DISABLE ACCOUNT -->
    <div class="disable_popup">
        <div id="disable_acct" class="p-3">
          <h5 class="font-weight-normal" style="color:#000">Enable/Disable Account</h5>
          <p class="mt-1" style="color:rgba(0,0,0,0.8);line-height:20px">Enabling or disabling this account will determine the restrictions for this account.</p>

          <div id="popup_btn">
              <button id="cancel_disable">Cancel</button>
              <button id="ok_disable">Enable/Disable</button>
          </div>
        </div>
    </div>
    
  </section>


    <script src="./script.js"></script>
  </body>
  </html>



  <script>
  // Minimize Account Items
  var acctItem = document.querySelectorAll(".acct_item");
    for (let i = 0; i < acctItem.length; i++);

  var acctItemDown = document.querySelectorAll(".acct_item_down");
    for (let i = 0; i < acctItemDown.length; i++);

  var minimizeMax = document.querySelectorAll(".minimize .max");
    for (let i = 0; i < minimizeMax.length; i++);

  var minimizeMin = document.querySelectorAll(".minimize .min");
    for (let i = 0; i < minimizeMin.length; i++);

  var minAcct = document.querySelectorAll(".minimize");
    for(let i = 0; i < minAcct.length; i++){
        minAcct[i].onclick = () => {
          acctItem[i].classList.toggle("active");
          acctItemDown[i].classList.toggle("active");
          minimizeMax[i].classList.toggle("active");
          minimizeMin[i].classList.toggle("active");

          // if(acctItem[i].classList.contains("active")){
          //   var acctItemHeight = acctItem[i].getBoundingClientRect().height + 705;
          //   var currBottom = window.innerHeight;
          //   var footerHeight = document.querySelector(".footer_menu").getBoundingClientRect().height
          //   var actWinHeight = currBottom - footerHeight;

          //   console.log(acctItemHeight, currBottom, footerHeight, actWinHeight)
            
          //   if(acctItemHeight > actWinHeight){
          //     var diff = acctItemHeight - actWinHeight;
          //     window.scrollBy(0, diff + 50);
          //   }
          // }
          

          if(acctItemDown[i].classList.contains("active")){
            minimizeMax[i].style.border = "1px solid #FFC107";
            minimizeMin[i].style.border = "1px solid #FFC107";
          }
          else{
            minimizeMax[i].style.border = "0.1em solid #fff";
            minimizeMin[i].style.border = "1px solid #f0f0f0";
          }
        }
    }


  // Account Menu List
  var acctMenu = document.querySelectorAll(".account_menu");
    for(let i = 0; i <= acctMenu.length; i++);

  var acctMenuIcon = document.querySelectorAll(".acct_menu_icon");
    for(let i = 0; i <= acctMenuIcon.length; i++){
      acctMenuIcon[i].onclick = (ev) => {
        
        acctMenu[i].classList.add("active");

        if(window.innerHeight - ev.clientY > 245){
            acctMenu[i].style.top = "55px";
        }
        else{
            acctMenu[i].style.top = "-220px";
        }

        // On click of window
        window.onmouseup = () => {
            acctMenu[i].classList.remove("active");
        }

        // On window scroll
        window.onscroll = () => {
            acctMenu[i].classList.remove("active");
        }
      }
    }

    
  // Permanently Delete Account
  function deleteAcct(event, str){
    event.preventDefault();

    // Open background and dialog box
    var deleteDialog = document.querySelector(".delete_popup");
        deleteDialog.classList.add("active");

    var delete_acct = document.getElementById("del_acct");
        delete_acct.classList.add("active");


    // Cancel Delete
    var cancelDelete = document.getElementById("cancel_delete");
        cancelDelete.onclick = () => {
          deleteDialog.classList.remove("active");
          delete_acct.classList.remove("active");
        }

    // OK Delete
    var okDelete = document.getElementById("ok_delete");
        okDelete.onclick = () => {
          deleteDialog.classList.remove("active");
          delete_acct.classList.remove("active");

          // Make HttpXML Request
          var xhr = new XMLHttpRequest();
          xhr.open("GET", "ajax/delete-acct.php?acct_id="+str, true);
          xhr.onload = function(){
              if(this.status == 200){
                location.href = "index.php";

                affirmText.innerHTML = "You have successfully deleted this account! Account has been permanently lost";
                affirmPopup.classList.add("active");

                setTimeout(function(){
                    affirmPopup.classList.remove("active");
                }, 3000);
                
              }
          }
          xhr.send();
        }

    // On click of window, close delete dialog
    window.onmouseup = (e) => {
        if(e.target !== delete_acct && e.target.parentNode !== delete_acct){
          deleteDialog.classList.remove("active");
          delete_acct.classList.remove("active");
        }
    }

    // On window scroll, close dialog
    window.onscroll = () => {
        deleteDialog.classList.remove("active");
        delete_acct.classList.remove("active");
    }
  }

  // Reset Account
  function resetAcct(event , str){
    event.preventDefault();

    // Open background and dialog box
    var resetDialog = document.querySelector(".reset_popup");
        resetDialog.classList.add("active");

    var reset_acct = document.getElementById("reset_acct");
        reset_acct.classList.add("active");

    
    // Cancel reset
    var cancelReset = document.getElementById("cancel_reset");
        cancelReset.onclick = () => {
          resetDialog.classList.remove("active");
          reset_acct.classList.remove("active");
        }

    // OK Reset Account
    var okReset = document.getElementById("ok_reset");
        ok_reset.onclick = () => {
          resetDialog.classList.remove("active");
          reset_acct.classList.remove("active");

          // Make HttpXML Request
          var xhr = new XMLHttpRequest();
          xhr.open("GET", "ajax/reset_acct.php?acct_id="+str, true);
          xhr.onload = function(){
              if(this.status == 200){
                affirmText.innerHTML = "This account has been successfully reset! All values have been set to 0";
                affirmPopup.classList.add("active");

                setTimeout(function(){
                    affirmPopup.classList.remove("active");
                    location.href = "index.php";
                }, 3000);
              }
          }
          xhr.send();
        }

    // On click of window, close reset dialog
    window.onmouseup = (e) => {
        if(e.target !== reset_acct && e.target.parentNode !== reset_acct){
          resetDialog.classList.remove("active");
          reset_acct.classList.remove("active");
        }
    }

    // On window scroll, close dialog
    window.onscroll = () => {
        resetDialog.classList.remove("active");
        reset_acct.classList.remove("active");
    }
  }

  // Disable Account
  function disableAcct(event , str){
    event.preventDefault();

    // Open background and dialog box
    var disableDialog = document.querySelector(".disable_popup");
        disableDialog.classList.add("active");

    var disable_acct = document.getElementById("disable_acct");
        disable_acct.classList.add("active");

    
    // Cancel disable
    var canceldisable = document.getElementById("cancel_disable");
        canceldisable.onclick = () => {
          disableDialog.classList.remove("active");
          disable_acct.classList.remove("active");
        }

    // OK disable Account
    var okdisable = document.getElementById("ok_disable");
        ok_disable.onclick = () => {
          disableDialog.classList.remove("active");
          disable_acct.classList.remove("active");

          // Make HttpXML Request
          var xhr = new XMLHttpRequest();
          xhr.open("GET", "ajax/disable_acct.php?acct_id="+str, true);
          xhr.onload = function(){
              if(this.status == 200){
                affirmText.innerHTML = "You have successfully " + this.responseText + " this account!";
                affirmPopup.classList.add("active");

                setTimeout(function(){
                    affirmPopup.classList.remove("active");
                    location.href = "index.php";
                }, 3000);
              }
          }
          xhr.send();
        }

    // On click of window, close disable dialog
    window.onmouseup = (e) => {
        if(e.target !== disable_acct && e.target.parentNode !== disable_acct){
          disableDialog.classList.remove("active");
          disable_acct.classList.remove("active");
        }
    }

    // On window scroll, close disable dialog
    window.onscroll = () => {
        disableDialog.classList.remove("active");
        disable_acct.classList.remove("active");
    }
  }


  
  </script>