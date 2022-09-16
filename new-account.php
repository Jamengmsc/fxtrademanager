<?php
   $caption = "Add Trading Account";
   
   include "partials/header.php";
   include "config/check-login.php";
   include "mail/mail_constant.php";
?>

<section class="container-fluid px-md-5">
   <div class="d-flex justify-content-between align-items-center mb-4">
      <div class="d-flex flex-column">
         <div class="home-icon d-flex justify-content-start align-items-center">
            <i class="fa fa-plus-square" style="font-size: 18px; color: #e7781c;"></i>
            <span class="ml-2" style="font-size: 16px; color: #e7781c;">Add Accounts</span>
         </div>
      </div>

      <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
         <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

         <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
      </div>
   </div>
</section>

<section class="mb-5">
   <div class="container-fluid px-md-5">

   <?php
      if(isset($_SESSION['new-acct'])){
         echo $_SESSION['new-acct'];
         unset($_SESSION['new-acct']);
      }
   ?>

   <div id="new_account" class="bg-dark p-2 pb-4 rounded-lg">
      <form method="post" class="new_account" autocomplete="off">

      <!-- Account Information -->
      <h5 class="p-3 font-italic" style="color: lightgray;">Account Information</h5>

      <div class="col-12">
         <label for="borker">Broker Name:</label>
         <input type="text" name="broker" class="w-100 mb-4" placeholder="Account Broker's Name...">
      </div>

      <div class="col-12">
         <label for="acct-type">Account Type:</label><br>
         <select name="acct_type" class="w-100" id="open_type" onchange="openAcctType(this.value)">
            <!-- <option value="" disabled selected>Account Type</option> -->
            <option value="Demo">Demo Account</option>
            <option value="Live">Live Account</option>
         </select>
      </div>
         
         <!-- <div class="row"> -->
      <div class="col-12">
         <label for="currency">Currency:</label><br>
         <select name="currency" class="w-100">
            <!-- <option value="" disabled selected>Account Type</option> -->
            <option value="$">US Dollar</option>
            <option value="₦">Naira</option>
            <option value="£">Pound</option>
            <option value="€">Euro</option>
         </select>
      </div>

      
      <div class="col-12">
         <label for="acct_no">Account No:</label>
         <input type="text" name="acct_no" id="new_acct_no" class="w-100 mb-3" placeholder="Eg. 2189087">
         <div class="num_count text-white font-italic mb-n4" style="font-size:11px;opacity:0">* You have <span>5</span> digits to be able to send</div>
      </div>

      <div class="col-12" id="open_bal">
      <label for="acct-bal">Opening Balance:</label>
      <input type="text" name="balance" id="new_acct_bal" class="w-100" placeholder="Current account balance...">
      </div>
      
      <div class="col-12 mt-3">
         <input type="submit" id="add_account" name="add" value="Add Trading Account" class="w-100 text-dark font-weight-bold bg-secondary border-0" disabled>
      </div>

      </form>
   </div>
   </div>
</section>

<?php
   if(isset($_POST['add'])){
      $user_id = $id;
      $broker = mysqli_real_escape_string($conn, $_POST['broker']);
      $acct_type = mysqli_real_escape_string($conn, $_POST['acct_type']);
      $currency = mysqli_real_escape_string($conn, $_POST['currency']);
      $acct_no = mysqli_real_escape_string($conn, $_POST['acct_no']);

      if($acct_type == "Live"){
         $balance = 0;
      }
      else{
         $balance = mysqli_real_escape_string($conn, $_POST['balance']);
      }

      // Check if account on same type of account already exists
      $check = "SELECT * FROM new_account WHERE acct_type='$acct_type' and acct_no=$acct_no and broker='$broker' and user_id=$id";
      $check_acct = mysqli_query($conn, $check);

      if(mysqli_num_rows($check_acct) == 1){
         $_SESSION['new-acct'] = "<div class='text-secondary text-right font-italic small'>Account already exists</div>";
         die();
      }
      else{
         $add = "INSERT INTO new_account SET
         user_id = $user_id,
         acct_type = '$acct_type',
         currency = '$currency',
         acct_no = $acct_no,
         balance = '$balance',
         broker = '$broker'
      ";

         $add_res = mysqli_query($conn, $add);
         if($add_res == false){
            echo "Failed to add account";
         }
         else{
            
            // Also add account details on the record account table
            $rec_acct = "INSERT INTO record_acct SET
               user_id=$id,
               acct_type = '$acct_type',
               acct_no = $acct_no
            ";
            $rec_acct_res = mysqli_query($conn, $rec_acct);


            // Send email containing account details
            $subject = "FxTrade " . $acct_type . " Account Opened";
            $body = "<div style='width:90%; margin:0 auto; padding:10px 15px 30px 15px; background:rgba(0,0,0,.05); border-radius:3px'>
              <p><b>Hi " . $firstname . ",</b></p>

              <p>Congratulations, you have just opened a " . $acct_type . " Account with FxTrade! </p>
              
              <p class='font-weight-bold'>Below are the details of your new FxTrade Account: </p>
              

              <h3 style='padding:0; color:orangered'>Your FxTrade Account Details</h3>

              <span><b>Account No.:</b>&nbsp; &nbsp; " . $acct_no . "</span><br>
              <span><b>Account Type:</b>&nbsp; &nbsp; " . $acct_type . "</span><br>            
              <span><b>Account Broker:</b>&nbsp; &nbsp; " . $broker . "</span><br>          

              <br>

              <a style='display:block; padding:10px;border-radius:2px;background:#34495e;color:white;border:none;text-align:center; max-width:250px; margin:0 auto' href='" . SITEURL . "activate_acct.php?acct_no=" . $acct_no . "'>Click to Activate Account</a>

              <br>
              <br>

              <p style='padding:0; margin:0'>Regards,</p>
              <p style='padding:0; margin:0'><b>FxTrade Team</b></p>

          </div>
        ";

            // Send HTML element tag in the mail
            $mail->addAddress($user_email);
            $mail->Subject = $subject;
            $mail->Body = $body;

            if(!$mail->Send()) {
               echo 'Message could not be sent.';
               echo 'Mailer Error: ' . $mail->ErrorInfo;
               exit;
            }

            echo 'Message has been sent';


            // Redirect to home page
            echo "<script>location.href='index.php'</script>";
         }
      }
   }

?>



   <script src="./script.js"></script>
</body>
</html>


<script>
   function openAcctType(str){

      var xhr = new XMLHttpRequest();
        xhr.onload = function(){
          if(this.status == 200){
            //   console.log(this.response);
              document.getElementById("open_bal").innerHTML = this.responseText;
          }
        }
        xhr.open("GET", "ajax/detect_acct_type.php?acct_type=" + str, true);

        xhr.send();
   }


   var addAcct = document.getElementById("add_account"),
      numCount = document.querySelector(".num_count"),
      numCountNum = document.querySelector(".num_count span"),
      newAcctNo = document.getElementById("new_acct_no");

      newAcctNo.oninput = () => {
         newAcctNo.classList.replace("mb-3", "mb-0");

         numCount.style.transition = "all 0.15s linear";
         newAcctNo.style.transition = "all 0.15s linear";
         numCount.classList.replace("mb-n4", "mb-2");
         numCount.style.opacity = "1";

         
         // if(numCountNum.innerHTML <= 1){
         //    numCount.innerHTML = "You have reached the maximum input";
         // }
         // else{
            //    numCountNum.innerHTML = 8 - newAcctNo.value.length;
            // }
            numCountNum.innerHTML = 8 - newAcctNo.value.length;


         if(newAcctNo.value == ""){
            numCount.classList.replace("mb-2", "mb-n4");
            numCount.style.opacity = "0";

            newAcctNo.classList.replace("mb-0", "mb-3");
         }

         newAcctNo.value = newAcctNo.value.substring(0, 8);

         if(newAcctNo.value.length === 8){
            addAcct.removeAttribute("disabled", "disabled");
            addAcct.classList.replace("bg-secondary", "bg-warning");
            addAcct.style.transition = "all 0.3s linear";
         }
         else{
            addAcct.setAttribute("disabled", "disabled");
            addAcct.classList.replace("bg-warning", "bg-secondary");
         }
      }

</script>