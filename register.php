<?php
   $caption = "User Registration";
   include "partials/header.php";
   include "mail/mail_constant.php";
?>

<section class="mb-4">
  <div class="container-fluid px-md-5">
    <div class="row mt-md-5 mt-4">
      <div class="col-md-6 col-12">
        <h3 class="mb-3 text-center text-md-left text-dark">User Registration</h3>
      </div>
      <div class="col-md-6 col-12 text-md-right text-left">
        <div class="text-secondary font-weight-bold" style="font-size: 14px;">Note: <span class="font-italic font-weight-normal" style="color: rgb(255, 145, 0);">All information is required</span> </div>
      </div>
    </div>

    <div class="register bg-dark px-4 pt-3 pb-2">
      <form method="post" class="form-register" autocomplete="off">
        <div class="row">
          <div class="col-md-6">
            <h5 class="mb-3" style="color: lightgray;">Personal Information</h5>

            <label for="name">Firstname:</label>
            <input type="text" name="firstname" class="w-100" placeholder="John">
          
            <label for="name">Lastname:</label>
            <input type="text" name="lastname" class="w-100" placeholder="Smith">

            <label for="gender">Gender:</label><br>
            <select name="gender" class="w-100">
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>

            <label for="country" class="mt-2">Country:</label>
            <input type="text" name="country" class="w-100" placeholder="Nigeria">

            <label for="state">State:</label>
            <input type="text" name="state" class="w-100" placeholder="Lagos">

            

          </div>

          <div class="col-md-6">
            <h5 class="mb-3 mt-md-0 mt-4" style="color: lightgray;">Contact Information</h5>

            <label for="address">Address</label><br>
            <textarea class="w-100" rows="4" name="address" placeholder="123 Broad Street, New York City"></textarea>

            <label for="mobile">Mobile:</label>
            <input type="text" name="mobile" class="w-100" placeholder="+2348078473670">

            <label for="email">Email:</label>
            <input type="email" name="email" class="w-100" placeholder="tradingaccount@gmail.com">

            <div class="row">
              <div class="col-md-6 col-12">
                <label for="pwd">Password:</label>
                <input type="password" name="password" class="w-100" placeholder="Use strong password">
              </div>
              <div class="col-md-6 col-12">
                <label for="cpwd">Retype Password:</label>
                <input type="password" name="cpassword" class="w-100" placeholder="Must match password...">
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-md-6 col-12">
            <input type="submit" name="submit" value="Register Account" class="w-100 text-dark bg-warning font-weight-bold border border-warning">
          </div>
          <div class="col-md-6 col-12">
            <p class="p-0 text-light mt-1 text-right small">Already registered? <a href="<?php echo SITEURL ?>login.php" class="text-warning text-decoration-none font-italic">login here</a></p>
          </div>
        </div>

      </form>



    <?php
      if(isset($_POST['submit'])){
        $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
        $country = mysqli_real_escape_string($conn, $_POST['country']);
        $state = mysqli_real_escape_string($conn, $_POST['state']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
        
        // Check for existence of email
        $sql = "SELECT email from user_reg where email='".$_POST['email']."'";
        $res = mysqli_query($conn, $sql);
        if(mysqli_num_rows($res) == 1){
          echo "Email address already exists";
          die();
        }
        else{
          $email = mysqli_real_escape_string($conn, $_POST['email']);
        }

        // Input gender data
        if(isset($_POST['gender'])){
          $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        }
        
        // Validate passwords match
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];

        if($password !== $cpassword){
          echo "Passwords do not match";
          die();
        }

        // Generate Account ID for user
        $account_id = rand(100000, 999999);

        // Insert user data to database table
        $insert_user = "INSERT INTO user_reg SET
          account_id = $account_id,
          firstname = '$firstname',
          lastname = '$lastname',
          country = '$country',
          state = '$state',
          address = '$address',
          email = '$email',
          mobile = '$mobile',
          gender = '$gender',
          password = '$password'
        ";

        $user = mysqli_query($conn, $insert_user);
        if($user == false){
          $_SESSION['user-reg'] = "<div class='text-light font-italic'>Registration Failed</div>";
        }
        else{
          // Send a mail to the user
          $subject = "Registration Successful";

          $body = "<div style='width:90%; margin:0 auto; padding:10px 15px 30px 15px; background:rgba(0,0,0,.05); border-radius:3px'>
              <p><b>Hi " . $firstname . ",</b></p>

              <p>Thank you for signing up with FxTrade.</p>
              
              <p>Below are the details of your new FxTrade account: </p>
              <br>
              
              <h3 style='color:orange; padding:0'>Your FxTrade Account Details</h3>

              <span><b>Account ID:</b>&nbsp; &nbsp; " . $account_id . "</span><br>
              <span><b>Password:</b>&nbsp; &nbsp; " . $password . "</span>
              
              <br>
              <p>Keep your password confidential and in case of any fraud on your account, kindly <a href=''>reset your password</a> to retrieve your account and continue using FxTrade</p>

              <br>

              <a style='display:block; padding:10px;border-radius:2px;background:#34495e;color:white;border:none;text-align:center' href=''>Click to Activate Account</a>

              <br>
              <br>

              <p style='padding:0; margin:0'>Regards,</p>
              <p style='padding:0; margin:0'><b>FxTrade Team</b></p>

          </div>
        ";

          // Send HTML element tag in the mail
          $mail->addAddress($email);
          $mail->Subject = $subject;
          $mail->Body = $body;

          if(!$mail->Send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            exit;
          }

          echo 'Message has been sent';
          
          // Redirect to registration successful page
          echo "<script>location.href='" . SITEURL . "reg-success.php'</script>";
        }
      }
    ?>

    </div>
  </div>
</section>



   <script src="./script.js"></script>
</body>
</html>