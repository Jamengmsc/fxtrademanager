<?php
   $caption = "Edit Account";

   include "partials/header.php";
   include "config/check-login.php";
?>

<!-- Main section -->
<section class="container-fluid px-md-5">
   <div class="d-flex justify-content-between align-items-center mb-4">
      <div class="d-flex flex-column">
         <div class="home-icon d-flex justify-content-start align-items-center">
            <i class="fa fa-edit" style="font-size: 18px; color: #e7781c;"></i>
            <span class="ml-2" style="font-size: 16px; color: #e7781c;">Edit Accounts</span>
         </div>
      </div>

      <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
         <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

         <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
      </div>
   </div>
</section>

<?php
  if(isset($_GET['acct_id'])){
    $acct_id = $_GET['acct_id'];
  }

  $getAcctNo = mysqli_query($conn, "SELECT acct_no FROM record_acct WHERE id=$acct_id");
  $acct_no = mysqli_fetch_assoc($getAcctNo)['acct_no'];

  $getAcctDetail = mysqli_query($conn, "SELECT * FROM new_account WHERE acct_no=$acct_no");
  if(mysqli_num_rows($getAcctDetail) == 1){
    $row = mysqli_fetch_assoc($getAcctDetail);
  }

?>


<section class="mb-5">
   <div class="container-fluid px-md-5">
      <!-- <h5 class="mt-4 mb-2">Edit Account Details</h5> -->

      <div id="new_account" class="bg-dark p-2 pb-4 rounded-lg">
         <form method="post" class="new_account" autocomplete="off">

         <!-- Account Information -->
         <h5 class="py-2 px-3 font-italic" style="color: lightgray;">Account Information</h5>

         <div class="col-12">
            <label for="borker">Broker Name:</label>
            <input type="text" name="broker" class="w-100 mb-4" value="<?= $row['broker'] ?>">
         </div>

         <div class="col-12">
            <label for="acct-type">Account Type:</label><br>
            <select name="acct_type" class="w-100" id="open_type">
               <option value="Demo" <?php if($row['acct_type'] == "Demo"){echo "selected";} ?>>Demo Account</option>
               <option value="Live" <?php if($row['acct_type'] == "Live"){echo "selected";} ?>>Live Account</option>
            </select>
         </div>
            
            <!-- <div class="row"> -->
         <div class="col-12">
            <label for="currency">Currency:</label><br>
            <select name="currency" class="w-100">
               <!-- <option value="" disabled selected>Account Type</option> -->
               <option value="$" <?php if($row['currency'] == "$"){echo "selected";} ?>>US Dollar</option>
               <option value="₦" <?php if($row['currency'] == "₦"){echo "selected";} ?>>Naira</option>
               <option value="£" <?php if($row['currency'] == "£"){echo "selected";} ?>>Pound</option>
               <option value="€" <?php if($row['currency'] == "€"){echo "selected";} ?>>Euro</option>
            </select>
         </div>

         
         <div class="col-12">
            <label for="acct_no">Account No:</label>
            <input type="text" name="acct_no" class="w-100" value="<?= $row['acct_no'] ?>">
         </div>

         <?php
            if($row['acct_type'] == "Demo"){
              ?>
                <div class="col-12" id="open_bal">
                    <label for="acct-bal">Opening Balance:</label>
                    <input type="text" name="balance" class="w-100" value="<?= $row['balance'] ?>">
                </div>
              <?php
            }
            else{
              echo '
                <div class="col-12 text-warning small font-italic my-2">
                  Manage account amount values with deposits and transfers
                </div>
              ';
            }
         ?>
         
         <div class="col-12 mt-3">
            <input type="submit" name="update" value="Update Account Details" class="w-100 text-dark font-weight-bold bg-warning border border-warning py-2">
         </div>

         </form>

         <?php
            if(isset($_POST['update'])){
               
               $acct_num = mysqli_real_escape_string($conn, $_POST['acct_no']);
               $acct_type = mysqli_real_escape_string($conn, $_POST['acct_type']);
               $currency = mysqli_real_escape_string($conn, $_POST['currency']);
               $broker = mysqli_real_escape_string($conn, $_POST['broker']);
               $balance = mysqli_real_escape_string($conn, $_POST['balance']);

               // Update new_account table on database
                  // Check for empty fields
                  if($acct_num !== "" && $acct_type !== "" && $currency !== "" && $broker !== ""){
                     if($acct_type == "Demo"){
                        if($balance !== ""){
                           // Update account table
                           $upd_acct = "UPDATE new_account SET
                           acct_no=$acct_num,
                           acct_type='$acct_type',
                           currency='$currency',
                           broker='$broker',
                           balance='$balance'

                           WHERE acct_no=$acct_no
                           ";

                           $upd_acct_res = mysqli_query($conn, $upd_acct);

                           if($upd_acct_res == true){
                           // Update Record_acct also
                           $upd_rec_acct = "UPDATE record_acct SET
                              acct_no=$acct_no,
                              acct_type='$acct_type'

                              WHERE id=$acct_id
                           ";

                           $upd_rec_acct_res = mysqli_query($conn, $upd_rec_acct);
                           }
                        }
                     }

                     elseif($acct_type == "Live"){
                        // Update account table
                        $upd_acct = "UPDATE new_account SET
                                    acct_no=$acct_num,
                                    acct_type='$acct_type',
                                    currency='$currency',
                                    broker='$broker'
   
                                    WHERE acct_no=$acct_no
                                    ";
   
                        $upd_acct_res = mysqli_query($conn, $upd_acct);
   
                        if($upd_acct_res == true){
                        // Update Record_acct also
                        $upd_rec_acct = "UPDATE record_acct SET
                           acct_no=$acct_no,
                           acct_type='$acct_type'
   
                           WHERE id=$acct_id
                        ";
   
                        $upd_rec_acct_res = mysqli_query($conn, $upd_rec_acct);
                        }
                     }

                     if($upd_rec_acct_res == true){
                        echo "<script>location.href='index.php'</script>";
                     }
                     else{
                        echo "Could not update account";
                     }
                  }
            }
         ?>
      </div>
   </div>
</section>

  <script src="./script.js"></script>
</body>
</html>