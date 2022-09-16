<?php
   $caption = "Plans List";

   include "partials/header.php";
   include "config/check-login.php";
?>

<!-- Main section -->
<section class="container-fluid px-md-5">
  <div class="d-flex justify-content-between align-items-center">
    <div class="d-flex flex-column">
      <div class="home-icon d-flex justify-content-start align-items-center">
          <i class="fab fa-microsoft" style="font-size: 18px; color: #e7781c;"></i>
          <span class="ml-2" style="font-size: 16px; color: #e7781c;">Plans List</span>
      </div>
    </div>

    <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
      <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

      <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
    </div>
  </div>

  <hr class="m-0 my-2">
</section>

<style>
  .plan_name{
    color:#FFC107;
    font-size:16px;
    font-weight:500;
  }
</style>

<section class="mb-4">
  <?php // Confirmations
    if(isset($_SESSION['added-plan'])){
      echo "<div class='alert alert-success alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['added-plan'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
      unset($_SESSION['added-plan']);
    }

    if(isset($_SESSION['deleted-plan'])){
      echo "<div class='alert alert-danger alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['deleted-plan'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
      unset($_SESSION['deleted-plan']);
    }
  ?>

  <div class="container-fluid px-md-5 mb-3">
    <h6 class="m-0 text-dark mt-2 text-uppercase font-weight-bold">Compounding <span class="text-secondary font-italic">Plans</span></h6>
    <p class="mb-3 text-secondary" style="font-size:10px; color: #e7781c;">(List of compounding plan items. Click on any plan item to view full details)</p>
    
    <div class="plan_list">
      <?php
        $plan_items = mysqli_query($conn, "SELECT * FROM compounding WHERE user_id=$id ORDER BY plan_name ASC");
        if(mysqli_num_rows($plan_items) > 0){
          while($plan_rows = mysqli_fetch_assoc($plan_items)){
            ?>
              <div class="plan_item position-relative">
                <?php
                  if(date("Y-m-d") == $plan_rows['start_date']){
                    ?>
                      <div class="start_plan">Active</div>
                    <?php
                  }


                  if($plan_rows['interest'] == "Compound"){
                    $interest_type = "<span class='font-italic text-gray'>Ci</span>";
                  }
                  else{
                    $interest_type = "<span class='font-italic text-gray'>Si</span>";
                  }
                ?>
                
                <a href="<?= SITEURL ?>comp_plan_item.php?plan_id=<?= $plan_rows['id'] ?>" class="plan small">
                  <div class="plan_name" style="font-size:14px; margin-bottom:2px"><?= $plan_rows['plan_name'] ?></div>
                  <div class="plan_details small mt-n1"><?php echo "$" . $plan_rows['principal'] . ", " . $plan_rows['rate'] . "% " . $interest_type ?></div>
                  <div class="plan_period small font-italic" style="margin-top:-2px"><?= "(" .$plan_rows['duration'] . " days)" ?></div>
                  
                  
                  <div class="plan_perc_completed text-white font-italic" style="font-size:8px">
                    <?php
                      $date_now = date("Y-m-d");
                      $date_now = strtotime($date_now);
                      $date_start = strtotime($plan_rows['start_date']);

                      if($date_now < $date_start){ // Start date not set yet
                        echo "0%";
                      }
                      else{ // if start date is set
                        if($plan_rows['active'] == 1){ // If start date has reached, progress status starts to read
                          $date_diff = $date_now - $date_start;
                          $day = ($date_diff / 86400) + 1;

                          $perc_done = ($day / $plan_rows['duration']) * 100;

                          if($perc_done >= 100){
                            ?>
                              <div class='font-italic text-warning'><?= "Completed" ?></div>
                            <?php
                          }
                          else{ // If start date has not reached, so no progress yet
                            echo number_format($perc_done, 0, ".", ",") . "%";
                          }
                        }
                        else{
                          echo "0%" ;
                        }
                      }
                    ?>
                  </div>

                  <div class="progress-bar rounded" style="width:80%; height:4px; background:none; border:1px solid gray;">
                    <div class="progress-status bg-warning" style="height:100%"></div>
                  </div>
                </a>
                

                <!-- Plan bottom icons -->
                <?php
                  if($plan_rows['active'] == 1 && date("Y-m-d") >= $plan_rows['start_date']){
                    ?>
                      <i class="fa fa-check text-warning" style="position:absolute; bottom:6px; left:85px; font-size:8px"></i>
                    <?php

                    // Whether start date is set or not
                    if($plan_rows['start_date'] > 1){
                      ?>
                        <div class="font-italic" style="position:absolute; bottom:3.5px; left:70px; font-size:9px; color:#6cd6f7"><i class="far fa-clock" style="font-size:8px"></i></div>
                      <?php
                    }
                    else{
                      ?>
                        <div class="text-muted font-italic" style="position:absolute; bottom:3.5px; left:70px; font-size:9px"><i class="far fa-clock" style="font-size:8px"></i></div>
                      <?php
                    }
                    
                    // Check for weekends
                    if($plan_rows['weekend'] == 1){
                      ?>
                        <div class="text-warning font-italic" style="position:absolute; bottom:3px; left:7px; font-size:10px"><i class="far fa-check-square" style="font-size:9px"></i> wkd</div>
                      <?php
                    }
                    else{
                      ?>
                        <div class="text-secondary font-italic" style="position:absolute; bottom:3px; left:7px; font-size:10px"><i class="far fa-square m-0" style="font-size:9px"></i> wkd</div>
                      <?php
                    }
                  }

                  // If plan is not active
                  else{
                    ?>
                      <i class="fa fa-check text-secondary" style="position:absolute; bottom:6px; left:85px; font-size:8px"></i>
                    <?php

                    // Whether start date is set or not
                    if($plan_rows['start_date'] > 1){
                      ?>
                        <div class="font-italic" style="position:absolute; bottom:3.5px; left:70px; font-size:10px; color:#6cd6f7"><i class="far fa-clock" style="font-size:8px"></i></div>
                      <?php
                    }
                    else{
                      ?>
                        <div class="text-muted font-italic" style="position:absolute; bottom:3.5px; left:70px; font-size:10px"><i class="far fa-clock" style="font-size:8px"></i></div>
                      <?php
                    }


                    // Check for weekends
                    if($plan_rows['weekend'] == 1){
                      ?>
                        <div class="text-warning font-italic" style="position:absolute; bottom:3px; left:7px; font-size:10px"><i class="far fa-check-square" style="font-size:9px"></i> wkd</div>
                      <?php
                    }
                    else{
                      ?>
                        <div class="text-secondary font-italic" style="position:absolute; bottom:3px; left:7px; font-size:10px"><i class="far fa-square m-0" style="font-size:9px"></i> wkd</div>
                      <?php
                    }
                  }
                ?>
              </div>

              
            <?php
          }
          ?>
            <!-- New Plan Button -->
            <a href="<?= SITEURL ?>new_plan.php" class="plan new_plan bg-warning p-0" title="New Plan"><i class="fa fa-plus text-dark"></i></a>
          <?php
        }
        else{
          ?>
            <div class="empty_plan_list text-center" style="margin-top:50px">
              <a class="text-warning" style="color: #e7781c;" href="<?= SITEURL ?>new_plan.php"><i class="fa fa-plus fa-4x"></i></a>
              <h4 class="text-secondary text-center">Empty Plan List</h4>
              <p class="font-italic text-dark small font-weight-bold">(Click on the + sign to add a new plan)</p>
            </div>
          <?php
        }
      ?>
    </div>

    <br>
    <!-- FINANCIAL BUDGETS -->
    <section class="my_plan budgets">
      <h6 class="m-0 text-dark mt-2 text-uppercase font-weight-bold">Financial <span class="text-secondary font-italic">Budgets</span></h6>
      <p class="mb-1 text-secondary" style="font-size:10px; color: #e7781c;">(List of compounding plan items. Click on any plan item to view full details)</p>

      <div class="budget_plan">
        <a href="<?= SITEURL ?>budgets.php" class="font-italic font-weight-bold text-success btn-sm m-0" style="font-size:16px">My Budgets &raquo;</a>
      </div>
    </section>
    
    <!-- DIRECT SAVINGS -->
    <section class="my_plan savings">
      <h6 class="m-0 text-dark mt-2 text-uppercase font-weight-bold">Direct <span class="text-secondary font-italic">Savings</span></h6>
      <p class="mb-1 text-secondary" style="font-size:10px; color: #e7781c;">(List of compounding plan items. Click on any plan item to view full details)</p>

      <div class="saving_plan">
        <a href="<?= SITEURL ?>savings.php" class="font-italic text-info font-weight-bold btn-sm m-0" style="font-size:16px">My Saving &raquo;</a>
      </div>
    </section>

     <!-- APPLICATION TO DO -->
    <section class="my_plan todo">
      <h6 class="m-0 text-dark mt-2 text-uppercase font-weight-bold">Application <span class="text-secondary font-italic">To Do</span></h6>
      <p class="mb-1 text-secondary" style="font-size:10px; color: #e7781c;">(List of events to do, places to go with reminders. Click on any plan item to view full details)</p>

      <div class="todo_plan">
        <a href="<?= SITEURL ?>todo.php" class="font-italic text-danger font-weight-bold btn-sm m-0" style="font-size:16px">To Do List &raquo;</a>
      </div>
    </section>
  </div>

</section>


<script src="./script.js"></script>
</body>
</html>


<script>
  var progressContainer = document.querySelectorAll(".plan_perc_completed");
  var progressStatus = document.querySelectorAll(".progress-status");
      
  for(let i = 0; i < progressContainer.length; i++);

  for(let i = 0; i < progressStatus.length; i++){
    if(progressContainer[i].innerHTML == "Completed"){
      progressStatus[i].style.width = "100%";
    }
    else{
      progressStatus[i].style.width = progressContainer[i].innerHTML;
    }
  }
</script>

