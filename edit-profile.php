<?php
   $caption = "Edit Profile";
   
   include "partials/header.php";
   include "config/check-login.php";
?>

<section class="container-fluid px-md-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex flex-column">
      <div class="home-icon d-flex justify-content-start align-items-center">
          <i class="fa fa-user-edit" style="font-size: 18px; color: #e7781c;"></i>
          <span class="ml-2" style="font-size: 16px; color: #e7781c;">Edit Profile</span>
      </div>
    </div>

    <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
      <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

      <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
    </div>
  </div>
</section>

<section class="mb-5 mt-3">
   <div class="container-fluid px-md-5">

   <?php
      if(isset($_SESSION['update-user'])){
         echo $_SESSION['update-user'];
         unset($_SESSION['update-user']);
      }

      $user = mysqli_query($conn, "SELECT * FROM user_reg WHERE id=$id");
      if(mysqli_num_rows($user) == 1){
        $row = mysqli_fetch_assoc($user);
      }
    ?>

    <div class="edit_profile bg-dark p-md-4 p-3 rounded-lg">
      <form method="post" class="form-register" autocomplete="off">
        <div class="row">
          <div class="col-md-6">
            <h5 class="mb-1" style="color: lightgray;">Personal Information</h5>
            <hr class="m-0 mb-3 bg-secondary">

            <label for="name">Firstname:</label>
            <input type="text" name="firstname" class="w-100" value="<?= $row['firstname'] ?>">
          
            <label for="name">Lastname:</label>
            <input type="text" name="lastname" class="w-100" value="<?= $row['lastname'] ?>">

            <label for="gender">Gender:</label><br>
            <select name="gender" class="w-100">
              <option value="Male" <?php if($row['gender'] == "Male"){ echo "selected"; } ?>>Male</option>
              <option value="Female" <?php if($row['gender'] == "Female"){ echo "selected"; } ?>>Female</option>
            </select>

            <label for="country" class="mt-2">Country:</label>
            <input type="text" name="country" class="w-100" value="<?= $row['country'] ?>">

            <label for="state">State:</label>
            <input type="text" name="state" class="w-100" value="<?= $row['state'] ?>">
          </div>


          <div class="col-md-6">
            <h5 class="mb-1 mt-md-0 mt-4" style="color: lightgray;">Contact Information</h5>
            <hr class="m-0 mb-3 bg-secondary">

            <label for="address">Address</label><br>
            <textarea class="w-100" rows="3" name="address"><?= $row['address'] ?></textarea>

            <label for="mobile">Mobile:</label>
            <input type="text" name="mobile" class="w-100" value="<?= $row['mobile'] ?>">

            <label for="email">Email:</label>
            <input type="email" name="email" class="w-100" value="<?= $row['email'] ?>">

            <!--  -->
            <div class="row mt-md-5 mt-2">
              <div class="col-12 text-right">
                <a onclick="chgPwdDialog(event)" href="#" class="text-light text-decoration-none font-italic d-block mb-n2"><i class="fa fa-lock text-light"></i> Change Password</a>
                <span class="text-warning" style="font-size:11px">(Click to change existing password)</span>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-md-6 col-12">
            <input type="submit" name="update" value="Save Changes" class="w-100 text-dark bg-warning font-weight-bold border border-warning">
          </div>
        </div>

      </form>
    </div>
   </div>
</section>


<!-- Change password -->
<div class="popup_chgpwd_bg">
  <div id="change_pwd" class="px-3 pt-3 pb-1 rounded-lg">
    <form action="" method="post">
      <div class="head d-flex justify-content-between align-items-center mb-3">
        <h6 class="" style="font-size:18px;color:#e7781c;">Change Password</h6>

        <i class="fa fa-times mr-1" style="font-size:20px;color:#e7781c;cursor:pointer"></i>
      </div>

      <?php
        if(isset($_SESSION['change-pass'])){
          echo "<div class='small mb-3 font-italic' style='color:#333;font-weight:500'>" . $_SESSION['change-pass'] . "</div>";
          unset($_SESSION['change-pass']);
        }
      ?>

      <!-- <input type="hidden" name="pwd_id" value="<?= $id ?>"> -->
      <input type="password" name="curr_pass" class="w-100 mb-3" placeholder="Current Password...">

      <input type="password" name="new_pass" class="w-100" placeholder="New Password...">
      <input type="password" name="retype_pass" class="w-100" placeholder="Re-enter new password">

      <input onclick="changePassword(event, <?php echo $id ?>)" type="submit" name="change_pass" value="Change Password" class="w-100 bg-warning border-warning text-dark font-weight-bold mt-2">
    </form>
  </div>
</div>


<!-- Update user profile -->
<?php
  if(isset($_POST['update'])){
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
  

    // Insert user data to database table
    $update_user = "UPDATE user_reg SET
      firstname = '$firstname',
      lastname = '$lastname',
      country = '$country',
      state = '$state',
      address = '$address',
      email = '$email',
      mobile = '$mobile',
      gender = '$gender'

      WHERE id=$id
    ";

    $user = mysqli_query($conn, $update_user);
    if($user == false){
      $_SESSION['update-user'] = "<div class='text-light font-italic'>Registration Failed</div>";
    }
    else{
      echo "<script>location.href='" . SITEURL . "user-profile.php'</script>";
    }
  }
?>



   <script src="./script.js"></script>
</body>
</html>




<script>
  // Change Password Dialog
  function chgPwdDialog(event){    
    event.preventDefault();

    var ChgPwdPopup = document.querySelector(".popup_chgpwd_bg");
    var changePass = document.getElementById("change_pwd");

    ChgPwdPopup.classList.add("active");
    changePass.classList.add("active");

    var closeChgPwd = document.querySelector("#change_pwd i");
  

    // Close dialog box
      closeChgPwd.addEventListener("click", function(){
        ChgPwdPopup.classList.remove("active");
        changePass.classList.remove("active");
      });
  }


  // Submit to change password
  function changePassword(event, str){
    event.preventDefault();

    var chgPwdForm = document.querySelector("#change_pwd form");
      var xhr = new XMLHttpRequest();
         xhr.onload = function(){
            if(this.status == 200){
              if(this.responseText === "success"){
                // location.href = "logout.php";
                console.log("success")
              }
              else{
                console.log("failed")
                chgPwdDialog(event);
              }
            }
          }
          xhr.open("POST", "ajax/change-pwd.php", true);
          location.href = "edit-profile.php";
          
         var formData = new FormData(chgPwdForm);
         xhr.send(formData);
  }
</script>