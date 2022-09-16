<?php
   $caption = "My Savings";

   include "partials/header.php";
   include "config/check-login.php";
?>

<!-- Main section -->
<section class="container-fluid px-md-5">
  <div class="d-flex justify-content-between align-items-center">
    <div class="d-flex flex-column">
      <div class="home-icon d-flex justify-content-start align-items-center">
          <i class="fa fa-credit-card" style="font-size: 18px; color: #e7781c;"></i>
          <span class="ml-2" style="font-size: 16px; color: #e7781c;">My Savings</span>
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

<section class="mb-4 mx-auto" style="max-width:400px">
  <div class="container-fluid px-md-3">
    <h6 class="m-0 text-dark mt-2 text-uppercase font-weight-bold">Savings <span class="text-secondary font-italic">Details</span></h6>
    <p class="mb-3 text-secondary" style="font-size:10px; color: #e7781c;">(Details of periodic savings with total for every month and at the end of the year)</p>
  </div>

  <!-- CONFIRMATIONS -->
  <div class="confirmations">
    <?php
      if(isset($_SESSION['receipt-image'])){
        echo "<div class='alert alert-danger alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['receipt-image'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
        unset($_SESSION['receipt-image']);
      }

      if(isset($_SESSION['trans-success'])){
        echo "<div class='alert alert-success alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['trans-success'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
        unset($_SESSION['trans-success']);
      }

      if(isset($_SESSION['trans-failed'])){
        echo "<div class='alert alert-danger alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['trans-failed'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
        unset($_SESSION['trans-failed']);
      }

      if(isset($_SESSION['update-success'])){
        echo "<div class='alert alert-success alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['update-success'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
        unset($_SESSION['update-success']);
      }

      if(isset($_SESSION['update-failed'])){
        echo "<div class='alert alert-danger alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['update-failed'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
        unset($_SESSION['update-failed']);
      }

      if(isset($_SESSION['del-success'])){
        echo "<div class='alert alert-success alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['del-success'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
        unset($_SESSION['del-success']);
      }

      if(isset($_SESSION['del-failed'])){
        echo "<div class='alert alert-danger alert-dismissible font-italic mt-n1 mb-1 border-0' role='alert' style='font-size:13px; font-weight:500'>"  . $_SESSION['del-failed'] . "<a href='' class='close p-0 mt-2 mr-3' data-dismiss='alert' aria-label='close'><i class='fa fa-times small'></i></a></div>";
        unset($_SESSION['del-failed']);
      }
    ?>
  </div>

  <!-- PLAN TABLE OF DETAILS -->
  <div class="saving_table">
    <div class="container-fluid px-md-3 btn_container">
      <div class="trans">
        <h6 class="m-0 text-dark" style="font-weight:500">Savings Details</h6>

        <div class="div">
          <button type="button" id="edit_savings_item" onclick="editSaving(event)">Edit</button>

          <button type="button" id="delete_savings_item" onclick="deleteTransaction(event)">Delete</button>

          <button type="button" onclick="newSaving(event)">New</button>
        </div>
      </div>
    </div>

    <div style="overflow-x:auto">
      <table class="table-sm">       
        <tr>
          <th class="pl-2"><input type="checkbox" id="sel_all"></th>
          <th class="pr-3 pl-2">DATE</th>
          <th class="pr-4 text-left" style="white-space: nowrap">Transaction Description</th>
          <th class="px-2 text-info text-right" style="font-weight:600">CREDIT</th>
          <th class="px-2 text-warning font-italic text-right">DEBIT</th>
          <th>Trans. <br> Receipt</th>
        </tr>

        <!-- Loop start -->
        <?php
          $this_month = date("m");
          $this_year = date("Y");

          $getTrans = mysqli_query($conn, "SELECT DISTINCT MONTH(trans_date) AS month, YEAR(trans_date) AS year FROM savings WHERE user_id=$id GROUP BY month ORDER BY month DESC");

          while($month_row = mysqli_fetch_assoc($getTrans)){
            $month = $month_row['month'];
            $the_month = date("F", mktime(0, 0, 0, $month, 10));

            $year = $month_row['year'];

            ?>
              <tr class="saving_month bg-light">
                <td style="border-bottom:2px solid lightgray"></td>
                <td colspan="8" class="text-dark font-weight-bold text-uppercase text-left py-2" style="color: #e7781c; border-bottom:2px solid lightgray">
                  <?php echo "<span class='font-italic' style='color: #e7781c;'>" . $the_month . " " . $year . "</span> Transactions" ?>
                </td>
              </tr>
            <?php


            // GET SAVING TRANSACTIONS FOR MONTH
            $getMonthTrans = mysqli_query($conn, "SELECT * FROM savings WHERE MONTH(trans_date) = $month AND YEAR(trans_date) = $year AND user_id=$id ORDER BY trans_date DESC");

            while($trans_row = mysqli_fetch_assoc($getMonthTrans)){
              $week_day = date("l", strtotime($trans_row['trans_date']));
              $week_day = substr($week_day, 0, 3); // Truncate to Sun, Mon, Tues etc

              ?>
                <tr>
                  <td class="pl-2"><div class="text-center"><input type="checkbox" class="checked_items" name="sel_items[]" value="<?= $trans_row['id'] ?>"></div></td>

                  <td style="font-weight:600"><?= "<span class='text-secondary font-italic'>" . $week_day . "</span>, " . date("d", strtotime($trans_row['trans_date'])) ?></td>

                  <td class="text-left">
                    <?= "<span style='font-weight:500'>" . $trans_row['trans_desc'] . "</span>" ?>
                    
                  </td>

                  <td style="font-weight:700" class="text-right"><?php if($trans_row['trans_type'] == "Deposit") { echo number_format($trans_row['amount'], 2, ".", ","); } ?></td>

                  <td style="color:#e7781c;font-weight:700;font-style:italic" class="text-right"><?php if($trans_row['trans_type'] == "Withdrawal") { echo number_format($trans_row['amount'], 2, ".", ",");} ?></td>

                  <td>
                    <div class="receipt mx-auto" style="cursor:pointer;">
                      <?php
                        if($trans_row['receipt'] !== ""){
                          ?>
                            <img src="./images/receipts/<?= $trans_row['receipt'] ?>" class="img-fluid">
                          <?php
                        }
                        else{
                          echo "---";
                        }
                      ?>
                    </div>
                  </td>
                </tr>
              <?php
            }

            ?>
              <tr class="text-white bg-dark border-0">
                <td colspan="1"></td>
                <td colspan="2" class="text-uppercase text-right text-white font-weight-bold pb-0">TOTAL FOR <?= $the_month . " " . $year . " =" ?></td>
                <td style="color:#fff;font-weight:500" class="pb-0">
                  <?php
                    // Get SUM of total deposited amount for a month
                    $deposits = mysqli_query($conn, "SELECT SUM(amount) AS deposits FROM savings WHERE MONTH(trans_date) = $month AND YEAR(trans_date) = $year AND user_id=$id AND trans_type='Deposit'");

                    while($dep_row = mysqli_fetch_assoc($deposits)){
                      $total_deposits = $dep_row['deposits'];
                    }

                    echo number_format($total_deposits, 2, ".", ",");
                  ?>
                </td>

                <td class="text-warning font-italic pb-0" style="font-weight:500">
                  <?php
                    // Get SUM of total deposited amount for a month
                    $withdrawals = mysqli_query($conn, "SELECT SUM(amount) AS withdrawals FROM savings WHERE MONTH(trans_date) = $month AND YEAR(trans_date) = $year AND user_id=$id AND trans_type='Withdrawal'");

                    while($dep_row = mysqli_fetch_assoc($withdrawals)){
                      $total_withdrawals = $dep_row['withdrawals'];
                    }

                    echo number_format($total_withdrawals, 2, ".", ",");
                  ?>
                </td>
                <td></td>
              </tr>

              <tr class="text-white bg-dark border-0 saving_outcome">
                <td></td>
                <td colspan="2" class="text-uppercase text-right font-weight-bold font-italic pt-0 pr-3" style="color:lightgray">MONTH TOTAL =</td>
                <td class="font-weight-bold pt-0">
                  <?php
                    $outcome = $total_deposits - $total_withdrawals;
                    if($outcome < 0){
                      echo "<div class='text-warning font-italic'>(" . number_format($outcome, 2, ".", ",") . ")</div>";
                    }
                    else{
                      echo "<div style='color:lightgray'>" . number_format($outcome, 2, ".", ",") . "</div>";
                    }
                  ?>
                </td>
                <td colspan="2"></td>
              </tr>
            <?php
          }          
        ?>
        <!-- Loop ends -->


        <!-- OVERALL TOTAL FOR ALL MONTHS -->
        <tr class="bg-secondary overall_total">
          <td colspan="2"></td>
          <td class="text-center py-2 text-white">OVERALL TOTAL =</td>
          <td class="text-white">
            <?php
              // Get the net total for all the months
              $allDeposits = mysqli_query($conn, "SELECT SUM(amount) AS deposits FROM savings WHERE user_id=$id AND trans_type='Deposit'");

              while($allDep_row = mysqli_fetch_assoc($allDeposits)){
                $overallDeposits = $allDep_row['deposits'];
              }


              $allWithdrawals = mysqli_query($conn, "SELECT SUM(amount) AS withdrawals FROM savings WHERE user_id=$id AND trans_type='Withdrawal'");

              while($allWit_row = mysqli_fetch_assoc($allWithdrawals)){
                $overallWithdrawals = $allWit_row['withdrawals'];
              }

              $overallTotal = $overallDeposits - $overallWithdrawals;
              echo number_format($overallTotal, 2, ".", ",");
            ?>
          </td>
          <td colspan="2"></td>
        </tr>
      </table>
    </div>
  </div>
</section>



<!-- New Transaction Form -->
<div class="popup_new_saving">
  <div id="new_savings" class="p-3">
    <div id="" class="bg-light p-1 pb-3 rounded-lg">
      <form method="post" class="new_savings" autocomplete="off" enctype="multipart/form-data">
        <div class="d-flex justify-content-between align-items-center">
          <h6 class="m-0 p-3" style="font-size:18px;color:#e7781c;">New Transaction</h6>

          <i class="fa fa-times mr-3" style="font-size:20px;color:#e7781c;cursor:pointer"></i>
        </div>

        <?php
          if(isset($_SESSION['receipt-image'])){
            echo "<div class='text-warning font-italic text-right mr-3 mt-n2' style='font-size:12px'>"  . $_SESSION['receipt-image'] . "</div>";
            unset($_SESSION['receipt-image']);
          }
        ?>

        <div class="col-12">
          <label for="type">Transaction Type:</label>
          <select name="trans_type" id="type" class="w-100">
            <option value="Deposit">Deposit</option>
            <option value="Withdrawal">Withdrawal</option>
          </select>
        </div>

        <div class="col-12">
          <label for="amount">Description:</label>
          <input type="text" name="desc" class="w-100 mb-2" id="trans_desc" placeholder="Description...">
        </div>

        <div class="col-12">
          <label for="amount">Amount:</label>
          <input type="text" name="amount" class="w-100 mb-2" id="amount" placeholder="Trans. Amount...">
        </div>

        <div class="col-12">
          <label for="amount">Upload Receipt:</label>
          <input type="file" name="receipt" class="w-100 mb-2" id="file">
        </div>
      
        <div class="col-12 mt-2">
          <input type="submit" id="add_saving" name="save" value="Save Transaction" class="w-100 text-dark bg-warning border-0" style="font-weight:600" onclick="saveTransaction(event)">
        </div>
      </form>
    </div>
   </div>
</div>


<!-- Edit Transaction Form -->
<div class="popup_edit_saving">
  <div id="edit_savings" class="p-3"></div>

  <?php
    if(isset($_POST['update_trans'])){
      $trans_id = mysqli_real_escape_string($conn, $_POST['selected_id']);
      $trans_type = mysqli_real_escape_string($conn, $_POST['trans_type']);
      $trans_desc = mysqli_real_escape_string($conn, $_POST['desc']);
      $trans_amount = mysqli_real_escape_string($conn, $_POST['amount']);
      $trans_receipt = mysqli_real_escape_string($conn, $_POST['curr_receipt']);

      // Select and load image to database
      if(isset($_FILES['new_receipt']['name'])){

        $new_receipt = $_FILES['new_receipt']['name'];

        // check if image is selected
        if($new_receipt != ""){
          // Rename receipt name
          $ext = explode(".", $new_receipt);
          $extension = end($ext);
          $new_receipt = "Receipt" . rand(10,99) . "_" . date("Ymd") . "." . $extension; // Custom Receipt Name

          $src = $_FILES['new_receipt']['tmp_name'];
          $dest_path = "./images/receipts/" . $new_receipt;

          $upload_receipt = move_uploaded_file($src, $dest_path);

          if($upload_receipt == false){
            $_SESSION['update-failed'] = "<div class='error'>Failed to Upload Receipt</div>";
            echo "<script>location.href='savings.php'</script>";
            die();
          }
          else{
            if($trans_receipt !=""){
              // Remove/Unlink the image
              $dest_path = "./images/receipts/" . $trans_receipt;
              $remove = unlink($dest_path);

              if($remove==false){
                 $_SESSION['update-failed'] = "<div class='error'>Failed to remove current receipt image</div>";
                 echo "<script>location.href='savings.php'</script>";
                 die();
              }
            }
          }
        }
        else{
          $new_receipt = $trans_receipt;
        }
      }
      else {
          $new_receipt = $trans_receipt;
      }


    //  Update Transaction to DB
    // Check for empty transaction type and amount form values
    if(empty($trans_type) || empty($trans_amount)){
      $_SESSION['update-failed'] = "<div class='error'>Incomplete transaction details!</div>";
      echo "<script>location.href='savings.php'</script>";
      die();
    }
    else{
      $updateTrans = "UPDATE savings SET
        user_id = $id,
        trans_type = '$trans_type',
        trans_desc = '$trans_desc', 
        amount = '$trans_amount',
        receipt = '$new_receipt'

        WHERE id=$trans_id
      ";

        $updateTrans_res = mysqli_query($conn, $updateTrans);

        if($updateTrans_res == true){
          $_SESSION['update-success'] = "<div class='error'>Transaction Updated Successful</div>";
          echo "<script>location.href='savings.php'</script>";
        }
      }
    }
  ?>
</div>


<!-- Delete Transaction -->
<div class="popup_trans_del">
   <div id="trans_del" class="p-3">
      <h5 class="font-weight-normal" style="color:#000">Delete Transaction</h5>
      <p class="mt-1" style="color:rgba(0,0,0,0.8); line-height:20px;">Are you sure you want to delete this transaction? You will lost the transaction permanently.</p>

      <div id="popup_btn">
         <button id="cancel_trans_del">No</button>
         <button id="ok_trans_del">Yes, Delete</button>
      </div>
   </div>
</div>


<!-- Show Receipt Image -->
<div class="popup_receipt">
  <div id="show_receipt" class="text-center">
    <img src="./images/receipts/Receipt27_20220709.png" class="img-fluid">
   </div>
</div>


<script src="./script.js"></script>
</body>
</html>



<script>
  var transDesc = document.getElementById("trans_desc");
    transDesc.addEventListener("input", function(){
      transDesc.value = transDesc.value.substr(0, 30);
    })


  // SCRIPT TO SELECT MULTIPLE CHECKBOXES TO DELETE OR GENERATE SALES INVOICE
  var selectAll = document.getElementById("sel_all");
   var checkboxes = document.getElementsByClassName("checked_items");
   var editSavingItem = document.querySelector("#edit_savings_item"),
       deleteSavingItem = document.querySelector("#delete_savings_item");

   var checkedList = [];

    for(var checkbox of checkboxes){ 
      checkbox.addEventListener("click", function(){
        if(this.checked == true){
          checkedList.push(this.value);

          if(checkedList.length > 0){
            editSavingItem.classList.add("active");
            deleteSavingItem.classList.add("active");

            deleteSavingItem.innerHTML = "Delete" + " <span class='font-italic' style='font-size:12px'>(" + checkedList.length + ")</span>";
          }

          if(checkedList.length > 1){
            editSavingItem.classList.remove("active");
          }          

          // CHECK IF TOTAL CHECKED CHECKBOXES = THE TOTAL CHECKBOX ELEMENTS
          if(checkedList.length === checkboxes.length){
              selectAll.checked = true;
              deleteSavingItem.innerHTML = "Delete All";
          }
        }
        else{ // if the element is unchecked, remove it from the checked list array
          checkedList.pop(this.value);

          

          if(checkedList.length < 1){
            editSavingItem.classList.remove("active");
            deleteSavingItem.classList.remove("active");
          }

          if(checkedList.length == 1){
            editSavingItem.classList.add("active");
          }

          if(checkedList.length < checkboxes.length){
              selectAll.checked = false;
              deleteSavingItem.innerHTML = "Delete" + " <span class='font-italic' style='font-size:12px'>(" + checkedList.length + ")</span>";
          }
        }
      })
    }


   // SELECT ALL CHECKBOXES
   selectAll.onclick = () => {
    for (let i = 0; i < checkboxes.length; i++){
      if(selectAll.checked){
          checkboxes[i].checked = true;
          checkedList.length = checkboxes.length;

          deleteSavingItem.classList.add("active");
          editSavingItem.classList.remove("active");
          deleteSavingItem.innerHTML = "Delete All";
      }
      else{
          checkboxes[i].checked = false;
          checkedList.length = 0;

          editSavingItem.classList.remove("active");
          deleteSavingItem.classList.remove("active");

          deleteSavingItem.innerHTML = "Delete";
      }
    }
   }



  // Open New Savings Dialog Box
  function newSaving(event){
    event.preventDefault();

    // Open background and dialog box
    var newSavingPopup = document.querySelector(".popup_new_saving");
        newSavingPopup.classList.add("active");

    var newSaving = document.getElementById("new_savings");
        newSaving.classList.add("active");

    // Close Dialog Box
    document.querySelector(".new_savings .fa-times").addEventListener("click", function(){
      newSavingPopup.classList.remove("active");
      newSaving.classList.remove("active");
    })
  }

  // Open Edit Savings Dialog Box
  function editSaving(event){
    event.preventDefault();

    // Open background and dialog box
    var editSavingPopup = document.querySelector(".popup_edit_saving");
        editSavingPopup.classList.add("active");

    var editSaving = document.getElementById("edit_savings");
        editSaving.classList.add("active");


    // Get ID of selected transaction itema and display edit form for the item
    var checkedItems = [];
    for(i = 0; i < checkboxes.length; i++){
      if(checkboxes[i].checked == true){
          checkedItems.push(checkboxes[i].value);
      }
    }
    
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "./ajax/edit_savings.php?sel_id=" + checkedItems, true);
    xhr.onload = function(){
      if(this.status === 200){
        document.getElementById("edit_savings").innerHTML = this.responseText;

        // Close Dialog Box to edit savings transaction
        document.querySelector(".edit_savings .fa-times").addEventListener("click", function(){
          editSavingPopup.classList.remove("active");
          editSaving.classList.remove("active");
        })
      }
    }
    
    xhr.send();
  }


  // Delete transaction
  function deleteTransaction(event){
    event.preventDefault();

    // Open background and dialog box
    var delTransDialog = document.querySelector(".popup_trans_del");
        delTransDialog.classList.add("active");

    var delTrans = document.getElementById("trans_del");
        delTrans.classList.add("active");


    // Cancel Delete
    var cancelTransDelete = document.getElementById("cancel_trans_del");
        cancelTransDelete.onclick = () => {
        delTransDialog.classList.remove("active");
        delTrans.classList.remove("active");
      }


    // Confirm delete
    document.getElementById("ok_trans_del").addEventListener("click", function(){
      var checkedItems = [];

      for(i = 0; i < checkboxes.length; i++){
        if(checkboxes[i].checked == true){
            checkedItems.push(checkboxes[i].value);
        }
      }
      
      var xhr = new XMLHttpRequest();
        xhr.open("GET", "./ajax/delete_transaction.php?sel_items=" + checkedItems, true);
        xhr.onload = function(){
          if(this.status === 200){
            location.href = "savings.php";                    
          }
        }
        
        xhr.send();

        delTransDialog.classList.remove("active");
        delTrans.classList.remove("active");
    });
  }


  // Show Receipt Image on click of receipt image
  var receiptImg = document.querySelectorAll(".receipt img");

  for(let i = 0; i < receiptImg.length; i++){
    receiptImg[i].addEventListener("click", function(){
      var imgSrc = receiptImg[i].src

      document.querySelector(".popup_receipt img").src = imgSrc; // Apply image source to popup
      
      // Open background and dialog box
      var popupReceipt = document.querySelector(".popup_receipt");
          popupReceipt.classList.add("active");

      var dispReceipt = document.getElementById("show_receipt");
          dispReceipt.classList.add("active");



      // On window click, close dialog box
      window.onmouseup = (e) => {
        if(e.target !== dispReceipt && e.target.parentNode !== dispReceipt){
          popupReceipt.classList.remove("active");
        }
      }
    })
  }


  // Save Transaction
  function saveTransaction(event){
    event.preventDefault();

    var transForm = document.querySelector(".new_savings");
    
    var xhr = new XMLHttpRequest();
    xhr.onload = function(){
      if(this.status == 200){
        location.href = "savings.php";
      }
    }
    xhr.open("POST", "ajax/save_transaction.php", true);

    var formData = new FormData(transForm);
    xhr.send(formData);
  }
</script>

