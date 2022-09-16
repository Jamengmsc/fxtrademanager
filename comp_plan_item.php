<?php
   $caption = "My Plan";

   include "partials/header.php";
   include "config/check-login.php";

  if(isset($_GET['plan_id'])){
    $plan_id = $_GET['plan_id'];

    $plan_details = mysqli_query($conn, "SELECT * FROM compounding WHERE id=$plan_id AND user_id=$id");
    if(mysqli_num_rows($plan_details) == 1){
      $plan_row = mysqli_fetch_assoc($plan_details);
      $broker = $plan_row['broker'];
    }
  }
?>

<!-- Main section -->
<section class="container-fluid px-md-5">
  <div class="d-flex justify-content-between align-items-center">
    <div class="d-flex flex-column">
      <div class="home-icon d-flex justify-content-start align-items-center">
          <i class="fab fa-microsoft" style="font-size: 20px; color: #e7781c;"></i>
          <span class="ml-2" style="font-size: 16px; color: #e7781c;"><?= $plan_row['plan_name'] ?></span>
      </div>
    </div>

    <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
      <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

      <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
    </div>
  </div>

  <!-- <hr class="m-0 my-2"> -->
</section>

<section class="mt-2 mb-3">
  <?php
    if(isset($_SESSION['updated-plan'])){
      echo "<div class='alert alert-success font-italic' style='font-size:14px'>"  . $_SESSION['updated-plan'] . "</div>";
      unset($_SESSION['updated-plan']);
    }
  ?>

  <div class="plan_details bg-dark p-3">
    <?php
      $weekend = $plan_row['weekend'];
      $simple_daily = $plan_row['principal'] * $plan_row['rate'] * 0.01;
      $new_principal = $plan_row['principal'];
      $simple_daily_total = 0;
      $comp_daily_total = $plan_row['principal'];

      if($plan_row['start_date'] < 1){
        $start_date = date("Y-m-d");
      }
      else{
        $start_date = $plan_row['start_date'];
      }


      // Start loop from start date in DB else, use today's date to start loop
      for($i = 1; $i <= $plan_row['duration']; $i++){
        // LOOP TO GET THE SUM TOTAL FOR SIMPLE/COMPOUND INTERESTS
        $date = strtotime($start_date);
        $trade_date = $date;
        $trade_date = strtotime(+$i - 1 . " days", $trade_date);
        $trade_date = date("d/m/y", $trade_date); // Displays the dates from start of trade day(date)

        $day = date("l", strtotime(+$i - 1 . " days", $date)); // Checks for weekends

        if($day == "Saturday" || $day == "Sunday"){
          if($weekend == 0){
            $new_principal = $new_principal; // The principal value remains the same
          }
          elseif($weekend == 1){
            if($plan_row['interest'] == "Compound"){
              $new_principal = ($new_principal * $plan_row['rate'] * 0.01) + $new_principal;
            }
            else{
              $new_principal = $simple_daily + $new_principal;
            }
          }
        }
        elseif($plan_row['interest'] == "Compound"){
          $new_principal = ($new_principal * $plan_row['rate'] * 0.01) + $new_principal;
        }
        else{ // If interest type is simple
          $new_principal = $simple_daily + $new_principal;
        }
      }
      
      $period_total = $new_principal - $plan_row['principal'];
      $table_total = $new_principal;
    ?>

    <h6 class="mb-1 text-gray">Account Details</h6>
    <div class="row no-gutters">
      <div class="col-4">
        <div class="col_item"><span>Interest Type</span> <span class="mt-n1"><?php echo $plan_row['interest'] ?></span></div>
      </div>
      <div class="col-4">
        <div class="col_item"><span>Interest Rate</span> <span class="mt-n1"><?= $plan_row['rate'] . "%" ?></span></div>
      </div>
      <div class="col-4">
        <div class="col_item"><span>Start Amount</span><span class="mt-n1"><?= "$" . number_format($plan_row['principal'], 2, ".", ",") ?></span></div>
      </div>
      <div class="col-4">
        <div class="col_item"><span>Account No</span> <span class="mt-n1"><?= $plan_row['acct_no'] ?></span></div>
      </div>
      <div class="col-4">
        <div class="col_item"><span>Broker Name</span> <span class="mt-n1"><?= $plan_row['broker'] ?></span></div>
      </div>
      <div class="col-4 d-flex justify-content-start flex-column align-items-start pt-1">
        <?php
          if($weekend == 1){
            if($plan_row['active'] == 1 && date("Y-m-d") >= $plan_row['start_date']){
              ?>
                <div class="text-warning font-italic p-0" style="font-size:10px"><i class="far fa-check-square" style="font-size:11px"></i> Active</div>
              <?php
            }
            else{
              ?>
                <div class="text-secondary font-italic p-0" style="font-size:10px"><i class="far fa-square" style="font-size:11px"></i> Active</div>
              <?php
            }

            ?>
              <div class="text-warning font-italic p-0" style="font-size:10px"><i class="far fa-check-square" style="font-size:11px"></i> Weekends</div>
            <?php
          }
          // If weekend is not included
          else{
            if($plan_row['active'] == 1 && date("Y-m-d") >= $plan_row['start_date']){
              ?>
                <div class="text-warning font-italic p-0" style="font-size:10px"><i class="far fa-check-square" style="font-size:11px"></i> Active</div>
              <?php
            }
            else{
              ?>
                <div class="text-secondary font-italic p-0" style="font-size:10px"><i class="far fa-square" style="font-size:11px"></i> Active</div>
              <?php
            }

            ?>
              <div class="text-secondary font-italic p-0" style="font-size:10px"><i class="far fa-square" style="font-size:11px"></i> Weekends</div>
            <?php
          }
        ?>
      </div>
    </div>

    <h6 class="mb-1 mt-3 text-info">Progress Statistics</h6>
    <div class="row no-gutters">
      <div class="col-4">
        <div class="col_item act"><span>Start Date</span> 
          <span class="text-white mt-n1">
            <?php
              if($plan_row['start_date'] > 1){
                echo date("d/m/y", strtotime($plan_row['start_date']));
              }
              else{
                echo "Not Set";
              }
            ?>
          </span>
        </div>
      </div>
      <div class="col-4">
        <div class="col_item act">
          <span>End Date</span>
          <span class="text-light mt-n1">
            <?php
              // If plan includes weekends but ends on Saturday, move end date to Friday
              if($day == "Saturday" && $weekend == 0){
                $str_date = strtotime($start_date);
                $end_date = date("d/m/y", strtotime(+$plan_row['duration'] - 2 . " day", $str_date));
                echo $end_date;
              }
              // If plan includes weekends but ends on Sunday, move end date to Friday
              elseif($day == "Sunday" && $weekend == 0){
                $str_date = strtotime($start_date);
                $end_date = date("d/m/y", strtotime(+$plan_row['duration'] - 3 . " day", $str_date));
                echo $end_date;
              }
              else{ // If plan does not include weekends, so can end on any day of the week
                echo $trade_date;
              }
            ?>
          </span>
        </div>
      </div>
      <div class="col-4">
        <div class="col_item act">
          <span>Days Ellapsed</span> <span class="text-light mt-n1">
            <?php
              $date_now = date("Y-m-d");

              $date_now = strtotime($date_now);
              $date_start = strtotime($start_date);

              if($date_now < $date_start){
                echo "0";
              }
              else{
                $date_diff = $date_now - $date_start;
                $day = ($date_diff / 86400) + 1;

                if($plan_row['active'] == 1){
                  if($weekend == 0){
                    if($day > $plan_row['duration']){
                      $day = $plan_row['duration'];
                    }

                    $day_array = array(); // Declare array

                    // LOOP for actual days traded    
                    for($c = 1; $c <= $day; $c++){
                      $date_start = strtotime($plan_row['start_date']);
                      $trade_date = strtotime(+$c - 1  . " days", $date_start);

                      $day_of_week = date("l", strtotime(+$c - 1 . " days", $date_start)); // Sunday, Monday, Tuesday etc...

                      if($day_of_week !== "Saturday" && $day_of_week !== "Sunday"){
                        $day_array[] = $c;
                      }
                    }

                    if($day > $plan_row['duration']){
                      echo $plan_row['duration'] . " <span class='text-gray font-italic'>(" . count($day_array) . ")</span>";
                    }
                    else{
                      echo $day . " <span class='text-gray font-italic'>(" . count($day_array) . ")</span>";
                    }
                  }

                  // If weekends are included. that is, $weekend == 1
                  else{
                    if($day > $plan_row['duration']){
                      echo $day = $plan_row['duration'];
                    }
                    else{
                      echo $day;
                    }
                  }
                }
                else{
                  echo "0";
                }
              }
            ?>
        <span class="text-italic">of</span> <span class="text-warning"><?= $plan_row['duration'] ?></span></span></div>
      </div>
      <div class="col-4">
        <div class="col_item act"><span>Expected Profit</span><span class="text-light mt-n1">
          <?php
            // Get broker's withdrawal rate
            $broker_withdrawal = mysqli_query($conn, "SELECT withdraw_rate FROM new_account WHERE broker='$broker' AND acct_type='Live'");
            $row = mysqli_fetch_assoc($broker_withdrawal);
            $brokerRate = $row['withdraw_rate'];

            if($plan_row['start_date'] > 1){
              echo "$" . number_format($new_principal - $plan_row['principal'], 2, ".", ",");
              echo "<div class='small mt-n1 text-warning'>(&#8358;" . number_format(($new_principal - $plan_row['principal']) * $brokerRate, 2, ".", ",") . ")</div>";
            }
            else{
              echo "$0.00";
            }
          ?>
        </span></div>
      </div>
      <div class="col-4">
        <div class="col_item act">
          <span>Profit/Loss ($)</span>
          <span class="text-light mt-n1">
            <?php
              $first_day = $plan_row['start_date'];
              $curr_day = date("Y-m-d");

              $curr_prof = mysqli_query($conn, "SELECT SUM(profit) AS curr_profit FROM compounding_items WHERE item_date BETWEEN '$first_day' AND '$curr_day' AND plan_id=$plan_id AND user_id=$id");
              while($daily_row = mysqli_fetch_assoc($curr_prof)){
                $total_daily = $daily_row['curr_profit'];
              }

              if($first_day > 1){
                echo "$" . number_format($total_daily, 2, ".", ",");
                echo "<div class='small mt-n1 text-warning'>(&#8358;" . number_format($total_daily * $brokerRate, 2, ".", ",") . ")</div>";
              }
              else{
                echo "$0.00";
              }
            ?>
          </span>
        </div>
      </div>
      <div class="col-4">
        <div class="col_item act">
          <span>Profit/Loss (%)</span>
          <span class="text-light mt-n1">
            <?php
              $profitability = ($total_daily/$period_total) * 100;
              echo number_format($profitability, 2, ".", ",") . "%";
            ?>
          </span>
        </div>
      </div>
    </div>


    <div id="percComplete" class="text-right font-italic mt-1" style="color:lightgray; font-size:8px; font-weight:500; margin-bottom:-11px">
      <!-- 0% Completed -->
    </div>

    <div class="plan_progress rounded">
      <div class="progress_status bg-warning" style="width:65%; height:100%"></div>
    </div>
  </div>
</section>


<!-- SESSION confirmation messages -->
<div class="confirmations">
  <?php
    if(isset($_SESSION['trade-fail'])){
      echo "<div class='alert alert-danger alert-dismissible font-italic mb-3 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['trade-fail'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
      unset($_SESSION['trade-fail']);
    }

    if(isset($_SESSION['trade-success'])){
      echo "<div class='alert alert-success alert-dismissible font-italic mb-3 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['trade-success'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
      unset($_SESSION['trade-success']);
    }

    if(isset($_SESSION['start-date'])){
      echo "<div class='alert alert-success alert-dismissible font-italic mb-3 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['start-date'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
      unset($_SESSION['start-date']);
    }

    if(isset($_SESSION['start-date-err'])){
      echo "<div class='alert alert-danger alert-dismissible font-italic mb-3 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['start-date-err'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
      unset($_SESSION['start-date-err']);
    }

    if(isset($_SESSION['reset-failed'])){
      echo "<div class='alert alert-danger alert-dismissible font-italic mb-3 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['reset-failed'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
      unset($_SESSION['reset-failed']);
    }

    if(isset($_SESSION['reset-success'])){
      echo "<div class='alert alert-success alert-dismissible font-italic mb-3 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['reset-success'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
      unset($_SESSION['reset-success']);
    }
  ?>
</div>
  
<section class="plan_table">
  <!-- Buttons -->
  <div class="d-flex justify-content-between align-items-end px-3 mb-2">
    <a href="<?php echo SITEURL ?>comp_plan.php" class="mb-0 text-dark font-italic" style="color: #e7781c;font-size:15px; font-weight:600">My Plans &raquo;</a>
    
    <div class="d-flex align-items-center mr-n1 mb-2">
      <?php 
        if($plan_row['active'] == 1){
          $today = date("Y-m-d");
          $trade_day = date("Y-m-d", strtotime($plan_row['start_date'])); //Checks for weekends

          if($plan_row['active'] == 1){
          ?>
            <div id="new_plan_trade" onclick="newPlanTradeForm(event)" title="Add Trade"><i class="fa fa-plus"></i></div>
          <?php
          }
          else{
            ?>
              <div id="new_start_date" onclick="pickStartDate()" title="Select Start Date"><i class="far fa-calendar"></i></div>
            <?php
          }
        }
        else{
          ?>
            <div id="new_start_date" onclick="pickStartDate()" title="Select Start Date"><i class="far fa-calendar"></i></div>
          <?php
        }
      ?>

      <div id="equity_curve" class="curve_chart" title="Show Equity Curve" onclick="showEquityCurve()"><i class="fa fa-line-chart"></i></div>

      <a id="plan_list" href="" title="Reset Plan" onclick="resetPlan(event, <?php echo $plan_row['id'] ?>)"><i class="fa fa-rotate-left"></i></a>

      <a id="plan_list" href="<?= SITEURL ?>edit-plan.php?plan_id=<?= $plan_row['id'] ?>" title="Edit Plan"><i class="fa fa-pen"></i></a>
      <a id="plan_list" href="" onclick="deletePlan(event, <?php echo $plan_row['id'] ?>)" title="Delete Plan"><i class="fa fa-trash"></i></a>
    </div>
  </div>

  <!-- Display Equity Curve -->
  <div class="trade_curve bg-dark">
    <div id="equity_curve_chart" class="w-100">
      <h5 class="m-0 text-gray">Trading Plan Curve</h5>

      <?php
        if($plan_row['active'] == 1){
          $chart_data = '';
          $comp_daily = $plan_row['rate'] * 0.01 * $plan_row['principal'];
          $principal = $plan_row['principal'];
          $actual_principal = $plan_row['principal'];
          $simple_actual = $plan_row['principal'] * $plan_row['rate'] * 0.01;

          if($weekend == 0){
            for($count = 1; $count <= $day; $count++){ // Loop through the today days for the plan
              $trade_date = strtotime(+$count - 1  . " days", strtotime($plan_row['start_date']));

              $wkd = date("l", strtotime(+$count - 1 . " days", $date_start));
              if($wkd !== "Saturday" && $wkd !== "Sunday"){ // If it is not weekend days, Saturday or Sunday
                $curve_date = date("Y-m-d", strtotime(+$count - 1 . " days", $date_start));

                // Query Database
                $sumProfit = mysqli_query($conn, "SELECT SUM(profit) as day_prof FROM compounding_items WHERE item_date='$curve_date' AND plan_id=" . $plan_row['id'] . " AND user_id=$id");

                // Loop for array values
                while($prof_row = mysqli_fetch_assoc($sumProfit)){
                  $principal = $prof_row['day_prof'] + $principal;

                  if($plan_row['interest'] == "Simple"){ // If plan runs simple interest rate
                    $actual_principal = $simple_actual + $actual_principal;
                  }
                  else{ // If plan runs compounding interest rate
                    $actual_principal = ($plan_row['rate'] * 0.01 * $actual_principal) + $actual_principal;
                    $total = number_format($actual_principal, 2, ".", ",");
                  }
                  
                  $chart_data .= "{ serial:'" . $count . "', principal:'" . $principal . "', target: '" . $actual_principal . "'}, ";
                }
                
              }
            }
          }
          // If plan runs on weekends also
          else{
            for($count = 1; $count <= $day; $count++){
              $trade_date = strtotime(+$count - 1  . " days", strtotime($plan_row['start_date']));

              $curve_date = date("Y-m-d", strtotime(+$count - 1 . " days", $date_start));

              $sumProfit = mysqli_query($conn, "SELECT SUM(profit) as day_prof FROM compounding_items WHERE item_date='$curve_date' AND plan_id=" . $plan_row['id'] . " AND user_id=$id");

              while($prof_row = mysqli_fetch_assoc($sumProfit)){
                $principal = $prof_row['day_prof'] + $principal;
                
                if($plan_row['interest'] == "Simple"){
                  $actual_principal = $simple_actual + $actual_principal;
                }
                else{
                  number_format($actual_principal, 2, ".", ",");
                  $actual_principal = ($plan_row['rate'] * 0.01 * $actual_principal) + $actual_principal;
                }

                $chart_data .= "{ serial:'" . $count . "', principal:'" . $principal . "', target: '" . $actual_principal . "'}, ";
              }
            }
          }

          $chart_data = substr($chart_data, 0, -2);
    
        }
        else{ // If plan is not active
          $chart_data = '';
          $chart_data .= "{ serial:'0', principal:'" . $plan_row['principal'] . "', target: '" . $plan_row['principal'] . "'}, ";
        }

        $chart_day = $count - 1;
      ?>

      <div class="chart_details text-left">
        <p class="m-0">Overall <span class="font-italic">profit/loss</span> on <?= "<span style='color:white; font-weight:600; text-decoration:underline'><span id='serial_day'>Day " . $chart_day . "</span></span>" ?> is <span id="prof_loss" class="text-warning font-italic font-weight-bold"><?= "$ " . number_format($principal, 2, ".", ",")  ?></span></p>
        <p class="m-0 text-right">and you are up to target by <span id="prof_diff" class="text-white font-italic" style="font-weight:600"><?= "$ " .number_format($principal - $actual_principal, 2, ".", ",")  ?></span></p>
      </div>
    </div>
  </div>

  <p id="trade_tbl_head" class="m-0 text-dark font-italic mx-3 d-none">Trade Table</p>
  <!-- PLAN TABLE OF DETAILS -->
  <div style="overflow-x:auto">
    <table class="table-sm table-striped">
      <tr>
        <th class="text-warning font-weight-bold font-italic">DAY</th>
        <th class="text-info font-weight-bold font-italic">DATE</th>
        <th>Daily <br> <div class="text-gray font-italic">Target($)</div></th>
        <th class="py-1">Target <br> <div class="text-gray font-italic">Balance($)</div></th>
        <th>Daily <br> <div class="text-gray font-italic">Profit($)</div></th>
        <th>Daily <br> <div class="text-gray font-italic">Balance($)</div></th>
        <th><i class="fa fa-check"></i></th>
      </tr>

      <?php
        $new_principal = $plan_row['principal']; //Initial Principal Amount from DB
        $my_principal = $new_principal;

        // Start loop from start date in DB else, use today's date to start loop
        if($plan_row['start_date'] > 1){
          $start_date = $plan_row['start_date'];
        }
        else{
          $start_date = date("Y-m-d");
        }

        $total_daily = 0;
        $my_simple_daily = 0;
        $my_simple_principal = $plan_row['principal'];
        $simple_daily = $plan_row['principal'] * $plan_row['rate'] * 0.01;
        $simple_principal = $plan_row['principal']; //Simple interest principal amount


        // LOOP
        for($time = 1; $time <= $plan_row['duration']; $time++){
          $date = strtotime($start_date);
          $trade_date = $date;

          $trade_date = strtotime(+$time - 1 . " days", $trade_date);
          $show_date = $trade_date;
          $trade_date = date("d/m/y", $trade_date); //Displays the dates from start of trade day(date)

          $day = date("l", strtotime(+$time - 1 . " days", $date)); //Checks for weekends

          if($day == "Saturday" && $plan_row['weekend'] == 0){ //Check for Saturday and plan excludes weekends
            ?>
              <tr class="weekend <?php if(date("d/m/y") == $trade_date){ echo "weekend_highlight"; } ?>">
                <td><?= "<div class='text-gray font-italic'>" . $time . "</div>" ?></td>
                <td><?= "<div class='text-gray font-italic'>" . $trade_date . "</div>" ?></td>
                <td colspan="3"><div class="font-italic" style="color:#e69926">Saturday</div></td>
                <td>
                  <?php
                    if(date("d/m/y") == $trade_date){
                      $excess = $my_principal - $new_principal;
                      echo "<div class='font-italic text-light'>(" . number_format($excess, 2, ".", ",") . ")</div>";
                    }
                  ?>
                </td>
                <td></td>
              </tr>
            <?php
          }
          elseif($day == "Sunday" && $plan_row['weekend'] == 0){ //Check for Sunday and plan excludes weekends
            ?>
              <tr class="weekend <?php if(date("d/m/y") == $trade_date){ echo "weekend_highlight"; } ?>">
                <td><?= "<div class='text-gray font-italic'>" . $time . "</div>" ?></td>
                <td><?= "<div class='text-gray font-italic'>" . $trade_date . "</div>" ?></td>
                <td colspan="3"><div class="font-italic" style="color:#e69926">Sunday</div></td>
                <td>
                  <?php
                    if(date("d/m/y") == $trade_date){
                      $excess = $my_principal - $new_principal;
                      echo "<div class='font-italic'>(" . number_format($excess, 2, ".", ",") . ")</div>";
                    }
                  ?>
                </td>
                <td></td>
              </tr>
            <?php
          }
          else{ //Check if trading date is today and highlight the row
            ?>
              <tr class="<?php if(date("d/m/y") == $trade_date){ echo "plan_table_highlight"; } ?> <?php if($day == "Saturday" || $day == "Sunday"){ echo "weekend_active"; } ?>" onclick="showTrades(<?php echo $show_date ?>, <?php echo $plan_id ?>)">
                <td><?= $time ?></td>
                <td class="font-italic"><?= $trade_date ?></td>
                <td style="font-weight:500">
                  <?php // Daily Target
                    if($plan_row['interest'] == "Compound"){
                      $new_daily = $new_principal * $plan_row['rate'] * 0.01;
                      echo number_format($new_daily, 2, ".", ",");
                    }
                    else{
                      echo number_format($simple_daily, 2, ".", ",");
                    }
                  ?>
                </td>

                <td style="font-weight:500">
                  <?php // Daily Balances
                    if($plan_row['interest'] == "Compound"){
                      $new_principal = ($new_principal * $plan_row['rate'] * 0.01) + $new_principal;
                      echo number_format($new_principal, 2, ".", ",");
                    }
                    else{
                      $simple_principal = $simple_daily + $simple_principal;
                      echo number_format($simple_principal, 2, ".", ",");
                    }
                  ?>
                </td>

                <td style="font-weight:500">
                  <?php // Total daily made
                    $todays_date = strtotime(+$time - 1 . " days", strtotime($start_date));
                    $date_today = date("Y-m-d");
                    $db_date = date("Y-m-d", $todays_date);


                    $daily_prof = mysqli_query($conn, "SELECT SUM(profit) AS daily_profit FROM compounding_items WHERE item_date='$db_date' AND plan_id=$plan_id AND user_id=$id");
                    while($daily_row = mysqli_fetch_assoc($daily_prof)){
                      $total_daily = $daily_row['daily_profit'];
                      $my_simple_daily = $total_daily;
                    }

                    if($plan_row['interest'] == "Compound"){
                      if($total_daily == 0){
                        if($date_today >= $db_date){
                          echo "--";
                        }
                      }
                      elseif($total_daily < $new_daily){
                        echo "<div class='text-danger'>" . $total_daily . "</div>";
                      }
                      else{
                        echo $total_daily;
                      }
                    }
                    else{ // For simple interest, compare my total and expected daily total
                      if($my_simple_daily == 0){
                        if($date_today >= $db_date){
                          echo "--";
                        }
                      }
                      elseif($my_simple_daily < $simple_daily){
                        echo "<div class='text-danger'>" . $my_simple_daily . "</div>";
                      }
                      else{
                        echo $my_simple_daily;
                      }
                    }
                  ?>
                </td>

                <td style="font-weight:500">
                  <?php // My daily total and principal for compound and simple interest
                    if($plan_row['interest'] == "Compound"){
                      if(!empty($total_daily)){
                        $my_principal = $total_daily + $my_principal;

                        if(date("d/m/y") == $trade_date){
                          if($my_principal < $new_principal){
                            echo "<div class='text-danger'>" . number_format($my_principal, 2, ".", ",") . "</div>";
                            $excess = $my_principal - $new_principal;
                            echo "<div class='font-italic'>(" . number_format($excess, 2, ".", ",") . ")</div>";
                          }
                          else{
                            echo number_format($my_principal, 2, ".", ",");
                            $excess = $my_principal - $new_principal;
                            echo "<div class='font-italic'>(" . number_format($excess, 2, ".", ",") . ")</div>";
                          }
                        }
                        else{
                          if($my_principal < $new_principal){
                            echo "<div class='text-danger'>" . number_format($my_principal, 2, ".", ",") . "</div>";
                          }
                          else{
                            echo number_format($my_principal, 2, ".", ",");
                          }
                        }
                      }
                      if($total_daily == 0){
                        if($date_today > $db_date){
                          echo number_format($my_principal, 2, ".", ",");
                        }
                        elseif($date_today == $db_date){
                          echo "--";
                          $excess = $my_principal - $new_principal;
                          echo "<div class='font-italic'>(" . number_format($excess, 2, ".", ",") . ")</div>";
                        }
                      }
                    }
                    else{ // If plan is simple interest rate
                      if(!empty($my_simple_daily)){
                        $my_simple_principal = $my_simple_daily + $my_simple_principal;

                        if(date("d/m/y") == $trade_date){
                          if($my_simple_principal < $simple_principal){
                            echo "<div class='text-danger'>" . number_format($my_simple_principal, 2, ".", ",") . "</div>";
                            $excess = $my_simple_principal - $simple_principal;
                            echo "<div class='font-italic'>(" . $excess . ")</div>";
                          }
                          else{
                            echo number_format($my_simple_principal, 2, ".", ",");
                            $excess = $my_simple_principal - $simple_principal;
                            echo "<div class='font-italic'>(" . $excess . ")</div>";
                          }
                        }
                        else{
                          if($my_simple_principal < $simple_principal){
                            echo "<div class='text-danger'>" . number_format($my_simple_principal, 2, ".", ",") . "</div>";
                          }
                          else{
                            echo number_format($my_simple_principal, 2, ".", ",");
                          }
                        }
                      }
                      if($my_simple_daily == 0){
                        if($date_today > $db_date){
                          echo number_format($my_simple_principal, 2, ".", ",");
                        }
                        elseif($date_today == $db_date){
                          echo "--";
                          $excess = $my_simple_principal - $simple_principal;
                          echo "<div class='font-italic'>(" . number_format($excess, 2, ".", ",") . ")</div>";
                        }
                      }
                    }
                  ?>
                </td>

                <td>
                  <?php
                    if($plan_row['interest'] == "Compound"){
                      if(!empty($total_daily)){
                        if($my_principal < $new_principal){
                          ?>
                            <i class="fa fa-times text-danger"></i>
                          <?php
                        }
                        else{
                          ?>
                            <i class="fa fa-check text-success"></i>
                          <?php
                        }
                      }
                    }
                    else{
                      if(!empty($my_simple_daily)){
                        if($my_simple_principal < $simple_principal){
                          ?>
                            <i class="fa fa-times text-danger"></i>
                          <?php
                        }
                        else{
                          ?>
                            <i class="fa fa-check text-success"></i>
                          <?php
                        }
                      }
                    }
                  ?>
                </td>
              </tr>
            <?php
          }
        }
      ?>

      <tr>
        <th></th>
        <th>TOTAL</th>
        <th class="py-2">
          <?php echo "$" . number_format($period_total, 2, ".", ","); ?>
        </th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
    </table>
  </div>
</section>

<!-- New Trade -->
<div class="popup_plan_trade">
  <div id="new_trade" class="p-3">
    <div class="bg-light p-1 pb-2 rounded-lg">
      <form action="" method="POST" class="new_trade" autocomplete="off">
        <div class="d-flex justify-content-between align-items-center">
          <h6 class="m-0 p-3" style="font-size:18px;color:#e7781c;">New Plan Trade</h6>

          <i class="fa fa-times mr-3" style="font-size:20px;color:#e7781c;cursor:pointer"></i>
        </div>
      
        <div class="d-flex justify-content-between align-items-center">
          <div class="col-6">
            <label for="acct_no">Account No.</label>
            <select name="acct_no" class="w-100">
              <option value="<?php echo $plan_row['acct_no'] ?>"><?php echo $plan_row['acct_no'] ?></option>
            </select>
          </div>

          <div class="col-6">
            <label for="prev_date">Select Date</label>
            <input type="date" class="w-100 small" name="prev_date">
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-1">
          <div class="col-7">
            <select name="pair" class="w-100 mb-md-0 mb-2">
              <?php
                $get_pair = mysqli_query($conn, "SELECT * FROM currency_pair WHERE active=1 ORDER BY pair ASC");
                if(mysqli_num_rows($get_pair) > 0){
                  while($pair_rows = mysqli_fetch_assoc($get_pair)){
                    ?>
                      <option value="<?php echo $pair_rows['pair'] ?>"><?php echo $pair_rows['pair'] ?></option>
                    <?php
                  }
                }
              ?>
            </select>
          </div>
          <div class="col-5">
            <select name="position" class="w-100 mb-md-0 mb-2">
              <option value="Buy">BUY</option>
              <option value="Sell">SELL</option>
            </select>
          </div>
        </div>
          
        <div class="d-flex justify-content-between align-items-center mt-2">
          <div class="col-5">
            <input type="text" name="lotsize" class="w-100 mb-md-0 mb-2" placeholder="Lotsize...">
          </div>
          <div class="col-7">
            <input type="text" name="profit" class="w-100 mb-md-0 mb-2" placeholder="Profit/Loss...">
          </div>
        </div>

        <div class="col-12">
          <input type="submit" name="submit" value="Add Record" class="w-100 mt-md-0 mt-3 text-dark font-weight-bold bg-warning border-warning">
        </div>
        
        <input type="hidden" name="id" value="<?= $id ?>">
      </form>

      <?php
        // Save data entered
        if(isset($_POST['submit'])){
          $user_id = $id;
          $acct_no = mysqli_real_escape_string($conn, $_POST['acct_no']);
          $position = mysqli_real_escape_string($conn, $_POST['position']);
          $pair = mysqli_real_escape_string($conn, $_POST['pair']);
          $lotsize = mysqli_real_escape_string($conn, $_POST['lotsize']);
          $profit = mysqli_real_escape_string($conn, $_POST['profit']);
          $date_traded = mysqli_real_escape_string($conn, $_POST['prev_date']);


          $today = date("Y-m-d");
          $trade_day = date("l", strtotime($today)); //Checks for weekends
          $selected_date = date("l", strtotime($date_traded)); //Checks for weekends for selected date

          if($selected_date == "Saturday" || $selected_date == "Sunday"){
            if($weekend == 0){
              $_SESSION['trade-fail'] = "Sorry! The selected date is on a weekend";
              echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "' </script>";
              die();
            }
            else{
              // Selected date for previous date.. in case, the trades for that date was not recorded
              if($date_traded == ""){
                $database_date = date("Y-m-d");
              }
              elseif($date_traded > date("Y-m-d")){
                $_SESSION['trade-fail'] = "Sorry! The selected date is a future date";
                echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "' </script>";
                die();
              }
              else{
                $database_date = $date_traded;
              }
            }
          }

          // IF IT IS NOT A WEEKEND
          else{
            // Selected date for previous date.. in case, the trades for that date was not recorded
            if($date_traded == ""){
              $database_date = date("Y-m-d");
            }
            elseif($date_traded > date("Y-m-d")){
              $_SESSION['trade-fail'] = "Sorry! The selected date is a future date";
              echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "' </script>";
              die();
            }
            else{
              $database_date = $date_traded;
            }
          }



          if($trade_day == "Saturday" || $trade_day == "Sunday"){
            if($plan_row['weekend'] == 0){ // If it is a weekend but plan does not include weekends
              $_SESSION['trade-fail'] = "Sorry! cannot add trades for this plan on a weekend";
              echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "' </script>";
              die();
            }
            elseif($plan_row['weekend'] == 1){ // If it is a weekend and plan includes weekends
              if($acct_no !== "" && $position !=="" && $lotsize !== "" && $profit !== "" && $pair !== ""){
                // Add record to database
                $add = "INSERT INTO compounding_items SET
                    plan_id = $plan_id,
                    user_id = $user_id,
                    acct_no = $acct_no,
                    position = '$position',
                    pair = '$pair',
                    lotsize = $lotsize,
                    profit = '$profit',
                    item_date = '$database_date'
                ";
    
                // Query against the database
                $add_res = mysqli_query($conn, $add);
    
                if($add_res == false){
                  $_SESSION['trade-fail'] = "Sorry! Failed to add trade";
                  echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "' </script>";
                }
                else{
                  $_SESSION['trade-success'] = "Trade added successfully";
                  echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "' </script>";
                }
              }
              else{
                $_SESSION['trade-fail'] = "Please, add all form fields";
                echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "' </script>";
              }
            }
          }
          // If it is not a weekend, then add trade
          else{
            if($acct_no !== "" && $position !=="" && $lotsize !== "" && $profit !== "" && $pair !== ""){
              // Add record to database
              $add = "INSERT INTO compounding_items SET
                  plan_id = $plan_id,
                  user_id = $user_id,
                  acct_no = $acct_no,
                  position = '$position',
                  pair = '$pair',
                  lotsize = $lotsize,
                  profit = '$profit',
                  item_date = '$database_date'
              ";
  
              // Query against the database
              $add_res = mysqli_query($conn, $add);
  
              if($add_res == false){
                $_SESSION['trade-fail'] = "Sorry! Failed to add trade";
                echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "' </script>";
              }
              else{
                $_SESSION['trade-success'] = "Trade added successfully";
                echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "' </script>";
              }
            }
            else{
              $_SESSION['trade-fail'] = "Please, add all form fields";
              echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "' </script>";
            }
          }
        }
      ?>
    </div>
  </div>
</div>

<!-- Pick Start Date -->
<div class="popup_start_date">
  <div id="start-date" class="p-3 d-flex justify-content-center align-items-center">
    <form method="post" class="start_date_form">
      <div class="d-flex justify-content-between align-items-center mb-3 mt-1">
        <h6 class="m-0 text-dark">Start Date</h6>
        <i class="fa fa-times text-secondary mr-1 close_start_date"></i>
      </div>

      <input type="date" name="start_date" class="w-100" placeholder="dd/mm/yyyy">
      
      <input type="submit" name="new-date" onclick="saveStartDate(event)" class="mt-2 text-dark" value="Submit">
      <input type="hidden" name="plan_id" value="<?= $plan_row['id'] ?>" class="w-100">
      <input type="hidden" name="plan_weekend" value="<?= $plan_row['weekend'] ?>" class="w-100">
    </form>
  </div>
</div>

<!-- Show All Trades for Clicked Day -->
<div class="popup_show_trade">
  <div id="show_trades" class="bg-dark text-center p-3 border-top border-secondary"></div>
</div>

<!-- Edit Trade Item -->
<div class="popup_edit_trade">
  <div id="edit_trades" class="bg-dark text-center border-top border-secondary"></div>
</div>

<!-- Reset Plan -->
<div class="popup_upd_plan">
   <div id="plan_update" class="p-3">
      <h5 class="font-weight-normal" style="color:#000">Reset Plan</h5>
      <p class="mt-1" style="color:rgba(0,0,0,0.8);">Are you sure you want to reset this plan? All trade records for this plan and will be permanently lost. <br><br> Click on Reset to continue</p>

      <div id="popup_btn">
         <button id="cancel_plan_upd">Cancel</button>
         <button id="ok_plan_upd">Reset</button>
      </div>
   </div>
</div>

<!-- Delete Plan -->
<div class="popup_plan">
   <div id="plan_del" class="p-3">
      <h5 class="font-weight-normal" style="color:#000">Delete Plan</h5>
      <p class="mt-1" style="color:rgba(0,0,0,0.8); line-height:20px;">Are you sure you want to delete this plan?</p>

      <div id="popup_btn">
         <button id="cancel_plan_del">No</button>
         <button id="ok_plan_del">Yes, Delete</button>
      </div>
   </div>
</div>


<script src="./script.js"></script>
</body>
</html>


<!-- JAVASCRIPT CODES -->
<script>
  
  // MORRIS.js Chart
  new Morris.Line({
    element: 'equity_curve_chart',
    data: [{serial:'0', principal:<?= $plan_row['principal'] ?>, target:<?= $plan_row['principal'] ?>}, <?= $chart_data ?>],
    xkey: 'serial',
    ykeys: ['principal', 'target'],
    labels: ['Profit', 'Target'],
    lineWidth: ['2px'],
    pointFillColors: ['orange', '#17A2B8'],
    preUnits: '$ ',
    pointSize: 0,
    smooth: true,
    gridTextColor: ['white'],
    gridTextWeight: ['500'],
    lineColors: ['#eda915', '#17A2B8'],
    resize: true,
    xLabelMargin: 7,
    parseTime: false,
    resize: true
  }).on('click', function(i, row){
    var chartDetails = document.querySelector("#equity_curve_chart .chart_details");
    chartDetails.classList.add("active");

    document.getElementById("serial_day").innerHTML = "Day " + row.serial; // The day clicked on

    let profLoss = row.principal - <?php echo $plan_row['principal'] ?>;
    let n = profLoss.toFixed(2);
    document.getElementById("prof_loss").innerHTML = "$ " +n; // Over all profit/loss


    let profDiff = row.principal - row.target;
    let p = profDiff.toFixed(2);
    document.getElementById("prof_diff").innerHTML = "$ " + p; // Difference of overall profit and targeted profit
  });


  // New Plan trade form
  function newPlanTradeForm(){
    event.preventDefault();

    // Open background and dialog box
    var newPlanTradePopup = document.querySelector(".popup_plan_trade");
        newPlanTradePopup.classList.add("active");

    var newPladeTrade = document.getElementById("new_trade");
        newPladeTrade.classList.add("active");

    // Close Dialog Box
    document.querySelector(".new_trade .fa-times").addEventListener("click", function(){
      newPlanTradePopup.classList.remove("active");
      newPladeTrade.classList.remove("active");
    })
  }

  // Display Trading Plan Curve
  function showEquityCurve(ev){
    var tradeTblHeade = document.querySelector("#trade_tbl_head");
    var equityCurve = document.querySelector(".trade_curve");
  
    tradeTblHeade.classList.toggle("d-none");
    equityCurve.classList.toggle("active");

    // If curve's bottom goes below the footer, scroll up by 10px to get full view of curve
    if(equityCurve.classList.contains("active")){
      var curveBottom = equityCurve.getBoundingClientRect().bottom + 250;
      var currBottom = window.innerHeight;
      var footerHeight = document.querySelector(".footer_menu").getBoundingClientRect().height
      var actWinHeight = currBottom - footerHeight;

      if(curveBottom > actWinHeight){
        var diff = curveBottom - actWinHeight;
        window.scrollBy(0, diff + 10);
      }
    }
  }
  
  // Reset Plan
  function resetPlan(event, str){
    event.preventDefault();

    // Open background and dialog box
    var updatePlanDialog = document.querySelector(".popup_upd_plan");
        updatePlanDialog.classList.add("active");

    var updatePlan = document.getElementById("plan_update");
        updatePlan.classList.add("active");


    // Cancel Delete
    var cancelPlanUpdate = document.getElementById("cancel_plan_upd");
        cancelPlanUpdate.onclick = () => {
        updatePlanDialog.classList.remove("active");
        updatePlan.classList.remove("active");
      }

    // OK Delete
    var okPlanUpdate = document.getElementById("ok_plan_upd");
        okPlanUpdate.onclick = () => {
          updatePlanDialog.classList.remove("active");
          updatePlan.classList.remove("active");

          // Make HttpXML Request
          var xhr = new XMLHttpRequest();
          xhr.open("GET", "ajax/reset-plan.php?plan_id=" + str, true);
          xhr.onload = function(){
              if(this.status == 200){
                location.href="comp_plan_item.php?plan_id=" + str;             
              }
          }
          xhr.send();
        }

    
    // On click of window, close delete dialog
    window.onmouseup = (e) => {
      if(e.target !== updatePlan && e.target.parentNode !== updatePlan){
        updatePlanDialog.classList.remove("active");
        delPlan.classList.remove("active");
      }
    }

    // On window scroll, close dialog
    window.onscroll = () => {
        updatePlanDialog.classList.remove("active");
        UpdatePlan.classList.remove("active");
    }
  }

  // Delete Plan
  function deletePlan(event, str){
    event.preventDefault();

    // Open background and dialog box
    var delPlanDialog = document.querySelector(".popup_plan");
        delPlanDialog.classList.add("active");

    var delPlan = document.getElementById("plan_del");
        delPlan.classList.add("active");


    // Cancel Delete
    var cancelPlanDelete = document.getElementById("cancel_plan_del");
        cancelPlanDelete.onclick = () => {
        delPlanDialog.classList.remove("active");
        delPlan.classList.remove("active");
      }

    // OK Delete
    var okPlanDelete = document.getElementById("ok_plan_del");
      okPlanDelete.onclick = () => {
        delPlanDialog.classList.remove("active");
        delPlan.classList.remove("active");

        // Make HttpXML Request
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "ajax/delete-plan.php?plan_id="+str, true);
        xhr.onload = function(){
          if(this.status == 200){
            location.href = "comp_plan.php";

            affirmText.innerHTML = "You have successfully deleted this trade record! Record has been permanently lost";
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
      if(e.target !== delPlan && e.target.parentNode !== delPlan){
        delPlanDialog.classList.remove("active");
        delPlan.classList.remove("active");
      }
    }

    // On window scroll, close dialog
    window.onscroll = () => {
        delPlanDialog.classList.remove("active");
        delPlan.classList.remove("active");
    }
  }

  // CALENDAR START DATE
  function pickStartDate(){
    var startDatePopup = document.querySelector(".popup_start_date");
    var startDate = document.getElementById("start-date");

    startDatePopup.classList.add("active");
    startDate.classList.add("active");

    document.querySelector(".close_start_date").style.cursor = "pointer";
    // newStartFormWrap.classList.add("active");

    document.querySelector(".close_start_date").onclick = () => {
      startDatePopup.classList.remove("active");
      startDate.classList.remove("active");
    }
  }
  
  function saveStartDate(){
    var startDateForm = document.querySelector(".start_date_form");

    var xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/new_start_date.php", true);
      xhr.onload = function(){
        if(this.status == 200){
          console.log(this.responseText)
        }
      }

      var formData = new FormData(startDateForm);
      xhr.send(formData);
  }

  // SHOW TRADES OF CLICKED ROW OF PLAN
  function showTrades(str, id){

    var showTradesPopup = document.querySelector(".popup_show_trade");
    var showTrade = document.getElementById("show_trades");

    showTradesPopup.classList.add("active");
    showTrade.classList.add("active");

    document.getElementById("show_trades").innerHTML = '<div class="d-flex justify-content-center align-items-center"><i class="fa fa-spinner text-gray fa-spin" style="font-size:24px"></i> <span style="font-size:18px" class="ml-2 text-gray font-italic">Loading...</span></div>';
    
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "./ajax/plan-day-trades.php?d=" + str + "&id=" + id, true);
    xhr.onload = function(){
      if(this.status === 200){
        document.getElementById("show_trades").innerHTML = this.responseText;

        // Close trade item dialog box
        document.querySelector(".dismiss_trades span").addEventListener("click", function(){
          showTradesPopup.classList.remove("active");
          showTrade.classList.remove("active");
        });


        // SELECT CHECKBOX
        var selectAll = document.getElementById("sel_all");
        var checkboxes = document.getElementsByClassName("checked_items");
        var editTradeItem = document.querySelector(".edit_trades");
        var deleteTradeItem = document.querySelector(".delete_trades");

        var checkedList = [];

        for(var checkbox of checkboxes){ 
          checkbox.addEventListener("click", function(){

            if(this.checked == true){
              checkedList.push(this.value);

              if(checkedList.length > 0){
                editTradeItem.classList.add("active");
                deleteTradeItem.classList.add("active");
              }

              if(checkedList.length > 1){
                editTradeItem.classList.remove("active");
                deleteTradeItem.classList.remove("active");
              }      
            }
            else{ // if the element is unchecked, remove it from the checked list array
              checkedList.pop(this.value);

              if(checkedList.length < 1){
                editTradeItem.classList.remove("active");
                deleteTradeItem.classList.remove("active");
              }

              if(checkedList.length == 1){
                editTradeItem.classList.add("active");
                deleteTradeItem.classList.add("active");
              }
            }
          })
        } // End of for statement

        // Delete Trade Item(s)
        document.querySelector(".delete_trades").addEventListener("click", function(){
          var xhr = new XMLHttpRequest();
          xhr.open("GET", "./ajax/delete-trade-item.php?item=" + checkedList, true);
          xhr.onload = function(){
            if(this.status == 200){
              showTrades(str, id);
            }
          }
          xhr.send();
        })


        // Open Edit Trade Item(s) Dialog and Save Update
        document.querySelector(".edit_trades").addEventListener("click", function(){
          var editTradesPopup = document.querySelector(".popup_edit_trade");
          var editTrades = document.getElementById("edit_trades");

          editTradesPopup.classList.add("active");
          editTrades.classList.add("active");

          document.getElementById("edit_trades").innerHTML = '<div class="d-flex justify-content-center align-items-center"><i class="fa fa-spinner text-gray fa-spin" style="font-size:20px"></i> <span style="font-size:16px" class="ml-2 text-gray font-italic">Loading...</span></div>';

          var xhr = new XMLHttpRequest();
          xhr.open("GET", "./ajax/edit-trade-item.php?item=" + checkedList, true);
          xhr.onload = function(){
            if(this.status == 200){
              document.getElementById("edit_trades").innerHTML = this.responseText;

              // Close Edit Trade Item Dialog box
              document.querySelector("#edit_trades .dismiss_edit").addEventListener("click", function(){
                editTradesPopup.classList.remove("active");
                editTrades.classList.remove("active");
              });



              // Save Changes of edited trade item
              document.getElementById("update-trade").addEventListener("click", function(event){
                event.preventDefault();

                var tradeItemForm = document.querySelector("#edit_trades form"); // Form for form data

                var xhr = new XMLHttpRequest();
                xhr.open("POST", "./ajax/update-trade-item.php", true);
                xhr.onload = function(){
                  if(this.status == 200){
                    showTrades(str, id);

                    editTradesPopup.classList.remove("active");
                    editTrades.classList.remove("active");
                  }
                }

                var formData = new FormData(tradeItemForm);
                xhr.send(formData);
              })
            }
          }
          xhr.send();
        })

      }
    }
    
    xhr.send();
  }


    // PLAN PROGRESS STATUS BAR
  var progressStatus = document.querySelector(".progress_status");
  var day = <?php echo $count - 1 ?>;
  var period = <?php echo $plan_row['duration'] ?>;
  var percent = day / period * 100;
  var progressPercent = percent.toFixed(0);
  progressStatus.style.width = progressPercent + "%";
  document.getElementById("percComplete").innerHTML = progressPercent + "% Completed";

</script>