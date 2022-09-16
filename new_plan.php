<?php
   $caption = "Add Plan";
   
   include "partials/header.php";
   include "config/check-login.php";
   include "mail/mail_constant.php";
?>

<section class="container-fluid px-md-5">
   <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="d-flex flex-column">
         <div class="home-icon d-flex justify-content-start align-items-center">
            <i class="fa fa-plus-square" style="font-size: 18px; color: #e7781c;"></i>
            <span class="ml-2" style="font-size: 16px; color: #e7781c;">Add New Plan</span>
         </div>
      </div>

      <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
         <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

         <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
      </div>
   </div>
</section>


<section class="mb-0">
  <div class="container-fluid px-md-5">
    <div id="new_account" class="bg-dark p-1 pb-3 rounded-lg">
      <form method="post" class="new_account" autocomplete="off">
        <!-- Account Information -->
        <h6 class="p-3 mb-1 text-light" style="font-size:18px">New Compounding Account</h6>

        <?php
          if(isset($_SESSION['new-plan'])){
            echo "<div class='text-warning font-italic text-right mr-3 mt-n2' style='font-size:12px'>"  . $_SESSION['new-plan'] . "</div>";
            unset($_SESSION['new-plan']);
          }
        ?>

        <div class="col-12">
          <label for="plan">Plan Name:</label>
          <input type="text" name="plan" id="plan_name" class="w-100 mb-3" placeholder="Plan Name...">
        </div>

        <div class="col-12">
          <label for="acct">Account No: <span class="small font-italic text-gray">(Only Live Accounts)</span></label>
          <select name="acct" id="acct" class="w-100" onchange="getBroker(this.value)">
            <option value="0">-- Select Account --</option>
            <?php
              $accts = mysqli_query($conn, "SELECT * FROM new_account WHERE user_id=$id AND acct_type='Live'");
              if(mysqli_num_rows($accts) > 0){
                while($accts_row = mysqli_fetch_assoc($accts)){
                  ?>
                    <option value="<?php echo $accts_row['acct_no'] ?>"><?php echo $accts_row['acct_no'] ?></option>
                  <?php
                }
              }
            ?>
          </select>
        </div>

        <div class="col-12">
          <label for="broker">Account Broker:</label>
          <input type="text" name="broker" class="w-100 mb-3" id="acct_broker" placeholder="Broker: Owner of account...">
        </div>

        <div class="row px-3">
          <div class="col-6" id="open_bal">
            <label for="start_amount">Start Amount:</label>
            <input type="text" name="amount" id="new_acct_bal" class="w-100" placeholder="Start Amount...">
          </div>

          <div class="col-6">
            <label for="duration">Duration: <span class="small font-italic text-gray">(Days)</span></label>
            <div class="custom_duration" style="position:relative">
              <input type="text" class="w-100" id="custom_duration" name="cust_duration" placeholder="Duration...">
              <i class="fa fa-times close_custom d-none"></i>
            </div>
          </div>
        </div>

        <div class="row px-3">
          <div class="col-7">
            <label for="rate">Interest Type:</label>
            <select name="interest" id="interest" class="w-100">
              <option value="Simple">Simple</option>
              <option value="Compound">Compound</option>
            </select>
          </div>

          <div class="col-5">
            <label for="rate">Rate (%):</label>
            <div class="custom_rate" style="position:relative">
              <input type="text" class="w-100" id="custom_rate" name="cust_rate" placeholder="Rate...">
              <i class="fa fa-times close_custom d-none"></i>
            </div>
          </div>
        </div>

        <div class="row px-3 mt-3">
          <div class="col-6 d-flex justify-content-start align-items-center">
            <input type="checkbox" name="weekend" class="m-0 mr-1" id="weekend">
            <span class="text-light font-italic ml-1" style="font-size:12px">Include weekends</span>
          </div>
          <div class="col-6 d-flex justify-content-end align-items-center">
            <input type="checkbox" name="start_date" class="m-0 mr-1" id="start_date">
            <span class="text-gray font-italic ml-1" style="font-size:12px">Start plan today</span>
          </div>
        </div>

        <?php
          if(isset($_SESSION['start'])){
            echo "<div class='text-warning font-italic text-right mr-3' style='font-size:11px'>"  . $_SESSION['start'] . "</div>";
            unset($_SESSION['start']);
          }
        ?>
      
        <div class="col-12 mt-2">
          <input type="submit" id="add_plan" name="add" value="Save Plan" class="w-100 py-2 text-dark font-weight-bold bg-warning border-0">
        </div>
      </form>
    </div>
  </div>
</section>

<?php
   if(isset($_POST['add'])){
      $user_id = $id;
      $plan = mysqli_real_escape_string($conn, $_POST['plan']);
      $acct_no = mysqli_real_escape_string($conn, $_POST['acct']);
      $broker = mysqli_real_escape_string($conn, $_POST['broker']);
      $amount = mysqli_real_escape_string($conn, $_POST['amount']);
      $duration = mysqli_real_escape_string($conn, $_POST['cust_duration']);
      $rate = mysqli_real_escape_string($conn, $_POST['cust_rate']);
      $interest = mysqli_real_escape_string($conn, $_POST['interest']);

      // Check if plan title already exists for a particular user
      $check_plan = mysqli_query($conn, "SELECT plan_name FROM compounding WHERE plan_name='$plan' AND user_id=$id");
      if(mysqli_num_rows($check_plan) == 1){
        $_SESSION['new-plan'] = "<span class='text-uppercase text-light'>" . $plan . "</span> already exists.";
        echo "<script>location.href='new_plan.php'</script>";
        die();
      }

      // Include weekends or not in the plan
      if(isset($_POST['weekend'])){
        $weekend = 1;
      }
      else{
        $weekend = 0;
      }

      //  Check for empty fields
      if(empty($plan) || empty($acct_no) || empty($amount) || empty($duration) || empty($rate) || empty($broker) || empty($interest)){
        $_SESSION['new-plan'] = "Incomplete fields. Cannot save plan"; // Display under the start date checkbox
        echo "<script>location.href='new_plan.php'</script>";
        die();
      }
      else{
        if(isset($_POST['start_date'])){
          // Check if start date is on a weekend and if weekends are included in the trading plan
          $today = date("Y-m-d");
          $today = date("l", strtotime($today)); //Sunday, Monday, Tuesday...

          if($today == "Saturday" || $today == "Sunday"){
            if($weekend == 1){
              $start_date = date("Y-m-d");
              $active = 1;
            }
            elseif($weekend == 0){
              $_SESSION['start'] = "Cannot start this plan on a weekend"; //Display under the start date checkbox
              echo "<script>location.href='new_plan.php'</script>";
              die();
            }
          }
          else{
            $start_date = date("Y-m-d");
            $active = 1;
          }
        }
        else{
          $start_date = "";
          $active = 0;
        }

        // Insert to database
        $insert_plan = "INSERT INTO compounding SET
          user_id = $id,
          plan_name = '$plan',
          principal = '$amount',
          rate = $rate,
          interest = '$interest',
          duration = $duration,
          broker = '$broker',
          start_date = '$start_date',
          acct_no = $acct_no,
          weekend = $weekend,
          active = $active
        ";

        // Save to database
        $insert_plan_res = mysqli_query($conn, $insert_plan);

        if($insert_plan_res == true){
          // Redirect to plan list page and display confirmation message
          $_SESSION['added-plan'] = "<span class='text-uppercase' style='font-size:13px; font-weight:600'>" .$plan . "</span> was successfully added";
          echo "<script>location.href='comp_plan.php'</script>";
        }
        else{
          $_SESSION['new-plan'] = "Failed to Add Plan. Try Again";
          echo "<script>location.href='new_plan.php'</script>";
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