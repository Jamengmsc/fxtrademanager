<?php
   $caption = "User Login";
   include "partials/header.php";

   if(isset($_SESSION['id'])){
      // header("location:" . SITEURL . "index.php");
      echo "<script>location.href='" . SITEURL . "index.php'</script>";
   }
?>

<?php
   // Capture all errors into this array
   $errors = array();

   if(isset($_POST['submit'])){
      $account_id = mysqli_real_escape_string($conn, $_POST['accountid']);
      $password = $_POST['password'];

      if(empty($account_id) || empty($password)){
         if($account_id == ""){
            $errors[] = "Account ID is required";
         }

         if($password == ""){
            $errors[] = "Password is required";
         }
      }
      else{
         $sql = "SELECT * FROM user_reg WHERE account_id=$account_id AND password='$password'";
         $res = mysqli_query($conn, $sql);

         if(mysqli_num_rows($res) == 1){
            $row = mysqli_fetch_assoc($res);

            $_SESSION['id'] = $row['id'];
            
            // header("location:" . SITEURL . "index.php");
            echo "<script>location.href='" . SITEURL . "index.php'</script>";
         }
         else{
            $errors[] = "<div class='text-right'>Failed to login. Try again</div>";
         }
      }
   }
?>

<br>
<section class="login">
   <div class="container">
      <div class="login bg-dark pt-3 pb-0 pb-md-2 px-4 mt-5">
         <h4 class="text-center pb-0 pb-md-2" style="color: lightgray;"><i class="fa fa-user"></i> USER <span class="text-warning font-italic">Login</span></h4>

         <!-- Display login error messages -->
         <?php
            if($errors){
               foreach ($errors as $key => $value){
                  echo "<div class='font-italic small mb-2' style='color: lightgray'>$value</div>";
               }
            }
         ?>
   
         <form action="" method="post" class="form-login" autocomplete="off">
            <label for="acctid">Account ID</label>
            <input type="text" name="accountid" id="log-account" class="w-100" placeholder="Your FxTrade ID... Eg. 2048934">
   
            <div class="login_pwd position-relative">
               <label for="pwd">Password</label>
               <input type="password" id="log-password" name="password" class="w-100 m-0" placeholder="FxTrade Password...">
               <i class="fa fa-eye text-gray small" style="position:absolute; right:12px; bottom:14px; cursor:pointer"></i>
            </div>
   
            <div class="row">
               <div class="col-md-4 col-12">
                  <input type="submit" id="userlogin" name="submit" class="mt-3 border border-warning text-dark bg-warning font-weight-bold w-100" value="LOGIN">
               </div>
   
               <div class="col-md-8 col-12">
                  <p class="p-0 text-light mt-0 mt-md-1 text-right small">Don't have account? <a href="<?php echo SITEURL ?>register.php" class="text-warning text-decoration-none font-italic">Register Here</a></p>
               </div>
            </div>
         </form>
      </div>
   </div>
</section>


<script src="./script.js"></script>
</body>
</html>     


<script>
   var login = document.getElementById("userlogin");
      login.addEventListener("click", function(){
         var acctId = document.getElementById("log-account"),
            logPwd = document.getElementById("log-password");

         if(acctId.value.trim() == "" || logPwd.value.trim() == ""){
            document.getElementById("logErr").innerHTML = "Incomplete login information";
            return false;
         }
      })

   // SHOW/HIDE LOGIN PASSWORD
   var loginPwd = document.querySelector(".login_pwd input");
   var showLoginPwd = document.querySelector(".login_pwd i");

   showLoginPwd.addEventListener("click", function(){
      if(loginPwd.type === "password"){
         loginPwd.type = "text";
         this.classList.replace("fa-eye", "fa-eye-slash");
      }
      else{
         loginPwd.type = "password";
         this.classList.replace("fa-eye-slash", "fa-eye");
      }
   })
      
</script>