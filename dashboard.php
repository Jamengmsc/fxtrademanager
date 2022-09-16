<?php
   $caption = "Dashboard";

   include "partials/header.php";
   include "config/check-login.php";

   $acct_id = 41;

  // Get account no from records table on database
  $get_acct_no = mysqli_query($conn, "SELECT acct_no FROM record_acct WHERE id=$acct_id");
  $acct_no = mysqli_fetch_assoc($get_acct_no)['acct_no'];

    // Get full account details from new_account table
  $get_acct_details = mysqli_query($conn, "SELECT * FROM new_account WHERE acct_no=$acct_no");
  if(mysqli_num_rows($get_acct_details) == 1){
    $curr_row = mysqli_fetch_array($get_acct_details);
  }
?>

<!-- Main section -->
<section class="container-fluid px-md-5">
  <div class="d-flex justify-content-between align-items-center">
    <div class="d-flex flex-column">
      <div class="home-icon d-flex justify-content-start align-items-center">
          <i class="fa fa-tachometer" style="font-size: 18px; color: #e7781c;"></i>
          <span class="ml-2" style="font-size: 16px; color: #e7781c;">My Dashboard</span>
      </div>
    </div>

    <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
      <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

      <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
    </div>
  </div>

  <hr class="m-0 my-2">
</section>


<section class="mb-4 acct_details_main" style="position:relative">
  <div class="container-fluid px-md-5">
    <p class="text-dark m-0 small font-weight-bold">Attention: <p class="text-secondary small">See details of daily, weekly and monthly profits, and also know the profit between a selected period of time.</p></p>

    <div class="pre_head">
      <!-- Today's trades total profit -->
      <div class="detail_wrap acct_created small">
        <div class="d-flex flex-column align-items-start">
          <span class="mb-0 text-dark" style="font-size:18px; font-weight:700">Today <?= "<span style='font-size:12px' class='text-dark font-italic small'> (" . date("l") . ")</span>" ?></span>

          <span style="color:#e7781c; font-weight:600"><?= date("d/m/Y") ?></span>
        </div>

        <?php
          $date = date("Y-m-d");

          // Sum of all trades, today
          $today = mysqli_query($conn, "SELECT SUM(profit) as today_total FROM records WHERE acct_id=$acct_id AND record_date='$date'");

          // Count the number of trades
          $today_count = mysqli_query($conn, "SELECT COUNT(position) as today_count FROM records WHERE acct_id=$acct_id AND record_date='$date'");
          while($count_row = mysqli_fetch_assoc($today_count)){
            $trades_today = $count_row['today_count'];
          }

          while($row = mysqli_fetch_assoc($today)){
            $today_total = $row['today_total'];
          }
        ?>

        <div class="d-flex flex-column align-items-end">
          <span class="mt-1" style="font-size:11px; font-weight:600"><span class="text-secondary font-italic">No. of Trades:</span> <span class="text-dark"><?= $trades_today ?></span></span>

          <div class='font-weight-bold text-dark mt-n1 mb-n1' style='color: #e7781c; font-size:18px'>
            <?php
              echo $curr_row['currency'] . number_format($today_total, 2, ".", ",");
            ?>
          </div>
        </div>
      </div>

      <!-- This week's trades profit -->
      <div class="detail_wrap acct_created small">
        <div class="d-flex flex-column align-items-start">
          <span class="font-weight-bold text-dark mb-0" style="color:#e7781c; font-size:18px; font-weight:600">This Week<?= "<span class='text-dark font-italic small'> (week " . date('W') . ")</span>" ?> </span>
          <?php
            $this_sunday = date("d/m/Y", strtotime("sunday 0 week"));
            $today_date = date("d/m/Y");
          ?>
          <span style="color:#e7781c; font-weight:600"><?=  $this_sunday . " ~ " . $today_date ?></span>
        </div>

        <?php
          $sunday = date("Y-m-d", strtotime("sunday 0 week"));
          $date_total = date("Y-m-d");

          $week = mysqli_query($conn, "SELECT SUM(profit) as week_total FROM records WHERE record_date BETWEEN '$sunday' AND '$date_total'");

          while($week_row = mysqli_fetch_assoc($week)){
            $week_total = $week_row['week_total'];
          }

          // Count the number of trades
          $week_count = mysqli_query($conn, "SELECT COUNT(position) as week_count FROM records WHERE record_date BETWEEN '$sunday' AND '$date_total'");
          while($count_row = mysqli_fetch_assoc($week_count)){
            $trades_week = $count_row['week_count'];
          }
        ?>

        <div class="d-flex flex-column align-items-end">
          <span class="mt-1" style="font-size:11px; font-weight:600"><span class="text-secondary font-italic">No. of Trades:</span> <span class="text-dark"><?= $trades_week ?></span></span>

          <div class='font-weight-bold text-dark mt-n1 mb-n1' style='color: #e7781c; font-size:18px'>
            <?php
              echo $curr_row['currency'] . number_format($week_total, 2, ".", ",");
            ?>
          </div>
        </div>
      </div>

      <!-- This month's trades profit -->
      <div class="detail_wrap acct_created small">
        <div class="d-flex flex-column align-items-start">
          <span class="mb-n1 text-dark" style="color:#e7781c; font-size:18px; font-weight:700">This Month</span>
          <span class="" style="color:#e7781c;font-size:17px;font-weight:600"><?= date("F Y") ?></span>
        </div>

        <?php
          $this_month = date("m");
          $this_year = date("Y");

          $month = mysqli_query($conn, "SELECT SUM(profit) as month_total FROM records WHERE MONTH(record_date) = $this_month AND YEAR(record_date) = $this_year");
          while($month_row = mysqli_fetch_assoc($month)){
            $month_total = $month_row['month_total'];
          }

          // Count the number of trades
          $month_count = mysqli_query($conn, "SELECT COUNT(position) as month_count FROM records WHERE MONTH(record_date) = $this_month AND YEAR(record_date) = $this_year");
          while($count_row = mysqli_fetch_assoc($month_count)){
            $trades_month = $count_row['month_count'];
          }
        ?>

        <div class="d-flex flex-column align-items-end">
          <span class="mt-1" style="font-size:11px; font-weight:600"><span class="text-secondary font-italic">No. of Trades:</span> <span class="text-dark"><?= $trades_month ?></span></span>

          <div class='font-weight-bold text-dark m-n1' style='color: #e7781c; font-size:18px'>
            <?php
              echo $curr_row['currency'] . number_format($month_total, 2, ".", ",");
            ?>
          </div>
        </div>
      </div>

      <!-- Profit for a date range, period of time -->
      <div class="detail_wrap acct_created small">
        <div class="d-flex flex-column align-items-start">
          <span class="mb-1 text-dark" style="color:#e7781c; font-size:18px; font-weight:700">Custom <span class="text-danger font-italic" id="custom_err" style="font-size:10px"></span></span>

          <div class="d-block">
            <input onchange="getDateFrom()" type="date" name="dateFrom" class="prof_range_inputs" id="from" placeholder="dd/mm/yyyy"> ~ <input onchange="getDateTo()" type="date" name="dateTo" class="prof_range_inputs" id="to" placeholder="dd/mm/yyyy">
          </div>
        </div>

        <div class="d-flex flex-column align-items-end">
          <span class="mt-1" style="font-size:11px; font-weight:600"><span class="text-secondary font-italic">No. of Trades:</span> <span class="text-dark" id="count_custom">0</span></span>

          <div class='font-weight-bold text-dark m-n1' style='color: #e7781c; font-size:18px'><?= $curr_row['currency'] ?><span id="cust_profit">0.00</span></div>
        </div>
      </div>
    </div>
  </div>
</section>


<script src="./script.js"></script>
</body>
</html>


<script>
  var dateFrom = document.getElementById("from");
  var dateTo = document.getElementById("to");

  function getDateFrom(){
    getPeriodProfit(dateFrom.value, dateTo.value);
  }

  function getDateTo(){
    getPeriodProfit(dateFrom.value, dateTo.value);
  }

  
  function getPeriodProfit(el1, el2){
    document.getElementById("custom_err").innerHTML = "";
    document.getElementById("cust_profit").innerHTML = "0.00";

    if(el1 == "" && el2 !== ""){
      document.getElementById("custom_err").innerHTML = "Empty start date!";
      return false;
    }

    if(el1 !== "" && el2 !== ""){
      if(el1 > el2){
        document.getElementById("custom_err").innerHTML = "Date Order Error!";
        return false;
      }
      else{
        // AJAX request
        var xhr = new XMLHttpRequest();

        xhr.open("GET", "ajax/get_period_amt.php?date_from="+el1 + "&date_to="+el2, true);
        xhr.onload = function(){
          if(this.status == 200){
            var myObj = JSON.parse(this.responseText);
            document.getElementById("cust_profit").innerHTML = myObj[0];
            document.getElementById("count_custom").innerHTML = myObj[1];
          }
        }

        xhr.send();
      }
    }
  }
</script>