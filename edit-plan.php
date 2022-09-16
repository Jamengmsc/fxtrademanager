<?php
   $caption = "Edit Plan";
   
   include "partials/header.php";
   include "config/check-login.php";
  //  include "mail/mail_constant.php";
?>

<section class="container-fluid px-md-5">
   <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="d-flex flex-column">
         <div class="home-icon d-flex justify-content-start align-items-center">
            <i class="fa fa-edit" style="font-size: 18px; color: #e7781c;"></i>
            <span class="ml-2" style="font-size: 16px; color: #e7781c;">Edit Plan</span>
         </div>
      </div>

      <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
         <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

         <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
      </div>
   </div>
</section>

<?php
  if(isset($_GET['plan_id'])){
    $plan_id = $_GET['plan_id'];

    $plan = mysqli_query($conn, "SELECT * FROM compounding WHERE id=$plan_id");
    if(mysqli_num_rows($plan) == 1){
      $plan_row = mysqli_fetch_assoc($plan);
    }
  }

  if($plan_row['start_date'] < 1){
    $day_diff = 0;
  }

  // Calculate days ellapsed if there is a start date
  else{
    $date_start = $plan_row['start_date'];

    $date_now = strtotime(date("Y-m-d"));
    $date_start = strtotime($date_start);
  
    // Check if days ellapsed has Saturday or Sunday in it
    $date_diff = $date_now - $date_start;
    $day = ($date_diff / 86400) + 1;

    $day_array = array();


    // LOOP for actual days traded    
    for($c = 1; $c <= $day; $c++){
      $date_start = strtotime($plan_row['start_date']);
      $trade_date = strtotime(+$c - 1  . " days", $date_start);
  
      $day_of_week = date("l", strtotime(+$c - 1 . " days", $date_start)); // Sunday, Monday, Tuesday etc...
  
      if($day_of_week !== "Saturday" && $day_of_week !== "Sunday"){
        $day_array[] = $c;
      }
    }
  
    $day; // Total days from start date to today's date
    $day_actual = count($day_array); // Number of days from start date to today's date exluding weekends
  
    $day_diff = $day - $day_actual;
  }
?>


<section class="mb-0">
  <div class="container-fluid px-md-5">
    <div id="new_account" class="bg-dark p-1 pb-3 rounded-lg">
      <form method="post" class="new_account" autocomplete="off">
        <!-- Account Information -->
        <h6 class="p-3 mb-n1 text-light" style="font-size:18px">Update Plan</h6>
        <?php
          if($plan_row['active'] == 1 && date("Y-m-d") >= $plan_row['start_date']){
            echo "<div class='text-gray p-3 mt-n4' style='font-size:10px'><span class='text-warning'>Note: </span>This plan is an active plan, and there is a limit to what can be updated</div>";
          }
        ?>

        <?php
          if(isset($_SESSION['update-error'])){
            echo "<div class='text-warning font-italic text-right mr-3 mt-n2 mb-3' style='font-size:12px'>"  . $_SESSION['update-error'] . "</div>";
            unset($_SESSION['update-error']);
          }
        ?>

        <div class="row px-3">
          <div class="col-6">
            <label for="plan">Plan Name:</label>
            <input type="text" name="plan" id="plan_name" class="w-100 mb-3" value="<?= $plan_row['plan_name'] ?>">
          </div>

          <div class="col-6">
            <label for="date">Start Date:</label>
            <input type="date" name="date" id="start_date" class="w-100" style="font-size:14px" value="<?= $plan_row['start_date'] ?>" <?php if($plan_row['active'] == 1 && date("Y-m-d") >= $plan_row['start_date']) {echo "disabled";} ?>>
          </div>
        </div>

        <div class="col-12">
          <label for="acct">Account No: <span class="small font-italic text-gray">(Only Live Accounts)</span></label>
          <select name="acct" id="acct" class="w-100" onchange="getBroker(this.value)" <?php if($plan_row['active'] == 1 && date("Y-m-d") >= $plan_row['start_date']) {echo "disabled";} ?>>
            <?php
              $accts = mysqli_query($conn, "SELECT acct_no FROM compounding WHERE user_id=$id GROUP BY acct_no");

              if(mysqli_num_rows($accts) > 0){
                while($accts_row = mysqli_fetch_assoc($accts)){
                  ?>
                    <option value="<?php echo $accts_row['acct_no'] ?>" <?php if($accts_row['acct_no'] == $plan_row['acct_no']){ echo "selected"; } ?>><?php echo $accts_row['acct_no'] ?></option>
                  <?php
                }
              }
            ?>
          </select>
        </div>

        <div class="col-12">
          <label for="broker">Account Broker:</label>
          <input type="text" name="broker" class="w-100 mb-3" id="acct_broker" value="<?= $plan_row['broker'] ?>" <?php if($plan_row['active'] == 1 && date("Y-m-d") >= $plan_row['start_date']) {echo "disabled";} ?>>
        </div>

        <!-- CHECK IF THE NUMBER OF DAYS ELLAPSED IN THE PLAN IS UP TO 50% -->
        <?php
          $date_now = date("Y-m-d");
          $date_now = strtotime($date_now);
          $date_start = strtotime($plan_row['start_date']);

          if($date_start <= $date_now){
            $date_diff = $date_now - $date_start;
            $day = ($date_diff / 86400) + 1;

            $percent = ($day / $plan_row['duration']) * 100;
          }
          else{
            $percent = 0;
          }

          if($date_start < 1){
            $percent = 0;
          }
        ?>

        <div class="row px-3">
          <div class="col-6" id="open_bal">
            <label for="start_amount">Start Amount:</label>
            <input type="text" name="amount" id="new_acct_bal" class="w-100" value="<?= $plan_row['principal'] ?>" <?php if($percent >= 50){ echo "disabled"; } ?>>
          </div>

          <div class="col-6">
            <label for="duration">Duration: <span class="small font-italic text-gray">(Days)</span></label>
            <div class="duration" style="position:relative">
              <input type="text" class="w-100" id="duration" name="duration" value="<?= $plan_row['duration'] ?>" <?php if($percent >= 50){ echo "disabled"; } ?>>

              <i class="fa fa-times close_custom d-none"></i>
            </div>
          </div>
        </div>

        <div class="row px-3">
          <div class="col-7">
            <label for="rate">Interest Type:</label>
            <select name="interest" id="interest" class="w-100" <?php if($percent >= 50){ echo "disabled"; } ?>>
              <option value="Simple"<?php if($plan_row['interest'] == "Simple"){ echo "selected"; } ?>>Simple</option>
              <option value="Compound"<?php if($plan_row['interest'] == "Compound"){ echo "selected"; } ?>>Compound</option>
            </select>
          </div>

          <div class="col-5">
            <label for="rate">Rate (%):</label>
            <div class="rate" style="position:relative">
              <input type="text" class="w-100" id="rate" name="rate" value="<?= $plan_row['rate'] ?>" <?php if($percent >= 50){ echo "disabled"; } ?>>
              <i class="fa fa-times close_custom d-none"></i>
            </div>
          </div>
        </div>

        <div class="row px-3 mt-3">
          <div class="col-6 d-flex justify-content-start align-items-center">
            <input type="checkbox" name="weekend" class="m-0 mr-1" id="weekend" <?php if($plan_row['weekend'] == 1){ echo "checked"; } else {echo "";} ?> <?php if($day_diff > 0){echo "disabled";} else {echo "";} ?>>

            <span class="text-light font-italic ml-1" style="font-size:12px">Include weekends</span>
          </div>
        </div>
      
        <div class="col-12 mt-2">
          <input type="submit" id="update_plan" name="update" value="Update Plan" class="w-100 py-2 text-dark font-weight-bold bg-warning border-0">
        </div>
      </form>
    </div>
  </div>
</section>




<!-- PHP Codes to Update Plan -->
<?php
  if(isset($_POST['update'])){

    // Check if plan is active, that is, the plan has a valid start date and active = 1
    if($plan_row['active'] == 1 && date("Y-m-d") >= $plan_row['start_date']){
      if($percent >= 50){
        $user_id = $id;
        $plan = mysqli_real_escape_string($conn, $_POST['plan']);

        // Check if any weekend days has passed from the active date to today's date of the plan
        if($day_diff == 0){ // No weekends has passed so far
          if(isset($_POST['weekend'])){
            $wkd = 1;
          }
          else{
            $wkd = 0;
          }

          if(empty($plan)){
            $_SESSION['update-error'] = "Enter name of plan"; //Display under the start date checkbox
            echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
            die();
          }
          else{ // If plan name is not empty, then save name to database
            // Insert to database
            $insert_plan = "UPDATE compounding SET
              plan_name = '$plan',
              weekend = $wkd
  
              WHERE id=$plan_id AND user_id=$id
            ";
  
            // Save to database
            $insert_plan_res = mysqli_query($conn, $insert_plan);
  
            if($insert_plan_res == true){
              // Redirect to plan list page and display confirmation message
              $_SESSION['updated-plan'] = "<span class='text-uppercase' style='font-size:13px; font-weight:600'>" .$plan . "</span> was successfully updated";
              echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "'</script>";
            }
            else{
              $_SESSION['update-error'] = "Failed to Update Plan. Try Again";
              echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
            }
          }
        }
        else{ // if one of the weekend days has passed from start date to today's date
          if(empty($plan)){
            $_SESSION['update-error'] = "Enter name of plan"; //Display under the start date checkbox
            echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
            die();
          }
          else{ // If plan name is not empty, then save name to database
            // Insert to database
            $insert_plan = "UPDATE compounding SET
              plan_name = '$plan'
  
              WHERE id=$plan_id AND user_id=$id
            ";
  
            // Save to database
            $insert_plan_res = mysqli_query($conn, $insert_plan);
  
            if($insert_plan_res == true){
              // Redirect to plan list page and display confirmation message
              $_SESSION['updated-plan'] = "<span class='text-uppercase' style='font-size:13px; font-weight:600'>" .$plan . "</span> was successfully updated";
              echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "'</script>";
            }
            else{
              $_SESSION['update-error'] = "Failed to Update Plan. Try Again";
              echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
            }
          }
        }
      }

      else{ // If plan is active and has not gone beyond 50% of the total duration
        $user_id = $id;
        $plan = mysqli_real_escape_string($conn, $_POST['plan']);
        $amount = mysqli_real_escape_string($conn, $_POST['amount']);
        $duration = mysqli_real_escape_string($conn, $_POST['duration']);
        $rate = mysqli_real_escape_string($conn, $_POST['rate']);
        $interest = mysqli_real_escape_string($conn, $_POST['interest']);

        if($day_diff == 0){ // If a weekend day has not passed
          if(isset($_POST['weekend'])){
            $wkd = 1;
          }
          else{
            $wkd = 0;
          }

          //  Check for empty fields
          if(empty($plan) || empty($amount) || empty($duration) || empty($rate) || empty($interest)){
            $_SESSION['update-error'] = "Incomplete fields! Cannot update plan";
            echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
            die();
          }
          else{
            // Insert to database
            $insert_plan = "UPDATE compounding SET
              user_id = $id,
              plan_name = '$plan',
              principal = '$amount',
              rate = $rate,
              interest = '$interest',
              duration = $duration,
              weekend = $wkd

              WHERE id=$plan_id AND user_id=$id
            ";

            // Save to database
            $insert_plan_res = mysqli_query($conn, $insert_plan);

            if($insert_plan_res == true){
              // Redirect to plan list page and display confirmation message
              $_SESSION['updated-plan'] = "<span class='text-uppercase' style='font-size:13px; font-weight:600'>" .$plan . "</span> plan was successfully updated";
              echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "'</script>";
            }
            else{
              $_SESSION['update-error'] = "Failed to Update Plan. Try Again";
              echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
            }
          }
        }
        else{ // If a weekend day has passed
          //  Check for empty fields
          if(empty($plan) || empty($amount) || empty($duration) || empty($rate) || empty($interest)){
            $_SESSION['update-error'] = "Incomplete fields! Cannot update plan"; //Display under the start date checkbox
            echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
            die();
          }
          else{
            // Insert to database
            $insert_plan = "UPDATE compounding SET
              user_id = $id,
              plan_name = '$plan',
              principal = '$amount',
              rate = $rate,
              interest = '$interest',
              duration = $duration

              WHERE id=$plan_id AND user_id=$id
            ";

            // Save to database
            $insert_plan_res = mysqli_query($conn, $insert_plan);

            if($insert_plan_res == true){
              // Redirect to plan list page and display confirmation message
              $_SESSION['updated-plan'] = "<span class='text-uppercase' style='font-size:13px; font-weight:600'>" .$plan . "</span> plan was successfully updated";
              echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "'</script>";
            }
            else{
              $_SESSION['update-error'] = "Failed to Update Plan. Try Again";
              echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
            }
          }
        }

        //  Check for empty fields
        if(empty($plan) || empty($amount) || empty($duration) || empty($rate) || empty($interest)){
          $_SESSION['update-error'] = "Incomplete fields! Cannot update plan"; //Display under the start date checkbox
          echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
          die();
        }
        else{
          // Insert to database
          $insert_plan = "UPDATE compounding SET
            user_id = $id,
            plan_name = '$plan',
            principal = '$amount',
            rate = $rate,
            interest = '$interest',
            duration = $duration

            WHERE id=$plan_id AND user_id=$id
          ";

          // Save to database
          $insert_plan_res = mysqli_query($conn, $insert_plan);

          if($insert_plan_res == true){
            // Redirect to plan list page and display confirmation message
            $_SESSION['updated-plan'] = "<span class='text-uppercase' style='font-size:13px; font-weight:600'>" .$plan . "</span> plan was successfully updated";
            echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "'</script>";
          }
          else{
            $_SESSION['update-error'] = "Failed to Update Plan. Try Again";
            echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
          }
        }
      }
    }

    // If plan is not active yet
    else{
      $user_id = $id;
      $plan = mysqli_real_escape_string($conn, $_POST['plan']);
      $acct_no = mysqli_real_escape_string($conn, $_POST['acct']);
      $broker = mysqli_real_escape_string($conn, $_POST['broker']);
      $amount = mysqli_real_escape_string($conn, $_POST['amount']);
      $duration = mysqli_real_escape_string($conn, $_POST['duration']);
      $rate = mysqli_real_escape_string($conn, $_POST['rate']);
      $interest = mysqli_real_escape_string($conn, $_POST['interest']);
      $date = mysqli_real_escape_string($conn, $_POST['date']);

      // Include weekends or not in the plan
      if(isset($_POST['weekend'])){
        $wkd = 1;
      }
      else{
        $wkd = 0;
      }

      //  Check for empty fields
      if(empty($plan) || empty($acct_no) || empty($amount) || empty($duration) || empty($rate) || empty($broker) || empty($interest)){
        // Redirect to plan list page and display confirmation message
        $_SESSION['update-error'] = "Incomplete fields! Cannot update plan";
        echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
        die();
      }
      else{
        if($date == ""){ // If no date is selected
          // Insert to database
            $insert_plan = "UPDATE compounding SET
            user_id = $id,
            plan_name = '$plan',
            principal = '$amount',
            rate = $rate,
            interest = '$interest',
            duration = $duration,
            broker = '$broker',
            acct_no = $acct_no,
            weekend = $wkd

            WHERE id=$plan_id AND user_id=$id
          ";

          // Save to database
          $insert_plan_res = mysqli_query($conn, $insert_plan);

          if($insert_plan_res == true){
            // Redirect to plan list page and display confirmation message
            $_SESSION['updated-plan'] = "<span class='text-uppercase' style='font-size:13px; font-weight:600'>" .$plan . "</span> was successfully updated";
            echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "'</script>";
          }
          else{
            $_SESSION['update-error'] = "Failed to Update Plan. Try Again";
            echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
          }
        }

        elseif($date < date("Y-m-d")){ // If date selected is less than current date, becomes invalid
          $_SESSION['update-error'] = "Selected date is not valid. Choose a future date ";
          echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
          die();
        }

        else{ // If date selected is valid date
          $date = mysqli_real_escape_string($conn, $_POST['date']);
          $selected_date = date("l", strtotime($date)); //Checks for Saturdays and Sundays...

          if($wkd == 0){ // If plan does not include weekends
            if($selected_date == "Saturday" || $selected_date == "Sunday"){
              $_SESSION['update-error'] = "Plan does not include weekends, so you can not select " . $selected_date;
              echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
              die();
            }
            else{ // If date selected does not fall on a weekend
              // Insert to database
              $insert_plan = "UPDATE compounding SET
                user_id = $id,
                plan_name = '$plan',
                principal = '$amount',
                rate = $rate,
                interest = '$interest',
                duration = $duration,
                broker = '$broker',
                start_date = '$date',
                acct_no = $acct_no,
                weekend = $wkd,
                active = 1

                WHERE id=$plan_id AND user_id=$id
              ";

              // Save to database
              $insert_plan_res = mysqli_query($conn, $insert_plan);

              if($insert_plan_res == true){
                // Redirect to plan list page and display confirmation message
                $_SESSION['updated-plan'] = "<span class='text-uppercase' style='font-size:13px; font-weight:600'>" .$plan . "</span> was successfully updated";
                echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "'</script>";
              }
              else{
                $_SESSION['update-error'] = "Failed to Update Plan. Try Again";
                echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
              }
            }
          }

          else{ // If plan includes weekends
            // Insert to database
            $insert_plan = "UPDATE compounding SET
              user_id = $id,
              plan_name = '$plan',
              principal = '$amount',
              rate = $rate,
              interest = '$interest',
              duration = $duration,
              broker = '$broker',
              start_date = '$date',
              acct_no = $acct_no,
              weekend = $wkd,
              active = 1

              WHERE id=$plan_id AND user_id=$id
            ";

            // Save to database
            $insert_plan_res = mysqli_query($conn, $insert_plan);

            if($insert_plan_res == true){
              // Redirect to plan list page and display confirmation message
              $_SESSION['updated-plan'] = "<span class='text-uppercase' style='font-size:13px; font-weight:600'>" .$plan . "</span> was successfully updated";
              echo "<script>location.href='comp_plan_item.php?plan_id=" . $plan_row['id'] . "'</script>";
            }
            else{
              $_SESSION['update-error'] = "Failed to Update Plan. Try Again";
              echo "<script>location.href='edit-plan.php?plan_id=" . $plan_row['id'] . "'</script>";
            }
          }
        }
      }
    }
  }
?>

<script src="./script.js"></script>
</body>
</html>


<script>
  // Get broker name for selected account
  function getBroker(str){

    if(str == 0){
      return false;
    }
    
    var xhr = new XMLHttpRequest();
    xhr.onload = function(){
      if(this.status == 200){
          document.getElementById("acct_broker").value = this.responseText;
      }
    }
    xhr.open("GET", "ajax/get_broker.php?acct_no=" + str, true);

    xhr.send();
  }

  // Restrict Plan name to only 10 digits
  var planName = document.getElementById("plan_name");

  planName.oninput = () => {
    planName.value = planName.value.substring(0, 10);
  }


  // SHOW CUSTOM DURATION INPUT FIELD
  document.querySelector("#custom_duration").oninput = () => {
    document.querySelector(".custom_duration i").classList.remove("d-none");

    document.querySelector("#custom_duration").value = document.querySelector("#custom_duration").value.substring(0, 5);

    if(document.querySelector("#custom_duration").value == ""){
      document.querySelector(".custom_duration i").classList.add("d-none");
    }

    // Clear input field
    document.querySelector(".custom_duration i").onclick = () => {
      document.querySelector("#custom_duration").value = "";
      document.querySelector(".custom_duration i").classList.add("d-none");
    }
  }



  // SHOW CUSTOM RATE INPUT FIELD
  document.querySelector("#custom_rate").oninput = () => {
    document.querySelector(".custom_rate i").classList.remove("d-none");

    document.querySelector("#custom_rate").value = document.querySelector("#custom_rate").value.substring(0, 3);

    if(document.querySelector("#custom_rate").value == ""){
      document.querySelector(".custom_rate i").classList.add("d-none");
    }

    // Clear input field
    document.querySelector(".custom_rate i").onclick = () => {
      document.querySelector("#custom_rate").value = "";
      document.querySelector(".custom_rate i").classList.add("d-none");
    }
  }


</script>