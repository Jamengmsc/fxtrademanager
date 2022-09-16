<?php
    $caption = "Add Pair";
    include "../partials/header-admin.php";
?>


<!-- Get user details -->
<?php
   if(isset($_SESSION['id'])){
      $id = $_SESSION['id'];

      $query = mysqli_query($conn, "SELECT * FROM user_reg WHERE id=$id");
      if(mysqli_num_rows($query) == 1){
         $row = mysqli_fetch_assoc($query);

         $firstname = $row['firstname'];
         $acctID = $row['account_id'];
      }
   }
?>

<section class="container-fluid px-md-5">
  <div class="d-flex justify-content-between align-items-center">
    <div class="d-flex flex-column">
      <div class="home-icon d-flex justify-content-start align-items-center">
          <i class="fa fa-cogs" style="font-size: 18px; color: #e7781c;"></i>
          <span class="ml-2" style="font-size: 16px; color: #e7781c;">Admin Panel</span>
      </div>
    </div>

    <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
      <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

      <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
    </div>
  </div>

  <hr class="m-0 my-2">
</section>

<section>
  <div class="container">
    <div id="add_pair" class="bg-dark rounded-lg p-3 mt-3">
      <h5 class="text-light text-center pb-1" style="font-size:16px; font-weight:normal">New Currency Pair</h5>
      <form action="" class="pair_form" method="POST">
          <div class="row">
            <div class="col-12">
              <label for="pair">Currency Pair</label>
              <input type="text" name="pair" class="w-100" placeholder="E.g... EURUSD">
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <input type="submit" name="add" value="Add Currency Pair" class="w-100 mt-2 bg-warning border-warning text-dark font-weight-bold">
            </div>
          </div>
      </form>

      <?php
        if(isset($_POST['add'])){
          $pair = mysqli_real_escape_string($conn, $_POST['pair']);

          if($pair !== ""){
            // Add pair to database table
            $add = "INSERT INTO currency_pair SET pair='$pair'";
            $add_res = mysqli_query($conn, $add);

            if($add_res == false){
              echo "<div class='small font-italic' style='color:lightgray'>Failed to Add Pair</div>";
            }
            else{
              echo "<div class='small text-warning'>Pair Added Successfully</div>";
            }
          }
          else{
            echo "<div class='font-italic small' style='color:lightgray'>No Currency Pair in the field</div>";
            die();
          }
        }
      ?>
    </div>


    <!-- Add Broker's withdrawal rate -->
    
    <div id="add_pair" class="bg-dark rounded-lg p-3 my-5">
      <h5 class="text-light font-weight-normal text-center pb-2" style="font-size:16px;">Broker's Withdrawal Rate</h5>
        <?php
          if(isset($_SESSION['failed'])){
            echo "<div class='text-warning font-italic d-block mt-n2 mr-2 mb-2' style='font-size:10px'>" . $_SESSION['failed'] . "</div>";
            unset($_SESSION['failed']);
          }
        ?>

      <form action="" class="pair_form" method="POST">
        <div class="row">
          <div class="col-12">
            <label for="broker">Broker's Name</label>
            <select name="broker" class="w-100" id="broker">
              <option value="">-- Select Broker --</option>
              <?php
                $all_brokers = mysqli_query($conn, "SELECT DISTINCT broker FROM new_account ORDER BY broker ASC");
                if(mysqli_num_rows($all_brokers) > 0){
                  // $broker_row = mysqli_fetch_assoc($all_brokers);
                  while($broker_row = mysqli_fetch_assoc($all_brokers)){
                    ?>
                      <option value="<?= $broker_row['broker'] ?>"><?= $broker_row['broker'] ?></option>
                    <?php
                  }
                }
                else{
                  ?>
                    <option value="">No Broker Found</option>
                  <?php
                }
              ?>
            </select>
            
            <?php
              if(isset($_SESSION['broker'])){
                echo "<div class='text-light text-right font-italic d-block mt-n2 mr-2 mb-n1' style='font-size:11px'>" . $_SESSION['broker'] . "</div>";
                unset($_SESSION['broker']);
              }
            ?>
          </div>
        </div>

        <div class="row mt-1">
          <div class="col-12">
            <label for="rate">Withdrawal Rate</label>
            <input type="text" name="rate" class="w-100" placeholder="Enter Rate...">
            <?php
              if(isset($_SESSION['rate'])){
                echo "<div class='text-light text-right font-italic d-block mt-n2 mr-2 mb-1' style='font-size:11px'>" . $_SESSION['rate'] . "</div>";
                unset($_SESSION['rate']);
              }
            ?>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <input type="submit" name="submit" value="Save Withdrawal Rate" class="w-100 mt-2 bg-warning border-warning text-dark font-weight-bold">
          </div>
        </div>
      </form>

      <?php
        if(isset($_POST['submit'])){
          $broker = mysqli_real_escape_string($conn, $_POST['broker']);
          $rate = mysqli_real_escape_string($conn, $_POST['rate']);

          if($broker == ""){
            $_SESSION['broker'] = "Select a broker";
            echo "<script>location.href='add-pair.php'</script>";
            die();
          }
          else{
            if($rate == ""){
              $_SESSION['rate'] = "Enter a value for the withdrawal rate";
              echo "<script>location.href='add-pair.php'</script>";
              die();
            }
            else{
              // Update the broker's rate on DB
              $broker_rate = "UPDATE new_account SET
                withdraw_rate = $rate
                
                WHERE broker = '$broker' AND acct_type = 'Live'
              ";

              $broker_rate_res = mysqli_query($conn, $broker_rate);
              if($broker_rate_res == false){
                $_SESSION['failed'] = "Failed to add/update broker's withdrawal rate";
                echo "<script>location.href='add-pair.php'</script>";
                die();
              }
              else{
                $_SESSION['withdraw-rate'] = $broker . " withdrawal rate has been saved as " . $rate . " = 1 USD";
                echo "<script>location.href='../index.php'</script>";
              }
            }
          }
        }
      ?>
    </div>
  </div>
</section>


<script src="../script.js"></script>
</body>
</html>
