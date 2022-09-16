<?php
   $caption = "My Profile";
   include "partials/header.php";
   include "config/check-login.php";
?>

<section class="acct-details">
   <div class="container-fluid px-md-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
         <div class="d-flex flex-column">
            <div class="home-icon d-flex justify-content-start align-items-center">
               <i class="fa fa-user-plus" style="font-size: 18px; color: #e7781c;"></i>
               <span class="ml-2" style="font-size: 16px; color: #e7781c;">My Profile</span>
            </div>
         </div>

         <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
            <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

            <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
         </div>
      </div>
   </div>
</section>


<!-- Get user's details from the database -->
<?php
   $user = mysqli_query($conn, "SELECT * FROM user_reg WHERE id=$id");
   if(mysqli_num_rows($user) == 1){
      while($row = mysqli_fetch_assoc($user)){
         $user_id = $row['id'];
         $firstname = $row['firstname'];
         $lastname = $row['lastname'];
         $gender = $row['gender'];
         $mobile = $row['mobile'];
         $email = $row['email'];
         $address = $row['address'];
         $country = $row['country'];
         $state = $row['state'];
         $account_id = $row['account_id'];
         $date_created = $row['date_created'];
      }
   }
   else{
      echo "Something went wrong";
   }
?>

<!-- Profile main content -->
<section class="profile mb-5">
   <div class="container-fluid px-md-5">
      <div class="user_profile bg-dark px-3 py-2 p-md-4 mb-3 rounded-lg">
         <div class="d-flex justify-content-between align-items-center mt-1 mb-2">
            <h5 class="m-0 text-light" style="">Personal Details</h5>

            <i class="fa fa-ellipsis-vertical text-light py-2 px-3 rounded-circle mb-0"></i>


            <!-- User Profile Menu -->
            <div class="profile_menu">
               <a href="<?= SITEURL ?>edit-profile.php">Edit Profile</a>
               <a onclick="deleteUser(event, <?php echo $id ?>)" href="#">Delete Account</a>
            </div>

         </div>
         <hr class="bg-secondary m-0 mb-3 mt-n1">
      
         <div class="row">
            <div class="col-md-5 col-12">
               <!-- Detail item -->
               <div class="row mb-3">
                  <div class="col-md-4 col-12 text-md-right">
                     <span style="color:orange; font-style:italic; font-size:14px">Fullname</span>
                  </div>
                  <div class="col-md-8 col-12 text-left">
                     <span class="text-light"><?= $firstname . " " . $lastname ?></span>
                  </div>
               </div>

               <!-- Detail item -->
               <div class="row mb-3">
                  <div class="col-md-4 col-12 text-md-right">
                     <span style="color:orange; font-style:italic; font-size:14px">Gender</span>
                  </div>
                  <div class="col-md-8 col-12 text-left">
                     <span class="text-light"><?= $gender ?></span>
                  </div>
               </div>

               <!-- Detail item -->
               <div class="row mb-3">
                  <div class="col-md-4 col-12 text-md-right">
                     <span style="color:orange; font-style:italic; font-size:14px">Mobile Number</span>
                  </div>
                  <div class="col-md-8 col-12 text-left">
                     <span class="text-light"><?= $mobile ?></span>
                  </div>
               </div>

               <!-- Detail item -->
               <div class="row mb-3">
                  <div class="col-md-4 col-12 text-md-right">
                     <span style="color:orange; font-style:italic; font-size:14px">Email</span>
                  </div>
                  <div class="col-md-8 col-12 text-left">
                     <span class="text-light"><?= $email ?></span>
                  </div>
               </div>

               <!-- Detail item -->
               <div class="row mb-3">
                  <div class="col-md-4 col-12 text-md-right">
                     <span style="color:orange; font-style:italic; font-size:14px">Address</span>
                  </div>
                  <div class="col-md-8 col-12 text-left">
                     <span class="text-light"><?= $address ?></span><br>
                     <span class="text-light"><?= $state . ", " . $country ?></span>
                  </div>
               </div>

            </div>
            
            <div class="col-md-6 col-12">
               <!-- Detail item -->
               <div class="row mb-3">
                  <div class="col-md-4 col-12 text-md-right">
                     <span style="color:orange; font-size:18px">FxTrade ID:</span>
                  </div>
                  <div class="col-md-8 col-12 text-left">
                     <span class="text-light" style="font-size:18px"><?= $account_id ?></span>
                  </div>
               </div>

               <!-- Detail item -->
               <div class="row mb-3">
                  <div class="col-md-4 col-12 text-md-right">
                     <span style="color:orange; font-size:18px">Created On:</span>
                  </div>
                  <div class="col-md-8 col-12 text-left">
                     <span class="text-light" style="font-size:18px">
                        <?php
                           $date = $date_created;
                           $date = strtotime($date);
                           echo $date = date("d-M-Y", $date);
                        ?>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>



      <!-- Account Details -->
      <div class="acct_details bg-dark p-3 p-md-4 rounded-lg">
         <div class="d-flex justify-content-between align-items-center">
            <h5 class="m-0 text-light" style="">Account Details</h5>
         </div>
         <hr class="bg-secondary m-0 mt-1 mb-2">

         <!-- Get number of accounts for user and the currency symbols -->
         <?php
            $user_acct = mysqli_query($conn, "SELECT acct_no FROM new_account WHERE user_id=$id");
            $num_acct = mysqli_num_rows($user_acct);
         ?>

         <div class="row">
            <div class="col-md-6 col-12 mb-n2">
               <span class="text-gray">No. of Accounts: &nbsp; <span class="text-warning" style="font-style:normal; font-size:18px"><?= $num_acct ?></span></span>
            </div>
            <div class="col-md-6 col-12 text-md-right">
               <span class="font-italic" style="color:lightgray; font-size:10px">( Current Balance includes total deposits and total withdrawals )</span>
            </div>
         </div>


         <div style="overflow-x:auto" class="mt-4">
            <table id="acct_table">
               <tr>
                  <th>Account</th>
                  <th>Type</th>
                  <th>Broker</th>
                  <th>Investment</th>
                  <th>Profit/Loss</th>
                  <th>Balance</th>
                  <th>Created</th>
                  <th>Action</th>
               </tr>

               <?php
                  $account = mysqli_query($conn, "SELECT id, acct_no FROM record_acct WHERE user_id=$id");
                  if(mysqli_num_rows($account) > 0){
                     $sn = 1;

                     while($rows = mysqli_fetch_assoc($account)){
                        $acct_no = $rows['acct_no'];
                        $acct_id = $rows['id'];

                        $get_acct = mysqli_query($conn, "SELECT * FROM new_account WHERE acct_no=$acct_no");
                        if(mysqli_num_rows($get_acct) == 1){
                           $val = mysqli_fetch_assoc($get_acct);

                           ?>
                              <tr>
                                 <!-- <td><?php //echo $sn++ ?></td> -->
                                 <td class="font-italic"><?php echo $val['acct_no'] ?></td>
                                 <td><?= $val['acct_type'] ?></td>
                                 <td><?= $val['broker'] ?></td>
                                 <td><?php echo $val['currency'] . number_format($val['balance'], 2, ".", ",") ?></td>

                                 <?php
                                    $profit = mysqli_query($conn, "SELECT SUM(profit) AS total FROM records WHERE user_id=$id AND acct_id=$acct_id");
                                    while($prof_row = mysqli_fetch_assoc($profit)){
                                       $acct_profit = $prof_row['total'];
                                       if($acct_profit == ""){
                                          echo "<td>0.00</td>";
                                       }
                                       else{
                                          echo "<td>" . $val['currency'] . number_format($acct_profit, 2, ".", ",") . "</td>";
                                       }
                                    }
                                 ?>

                                 <td>
                                    <?php
                                       echo $val['currency'] . " ";
                                       echo number_format($acct_profit + $val['balance'], 2, ".", ",");
                                    ?>
                                 </td>
                                 <td>
                                    <?php
                                       echo $date = date("d/m/Y", strtotime($val['date_added'])); 
                                    ?>
                                 </td>
                                 <td>
                                    <a class="px-1 font-italic" style="color:orange; font-size:13px" href="#" onclick="updateAcct(event, <?php echo $acct_id ?>)">Edit</a>

                                    <a class="px-1 font-italic" style="color:rgba(255,255,255,0.7); font-size:13px" href="#" onclick="deleteAcct(event, <?php echo $acct_id ?>)">Delete</a>
                                 </td>
                              </tr>
                           <?php
                        }
                     }
                  }
                  else{
                     ?>
                        <tr>
                           <td colspan="8" class="text-gray text-left py-2 font-italic">You currently don't have an account!</td>
                        </tr>
                     <?php
                  }
               ?>

            </table>
         </div>
      </div>
   </div>
</section>

<!-- Edit Account dialog box -->
<div id="edit-acct" class="px-3 pt-3 pb-2">
   <form method="post" autocomplete="off">
      <div class="head d-flex justify-content-between align-items-center">
         <h4 class="m-0 text-warning" style="font-size: 23px">Edit <span class="font-italic" style="color:lightgray">Account</span></h4>
         <i class="fa fa-times mr-1"></i>
      </div>

      <hr class="m-0 mb-3 mt-2 bg-secondary">

      <div class="row">
         <div class="col-6">
            <label for="">Acct No.</label>
            <input type="text" name="acct_no" id="edit_acct_no" class="w-100 mb-2" value="20199829">
         </div>
         <div class="col-6">
            <label for="">Type</label>
            <select name="acct_type"class="w-100 mb-2" id="edit_acct_type">
               <option value="Demo">Demo</option>
               <option value="Live">Live</option>
            </select>
         </div>
      </div>

      <div class="row">
         <div class="col-md-6 col-12">
            <select name="currency" id="edit_currency" class="w-100 mb-2">
               <option value="$">US Dollar</option>
               <option value="₦">Naira</option>
               <option value="£">Pound</option>
               <option value="€">EURO</option>
            </select>   
         </div>

         <div class="col-md-6 col-12">
            <!-- Broker -->
            <input type="text" name="broker" id="edit_broker" class="w-100 mb-2" value="Hotforex">
         </div>
      </div>

      <div class="row">
         <div class="col-md-6 col-12">
            <label for="">Open Amount</label>
            <input type="text" id="edit_bal" name="balance" class="w-100 mb-2">
         </div>
         <div class="col-md-6 col-12">
            <label for="">Created</label>
            <input type="text" id="edit_created" name="created" class="w-100 mb-2">
         </div>
      </div>

      <input type="hidden" name="acct_id" id="edit_acct_id">

      <input type="submit" id="updateAcct" value="Update Account" class="w-100 mt-3 py-2 text-dark bg-warning border-0">
   </form>
</div>

<!-- DELETE ACCOUNT -->
<div class="delete_popup">
   <div id="del_acct" class="p-3">
      <h5 class="font-weight-normal" style="color:#000">Delete Account</h5>
      <p class="mt-1" style="color:rgba(0,0,0,0.8); line-height:20px;">Deleting this account will permanently remove the account and all the records for this account.</p>

      <div id="popup_btn">
         <button id="cancel_delete">Cancel</button>
         <button id="ok_delete">Delete Account</button>
      </div>
   </div>
</div>

<!-- DELETE USER ACCOUNT -->
<div class="user_acct_popup">
   <div id="user_acct" class="p-3">
      <h5 class="font-weight-normal" style="color:#000">Delete User Account</h5>
      <p class="mt-1" style="color:rgba(0,0,0,0.8); line-height:20px;">Deleting this account will permanently remove your account with all transactions and records.</p>

      <div id="popup_btn">
         <button id="cancel_user">Cancel</button>
         <button id="ok_user">Delete Account</button>
      </div>
   </div>
</div>



<script src="./script.js"></script>
</body>
</html>


<script>
   // Edit Account
   function updateAcct(event, str){
      event.preventDefault();

      // Remove User Profile
      var userProfileMenu = document.querySelector('.profile_menu');
         userProfileMenu.classList.remove("active");

      var acctDialog = document.getElementById("edit-acct"),
         closeDialog = document.querySelector("#edit-acct .head i"),
         updateBtn = document.querySelector("#edit-acct form #updateAcct");
         document.body.style.overflow = "hidden";
      
         acctDialog.style.opacity = "1";
         acctDialog.style.zIndex = "10";
         acctDialog.style.transition = "all 0.1s linear";

         // Fill/populate account edit form with data of the clicked account
         var xhr = new XMLHttpRequest();
            xhr.open("GET", "ajax/populate-acct.php?acct_id="+str, true);
            xhr.onload = function(){
               if(this.status == 200){
                  var myObj = JSON.parse(this.responseText);

                  document.getElementById("edit_acct_id").value = myObj[0];
                  document.getElementById("edit_acct_no").value = myObj[1];
                  document.getElementById("edit_acct_type").value = myObj[2];
                  document.getElementById("edit_currency").value = myObj[3];
                  document.getElementById("edit_broker").value = myObj[4];
                  document.getElementById("edit_bal").value = myObj[5];
                  document.getElementById("edit_created").value = myObj[6];
               }
            }

            xhr.send();


         // Close Account edit dialog box
         closeDialog.addEventListener("click", function(){
            acctDialog.style.opacity = "0";
            acctDialog.style.zIndex = "-10";

            // Make window scrollable
            document.body.style.overflow = "scroll";
         });

         // Click update button
         updateBtn.addEventListener("click", function(){
            var acctForm = document.querySelector("#edit-acct form");
               
            var xhr = new XMLHttpRequest();
               xhr.onload = function(){
                  if(this.status == 200){
                     console.log(this.responseText);
                  }
               }
               xhr.open("POST", "ajax/update-acct.php", true);

               var formData = new FormData(acctForm);
               xhr.send(formData);
         });
   }


   // Permanently Delete Account
   function deleteAcct(event, str){
      event.preventDefault();

      // Open background and dialog box
      var deleteDialog = document.querySelector(".delete_popup");
         deleteDialog.classList.add("active");

      var delete_acct = document.getElementById("del_acct");
         delete_acct.classList.add("active");


      // Cancel Delete
      var cancelDelete = document.getElementById("cancel_delete");
         cancelDelete.onclick = () => {
            deleteDialog.classList.remove("active");
            delete_acct.classList.remove("active");
         }

      // OK Delete
      var okDelete = document.getElementById("ok_delete");
         okDelete.onclick = () => {
            deleteDialog.classList.remove("active");
            delete_acct.classList.remove("active");

            // Make HttpXML Request
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "ajax/delete-acct.php?acct_id="+str, true);
            xhr.onload = function(){
               if(this.status == 200){
                  location.href = "user-profile.php";

                  affirmText.innerHTML = "You have successfully deleted this account! Account has been permanently lost";
                  affirmPopup.classList.add("active");

                  setTimeout(function(){
                     affirmPopup.classList.remove("active");
                  }, 3000);
                  
               }
            }
            xhr.send();
         }

      // On click of window, close delete dialog
      window.onmouseup = (e) => {
         if(e.target !== delete_acct && e.target.parentNode !== delete_acct){
            deleteDialog.classList.remove("active");
            delete_acct.classList.remove("active");
         }
      }

      // On window scroll, close dialog
      window.onscroll = () => {
         deleteDialog.classList.remove("active");
         delete_acct.classList.remove("active");
      }
   }


   // Dropdown menu for user
   var userMenu = document.querySelector(".user_profile div>i");
      userMenu.addEventListener("click", function(ev){

         ev.preventDefault(); // Prevent windows on-right-click menu to display

         // userMenu.classList.add("active");
         // setTimeout(() => {
         //    userMenu.classList.remove("active");
         // }, 200);

         var userProfileMenu = document.querySelector('.profile_menu');
         userProfileMenu.classList.add("active");
         
         // On click of window, close delete dialog
         window.onmouseup = (e) => {
            if(e.target !== userProfileMenu && e.target.parentNode !== userProfileMenu){
               userProfileMenu.classList.remove("active");
            }
         }

         // On window scroll, close dialog
         window.onscroll = () => {
            userProfileMenu.classList.remove("active");
         }
   });


   // Delete User Account
   function deleteUser(event, str){
      event.preventDefault();

      // Remove User Profile
      var userProfileMenu = document.querySelector('.profile_menu');
         userProfileMenu.classList.remove("active");

      // Open background and dialog box
      var userDialog = document.querySelector(".user_acct_popup");
         userDialog.classList.add("active");

      var user_acct = document.getElementById("user_acct");
         user_acct.classList.add("active");


      // Cancel user
      var canceluser = document.getElementById("cancel_user");
         canceluser.onclick = () => {
            userDialog.classList.remove("active");
            user_acct.classList.remove("active");
         }

      // OK user
      var okuser = document.getElementById("ok_user");
         okuser.onclick = () => {
            userDialog.classList.remove("active");
            user_acct.classList.remove("active");

            // Make HttpXML Request
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "ajax/user-acct.php?user_id="+str, true);
            xhr.onload = function(){
               if(this.status == 200){
                  if(this.responseText == "success"){
                     location.href = "logout.php";

                     affirmText.innerHTML = "You have successfully deleted your account!";
                     affirmPopup.classList.add("active");

                     setTimeout(function(){
                        affirmPopup.classList.remove("active");
                     }, 3000);
                  }
               }
            }
            xhr.send();
         }

      // On click of window, close user dialog
      window.onmouseup = (e) => {
         if(e.target !== user_acct && e.target.parentNode !== user_acct){
            userDialog.classList.remove("active");
            user_acct.classList.remove("active");
         }
      }

      // On window scroll, close dialog
      window.onscroll = () => {
         userDialog.classList.remove("active");
         user_acct.classList.remove("active");
      }
   }
</script>
