<?php
   $caption = "Trading Records";

   include "partials/header.php";
   include "config/check-login.php";
?>

<section>
   <div class="container-fluid px-md-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
         <div class="d-flex flex-column">
            <div class="home-icon d-flex justify-content-start align-items-center">
               <i class="fa fa-book" style="font-size: 18px; color: #e7781c;"></i>
               <span class="ml-2" style="font-size: 16px; color: #e7781c;">Trade Records</span>
            </div>
         </div>

         <div id="date_time" class="d-flex flex-column text-right" style="font-size:12px">
            <h5 class="font-italic text-secondary d-inline m-0" style="font-size:12px">Signed as: <span class="text-dark" style="font-style: normal;"><?= $firstname . " " ?></span><span class="text-dark" style="font-size: 12px;">(ID: <span class="font-weight-bold"><?= $acctID . ")" ?></span></span></h5>

            <div id="date" class="text-dark"><span>Date:</span><span class="text-dark"></span></div>
         </div>
      </div>

      <div class="bg-dark p-3 rounded-lg">
         <form action="" method="POST" class="form-record" autocomplete="off">
            <div class="row">
               <div class="col-md-3 col-12">
                  <div class="form-group">
                     <label for="acct_no">Account No.</label>
                     <select name="acct_id" class="w-100 mb-md-0 mb-2" onchange="updAcctStat(this.value)";>
                        <option value="0" selected disabled>--Select Account--</option>

                        <?php
                           $sel_acct = mysqli_query($conn, "SELECT * FROM record_acct WHERE user_id=$id");
                           if(mysqli_num_rows($sel_acct) > 0){
                              while($row = mysqli_fetch_assoc($sel_acct)){
                                 ?>
                                    <option value="<?php echo $row['id'] ?>" <?php if($row['status'] == 1){echo "selected";} ?>><?php echo $row['acct_no'] . " (" . $row['acct_type'] . ")" ?></option>
                                 <?php
                              }
                           }
                        ?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="row no-gutter">
               <div class="col-md-2 col-7">
                  <select name="pair" class="w-100 mb-md-0 mb-2">
                     <?php
                       $get_pair = mysqli_query($conn, "SELECT * FROM currency_pair WHERE active=1 ORDER BY pair ASC");
                       if(mysqli_num_rows($get_pair) > 0){
                          while($pair_rows = mysqli_fetch_assoc($get_pair)){
                            ?>
                              <option value="<?php echo $pair_rows['pair'] ?>"><?php echo $pair_rows['pair'] ?></option>
                            <?php
                          }
                       }
                     ?>
                  </select>
               </div>
               <div class="col-md-2 col-5">
                  <select name="position" class="w-100 mb-md-0 mb-2">
                     <option value="Buy">BUY</option>
                     <option value="Sell">SELL</option>
                  </select>
               </div>
               <div class="col-md-2 col-5">
                  <input type="text" name="lotsize" class="w-100 mb-md-0 mb-2" placeholder="Lot Size...">
               </div>
               <div class="col-md-2 col-7">
                  <input type="text" name="profit" class="w-100 mb-md-0 mb-2" placeholder="Enter Profit/Loss...">
               </div>
               <div class="col-md-2 col-7">
                  <input type="submit" name="submit" value="Add Record" class="w-100 mt-md-0 mt-3 text-dark font-weight-bold bg-warning border-warning">
               </div>
               <div class="col-md-2 col-5">
                  <input type="submit" name="clear" value="Clear" class="w-100 mt-md-0 mt-3 text-warning border border-warning">
               </div>
            </div>
            <input type="hidden" name="id" value="<?= $id ?>">
         </form>
      </div>
   </div>
</section>


<!-- Enter trading record -->
<?php
   // Clear fields
   if(isset($_POST['clear'])){
      $_POST['lotsize'] == "";
      $_POST['profit'] == "";
   }

   // Save data entered
   if(isset($_POST['submit'])){
      $user_id = $id;
      $acct_id = mysqli_real_escape_string($conn, $_POST['acct_id']);
      $position = mysqli_real_escape_string($conn, $_POST['position']);
      $pair = mysqli_real_escape_string($conn, $_POST['pair']);
      $lotsize = mysqli_real_escape_string($conn, $_POST['lotsize']);
      $profit = mysqli_real_escape_string($conn, $_POST['profit']);
      $record_date = date("Y-m-d");

      if($acct_id !== "" && $position !=="" && $lotsize !== "" && $profit !== "" && $pair !== ""){
         
         // Add record to database
         $add = "INSERT INTO records SET
            user_id = $user_id,
            acct_id = $acct_id,
            position = '$position',
            pair = '$pair',
            lotsize = '$lotsize',
            profit = '$profit',
            record_date='$record_date'
         ";

         // Query against the database
         $add_res = mysqli_query($conn, $add);

         if($add_res == false){
            echo "<div class='-fluid px-md-5 mt-2 text-secondary font-italic'>Failed to add record</div>";
         }
         else{
            echo "<script>location.href='records.php'</script>";
         }
      }
      else{
         echo "<div class='container mt-2 text-secondary font-italic'>Fill all record fields</div>";
      }
   }
?>


<section id="info-messages" class="my-5">
   <div class="container-fluid px-md-5">
      <div class="mb-2">
         <?php
            if(isset($_SESSION['edit-trade-success'])){
               echo "<div class='alert alert-success alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['edit-trade-success'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";

               unset($_SESSION['edit-trade-success']);
            }

            if(isset($_SESSION['edit-trade-fail'])){
               echo "<div class='alert alert-danger alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['edit-trade-fail'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
               
               unset($_SESSION['edit-trade-fail']);
            }
         ?>
      </div>

      <div class="row">
         <div class="col-6 align-items-center">
            <h6 class="text-dark m-0" style="font-size: 14px">Account Trades</h6>
         </div>

         <?php
            $getAcctID = mysqli_query($conn, "SELECT id FROM record_acct WHERE user_id=$id AND status=1");
               if(mysqli_num_rows($getAcctID) == 1){
                  $id_row = mysqli_fetch_assoc($getAcctID);
                  $account_id = $id_row['id'];
               }
         ?>

         <div class="col-6 text-right">
            <a href="<?= SITEURL ?>acct_details.php?acct_id=<?php echo $account_id ?>" class="text-decoration-none mb-2" style="color:#e7781c; font-size:13px;">View all &raquo;</a>
            <?php
               if(isset($_SESSION['del-rec'])){
                  echo $_SESSION['del-rec'];
                  unset($_SESSION['del-rec']);
               }
            ?>
         </div>
      </div>
   </div>

   <div class="record_tbl" style="overflow-x: auto; font-size:14px">
      <table class="table-striped w-100 mb-2" style="font-size:11px">
         <thead>
            <tr>
               <th class="font-weight-bold text-warning font-italic">DATE</th>
               <th class="font-weight-bold" style="color:lightgray; line-height:13px">Currency<div class="text-info font-italic">PAIR</div></th>
               <th>POSITION</th>
               <th>SIZE</th>
               <th>PROFIT</th>
               <th>Edit/Delele</th>
            </tr>
         </thead>

         <tbody id="bodyRow">
            <!-- Table data loaded here through AJAX -->
         </tbody>
      </table>
   </div>
</section>


<!-- Edit Record dialog box -->
<div class="popup_edit_rec">
   <div id="edit-rec" class="px-3 pt-3 pb-2">
      <form class="edit-form" method="post" autocomplete="off">
         <div class="head d-flex justify-content-between align-items-center mb-3">
            <h6 class="m-0" style="font-size:18px;color:#e7781c;">Edit Trade</h6>

            <i class="fa fa-times mr-1" style="font-size:20px;color:#e7781c;cursor:pointer"></i>
         </div>

         <h4 class="mb-2" style="font-size:17px"><span class="text-secondary">Account:</span> <span id="edit_acct_no" style="color:#333">40596889</span> <span id="edit_acct_type" class="font-italic font-weight-normal" style="color:#e7781c">(Live)</span></h4>

         <select name="pair" class="w-100 mb-2" id="edit_pair">
            <?php
               $get_pair = mysqli_query($conn, "SELECT * FROM currency_pair WHERE active=1");
               if(mysqli_num_rows($get_pair) > 0){
                  while($pair_rows = mysqli_fetch_assoc($get_pair)){
                     ?>
                        <option value="<?php echo $pair_rows['pair'] ?>"><?php echo $pair_rows['pair'] ?></option>
                     <?php
                  }
               }
            ?>
         </select>

         <select name="position" class="w-100 mb-2" id="edit_position">
            <option value="Buy">BUY</option>
            <option value="Sell">SELL</option>
         </select>

         <div class="row">
            <div class="col-md-5 col-12">
               <input type="text" id="edit_lotsize" name="lotsize" class="w-100 mb-2" placeholder="Lot Size...">
            </div>
            <div class="col-md-7 col-12">
               <input type="text" id="edit_profit" name="profit" class="w-100 mb-2" placeholder="Enter Profit/Loss...">
            </div>
         </div>

         <input type="hidden" name="rec_id" id="edit_rec_id">

         <input type="submit" id="updateRec" value="Save Changes" class="w-100 mt-3 py-2 text-dark bg-warning border-0">
      </form>
   </div>
</div>


<!-- Delete record dialog box -->
<div class="popup_bg">
   <div id="delete_rec" class="p-3">
      <h5 class="font-weight-normal" style="color:#000">Delete Record</h5>
      <p class="mt-1" style="color:rgba(0,0,0,0.8); line-height:20px;">Are you sure you want to delete this record?</p>

      <div id="popup_btn">
         <button id="cancel_del">Cancel</button>   
         <button id="ok_del">OK</button>
      </div>
   </div>
</div>


   <script src="./script.js"></script>
</body>
</html>



<!-- Javascript codes with AJAX -->
<script>
   function updAcctStat(){
      var recForm = document.querySelector(".form-record");
      var xhr = new XMLHttpRequest();
         xhr.onload = function(){
            if(this.status == 200){
               document.getElementById("bodyRow").innerHTML = this.responseText;
               location.href = "records.php";
            }
         }
         xhr.open("POST", "ajax/getAcctid.php", true);

         var formData = new FormData(recForm);
         xhr.send(formData);
   }



   // Get record form - onload event
   var recForm = document.querySelector(".form-record");

   var xhr = new XMLHttpRequest();
   xhr.onload = function(){
      if(this.status == 200){
         document.getElementById("bodyRow").innerHTML = this.responseText;
      }
   }
   xhr.open("POST", "ajax/getAcctid.php", true);

   var formData = new FormData(recForm);
   xhr.send(formData);



   // Permanently Delete Trade Record
   function deleteRecord(event, str){
      event.preventDefault();

      // Open background and dialog box
      var deleteDialog = document.querySelector(".popup_bg");
         deleteDialog.classList.add("active");

      var deleteRec = document.getElementById("delete_rec");
         deleteRec.classList.add("active");


      // Cancel Delete
      var cancelDelete = document.getElementById("cancel_del");
         cancelDelete.onclick = () => {
            deleteDialog.classList.remove("active");
            deleteRec.classList.remove("active");
         }

      // OK Delete
      var okDelete = document.getElementById("ok_del");
         okDelete.onclick = () => {
            deleteDialog.classList.remove("active");
            deleteRec.classList.remove("active");

            // Make HttpXML Request
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "ajax/delete-rec.php?rec_id="+str, true);
            xhr.onload = function(){
               if(this.status == 200){
                  location.href = "records.php";

                  affirmText.innerHTML = "You have successfully deleted this trade record! Record has been permanently lost";
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
         if(e.target !== deleteRec && e.target.parentNode !== deleteRec){
            deleteDialog.classList.remove("active");
            deleteRec.classList.remove("active");
         }
      }

      // On window scroll, close dialog
      window.onscroll = () => {
         deleteDialog.classList.remove("active");
         deleteRec.classList.remove("active");
      }
   }


   // Update Record
   function updateRecord(event, str){
      event.preventDefault();

      var popupEditRec = document.querySelector(".popup_edit_rec");
      var editDialog = document.getElementById("edit-rec");

         popupEditRec.classList.add("active");
         editDialog.classList.add("active");

         closeDialog = document.querySelector("#edit-rec .head i"),
         updateBtn = document.querySelector("#edit-rec form #updateRec");
      
         // Fill/populate edit form with data of the clicked record
         var xhr = new XMLHttpRequest();
            xhr.open("GET", "ajax/populate-edit.php?rec_id="+str, true);
            xhr.onload = function(){
               if(this.status == 200){
                  var myObj = JSON.parse(this.responseText);

                  document.getElementById("edit_acct_no").innerHTML = myObj[0];
                  document.getElementById("edit_acct_type").innerHTML = "(" + myObj[1] + ")";
                  document.getElementById("edit_lotsize").value = myObj[2];
                  document.getElementById("edit_profit").value = myObj[3];
                  document.getElementById("edit_rec_id").value = myObj[4];
                  document.getElementById("edit_pair").value = myObj[5];
                  document.getElementById("edit_position").value = myObj[6];



                  // Click update button
                  updateBtn.addEventListener("click", function(){
                     var editForm = document.querySelector(".edit-form");
                        
                     var xhr = new XMLHttpRequest();
                        xhr.open("POST", "ajax/update-rec.php", true);
                        xhr.onload = function(){
                           if(this.status == 200){
                             location.href = "records.php";
                           }
                        }

                        var formData = new FormData(editForm);
                        xhr.send(formData);
                  });
               }
            }

            xhr.send();


         // Close edit dialog box
         closeDialog.addEventListener("click", function(){
            popupEditRec.classList.remove("active");
            editDialog.classList.remove("active");
         });
   }
</script>