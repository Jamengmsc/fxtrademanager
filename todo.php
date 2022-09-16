<?php
   $caption = "To Do";

   include "partials/header.php";
   include "config/check-login.php";
?>

<!-- Main section -->
<section class="container-fluid px-md-5">
  <div class="d-flex justify-content-between align-items-center">
    <div class="d-flex flex-column">
      <div class="home-icon d-flex justify-content-start align-items-center">
          <i class="fa fa-list" style="font-size: 18px; color: #e7781c;"></i>
          <span class="ml-2" style="font-size: 16px; color: #e7781c;">To Do</span>
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
  <?php // Confirmations
    if(isset($_SESSION['added-plan'])){
      echo "<div class='alert alert-success font-italic' style='font-size:14px'>"  . $_SESSION['added-plan'] . "</div>";
      unset($_SESSION['added-plan']);
    }

    if(isset($_SESSION['deleted-plan'])){
      echo "<div class='alert alert-danger font-italic' style='font-size:14px'>"  . $_SESSION['deleted-plan'] . "</div>";
      unset($_SESSION['deleted-plan']);
    }
  ?>

  <div class="container-fluid px-md-3">
    <h6 class="m-0 text-dark mt-2 text-uppercase font-weight-bold">To Do <span class="text-secondary font-italic">List</span></h6>
    <p class="mb-3 text-secondary" style="font-size:10px; color: #e7781c;">(List of events to do, places to go with reminders. Click on any plan item to view full details)</p>
  </div>

  <!-- PLAN TABLE OF DETAILS -->
  <div class="saving_table todo">
    <div class="container-fluid px-md-3 btn_container">
      <?php
        if(isset($_SESSION['todo-success'])){
          echo "<div class='text-success font-italic text-right mt-n2 mb-3' style='font-size:12px; font-weight:500'>"  . $_SESSION['todo-success'] . "</div>";
          unset($_SESSION['todo-success']);
        }

        if(isset($_SESSION['todo-failed'])){
          echo "<div class='text-danger font-italic text-right mt-n2 mb-3' style='font-size:12px; font-weight:500'>"  . $_SESSION['todo-failed'] . "</div>";
          unset($_SESSION['todo-failed']);
        }

        if(isset($_SESSION['budg-update-success'])){
          echo "<div class='text-success font-italic text-right mt-n2 mb-3' style='font-size:12px; font-weight:500'>"  . $_SESSION['budg-update-success'] . "</div>";
          unset($_SESSION['budg-update-success']);
        }

        if(isset($_SESSION['budg-update-failed'])){
          echo "<div class='text-danger font-italic text-right mt-n2 mb-3' style='font-size:12px; font-weight:500'>"  . $_SESSION['budg-update-failed'] . "</div>";
          unset($_SESSION['budg-update-failed']);
        }

        if(isset($_SESSION['del-budget-success'])){
          echo "<div class='text-success font-italic text-right mt-n2 mb-3' style='font-size:12px; font-weight:500'>"  . $_SESSION['del-budget-success'] . "</div>";
          unset($_SESSION['del-budget-success']);
        }

        if(isset($_SESSION['del-budget-failed'])){
          echo "<div class='text-danger font-italic text-right mt-n2 mb-3' style='font-size:12px; font-weight:500'>"  . $_SESSION['del-budget-failed'] . "</div>";
          unset($_SESSION['del-budget-failed']);
        }
      ?>

      <!-- BUTTONS -->
      <div class="trans">
        <h6 class="m-0 text-dark" style="font-weight:500">Task/Event Details</h6>

        <div class="div">
          <button type="button" id="edit_budget_item" onclick="editBudgetItem(event)">Edit</button>

          <button type="button" id="delete_budget_item" onclick="deleteBudgetItem(event)">Delete</button>

          <button type="button" onclick="newToDoItem(event)">New</button>
        </div>
      </div>
    </div>

    <div style="overflow-x:auto">
      <table class="table-sm">       
        <tr>
          <th class="px-2" style="padding:12px 0"><input type="checkbox" id="sel_all"></th>
          <th class="text-left">TASK</th>
          <th class=""><span class="font-weight-bold">DESCRIPTION</span></th>
          <th class="p-0 text-info font-weight-bold font-italic">SCHEDULE</th>
          <th class="pr-2 text-warning font-italic">DONE</th>
        </tr>

        <!-- Loop start -->
        <?php
          $this_month = date("m");
          $this_year = date("Y");

          $getask = mysqli_query($conn, "SELECT DISTINCT MONTH(task_date) AS month, YEAR(task_date) AS year FROM tasks WHERE user_id=$id GROUP BY month ORDER BY month DESC");

          if(mysqli_num_rows($getask) > 0){
            while($month_row = mysqli_fetch_assoc($getask)){
              $month = $month_row['month'];
              $the_month = date("F", mktime(0, 0, 0, $month, 10));
  
              $year = $month_row['year'];
  
              ?>
                <tr class="saving_month bg-light">
                  <td colspan="5" class="text-dark font-weight-bold text-uppercase py-2" style="color: #e7781c;">
                    <?php echo "todo <span class='font-italic' style='color: #e7781c;'>" . $the_month . " " . $year . "</span>" ?>
                  </td>
                </tr>
              <?php
  
  
              // TO DO ITEMS
              $getMonthTask = mysqli_query($conn, "SELECT * FROM tasks WHERE MONTH(task_date) = $month AND YEAR(task_date) = $year AND user_id=$id ORDER BY task_date ASC");
  
              if(mysqli_num_rows($getMonthTask) > 0){
                while($task_row = mysqli_fetch_assoc($getMonthTask)){
                  $item_id = $task_row['id'];
    
                  ?>
                    <tr>
                      <td class="pl-2">
                        <div class="text-center"><input type="checkbox" class="checked_items" name="sel_items[]" value="<?= $task_row['id'] ?>">
                        </div>
                      </td>
    
                      <!-- Budgeted Amount -->
                      <td class="text-dark text-left px-1">
                        <?= "<span style='font-weight:700;'>" . $task_row['title'] . "</span>" ?>
                      </td>
    
                      <!-- Actual expended amount -->
                      <td class="text-left px-2 todo_desc"><?= $task_row['description'] ?></td>
    
                      <!-- Difference betweeen budgeted and actual amount -->
                      <td class="text-secondary" style="color:gray;font-style:italic;font-weight:600;white-space: nowrap;">
                        <?php
                          $day = substr(date("l", strtotime($task_row['task_date'])), 0, 3); // Truncate to Sun, Mon, Tues etc
                          echo $day . ", " . date("M d, Y", strtotime($task_row['task_date']));
                        ?>
                      </td>
                      <td>
                        <?php
                          if($task_row['status'] == 1){
                            echo '<i class="fa fa-check text-success"></i>';
                          }
                          else{
                            echo '<i class="fa fa-check text-dark"></i>';
                          }
                        ?>
                      </td>
                    </tr>
                  <?php
                }
              }
              else{
                ?>
                  <tr><td colspan="5" class="text-center text-danger font-italic font-weight-bold">-- No Task record found --</td></tr>
                <?php
              }  
            }
          }
          else{
            ?>
              <tr><td colspan="5" class="text-center text-secondary font-italic font-weight-bold py-4">Click on the <span class="text-info" style="font-style:normal">New</span> button to begin to record new ToDo tasks...</td></tr>
            <?php
          }    
        ?>
        <!-- Loop ends -->

      </table>
    </div>
  </div>
</section>


<!-- New ToDo Item Form -->
<div class="popup_new_todo">
  <div id="new_todo" class="p-3">
    <div id="" class="bg-light p-1 pb-2 rounded-lg">
      <form method="post" class="new_todo" autocomplete="off">
        <div class="d-flex justify-content-between align-items-center">
          <h6 class="m-0 p-3 pb-2" style="font-size:18px;color:#e7781c;">New Task/Event</h6>

          <i class="fa fa-times mr-3" style="font-size:20px;color:#e7781c;cursor:pointer"></i>
        </div>

        <div class="col-12">
          <label for="title">Title:</label>
          <input type="text" name="title" class="w-100 mb-2" id="todo_title" placeholder="Task/Event...">
        </div>

        <div class="col-12">
          <label for="description">Description:</label>
          <input type="text" name="description" class="w-100 mb-2" id="task_desc" placeholder="Description...">
        </div>

        <div class="col-12">
          <label for="schedule">Schedule:</label>
          <input type="date" name="schedule" class="w-100 mb-2" id="task_schedule" placeholder="Schedule Date...">
        </div>

        <div class="row px-3 mt-1 mb-0">
          <div class="col-12 d-flex justify-content-end align-items-center mb-1">
            <input type="checkbox" name="done" class="m-0" id="done">
            <span class="text-dark font-italic ml-1" style="font-size:13px; font-weight:600">Mark as done</span>
          </div>
        </div>
      
        <div class="col-12 mt-2">
          <input type="submit" id="add_todo" name="save_todo" value="Save Task/Event" class="w-100 text-dark bg-warning border-0" style="font-weight:600" onclick="saveToDoItem(event)">
        </div>
      </form>
    </div>
   </div>
</div>


<!-- Edit Transaction Form -->
<div class="popup_edit_budget">
  <div id="edit_budget" class="p-3"></div>

  <?php
    if(isset($_POST['update_budget'])){
      $budget_id = mysqli_real_escape_string($conn, $_POST['budget_id']);
      $item_desc = mysqli_real_escape_string($conn, $_POST['item_desc']);
      $budget_amount = mysqli_real_escape_string($conn, $_POST['budg_amount']);
      $act_amount = mysqli_real_escape_string($conn, $_POST['act_amount']);

      if(isset($_POST['income_amount'])){
        $income = 1;
      }
      else{
        $income = 0;
      }


    //  Update Transaction to DB
    // Check for empty transaction type and amount form values
    if(empty($item_desc) || empty($budget_amount)){
      $_SESSION['budg-update-failed'] = "<div class='error'>Item description/bugeted amount has been cleared</div>";
      echo "<script>location.href='budgets.php'</script>";
      die();
    }
    else{
      if($act_amount == ""){
        $act_amount = 0;
      }
      else{
        $act_amount = $act_amount;
      }

      // Query DB to save budget item
      $updateBudget = "UPDATE budgets SET
        user_id = $id,
        description = '$item_desc', 
        budget_amount = '$budget_amount',
        actual_amount = '$act_amount',
        income = $income

        WHERE id=$budget_id
      ";

        $updateBudget_res = mysqli_query($conn, $updateBudget);

        if($updateBudget_res == true){
          $_SESSION['budg-update-success'] = "<div class='error'>Budget Item Updated Successful</div>";
          echo "<script>location.href='budgets.php'</script>";
        }
      }
    }
  ?>
</div>


<!-- Delete Transaction -->
<div class="popup_budget_del">
   <div id="budget_del" class="p-3">
      <h5 class="font-weight-normal" style="color:#000">Delete Budget Item</h5>
      <p class="mt-1" style="color:rgba(0,0,0,0.8); line-height:20px;">Are you sure you want to delete this item? You will lost the item permanently. Continue?</p>

      <div id="popup_btn">
         <button id="cancel_budg_del">No</button>
         <button id="ok_budg_del">Yes, Delete</button>
      </div>
   </div>
</div>


<script src="./script.js"></script>
</body>
</html>



<script>
  //  VIEW FULL TODO DESCRIPTION
  var todoDesc = document.querySelectorAll(".todo_desc");
    for(let i = 0; i < todoDesc.length; i++){
      todoDesc[i].addEventListener("click", function(){
        todoDesc[i].classList.toggle("active");
      })
    }

  // SCRIPT TO SELECT MULTIPLE CHECKBOXES TO DELETE OR GENERATE SALES INVOICE
  var selectAll = document.getElementById("sel_all");
   var checkboxes = document.getElementsByClassName("checked_items");
   var editSavingItem = document.querySelector("#edit_budget_item"),
       deleteSavingItem = document.querySelector("#delete_budget_item");

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

  // Open Edit Savings Dialog Box
  function editBudgetItem(event){
    event.preventDefault();

    // Open background and dialog box
    var editBudgetPopup = document.querySelector(".popup_edit_budget");
        editBudgetPopup.classList.add("active");

    var editBudget = document.getElementById("edit_budget");
        editBudget.classList.add("active");


    // Get ID of selected transaction itema and display edit form for the item
    var checkedItems = [];
    for(i = 0; i < checkboxes.length; i++){
      if(checkboxes[i].checked == true){
        checkedItems.push(checkboxes[i].value);
      }
    }
    
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "./ajax/edit_budget_item.php?sel_id=" + checkedItems, true);
    xhr.onload = function(){
      if(this.status === 200){
        document.getElementById("edit_budget").innerHTML = this.responseText;

        // Close Dialog Box to edit savings transaction
        document.querySelector(".edit_budget .fa-times").addEventListener("click", function(){
          editBudgetPopup.classList.remove("active");
          editBudget.classList.remove("active");
        })
      }
    }
    
    xhr.send();
  }

  // Delete transaction
  function deleteBudgetItem(event){
    event.preventDefault();

    // Open background and dialog box
    var delBudgetDialog = document.querySelector(".popup_budget_del");
        delBudgetDialog.classList.add("active");

    var delBudget = document.getElementById("budget_del");
        delBudget.classList.add("active");


    // Cancel Delete
    var cancelBudgDelete = document.getElementById("cancel_budg_del");
        cancelBudgDelete.onclick = () => {
        delBudgetDialog.classList.remove("active");
        delBudget.classList.remove("active");
      }


    // Confirm delete
    document.getElementById("ok_budg_del").addEventListener("click", function(){
      var checkedItems = [];

      for(i = 0; i < checkboxes.length; i++){
        if(checkboxes[i].checked == true){
            checkedItems.push(checkboxes[i].value);
        }
      }
      
      var xhr = new XMLHttpRequest();
        xhr.open("GET", "./ajax/delete_budget.php?sel_items=" + checkedItems, true);
        xhr.onload = function(){
          if(this.status === 200){
            location.href = "budgets.php";                    
          }
        }
        
        xhr.send();

        delBudgetDialog.classList.remove("active");
        delBudget.classList.remove("active");
    });
  }

  // Save Transaction
  function saveToDoItem(event){
    event.preventDefault();

    var transForm = document.querySelector(".new_todo");
    
    var xhr = new XMLHttpRequest();
    xhr.onload = function(){
      if(this.status == 200){
        location.href = "todo.php";
      }
    }
    xhr.open("POST", "ajax/save_todo_item.php", true);

    var formData = new FormData(transForm);
    xhr.send(formData);
  }

  // Open New Savings Dialog Box
  function newToDoItem(event){
    event.preventDefault();

    // Open background and dialog box
    var newToDoPopup = document.querySelector(".popup_new_todo");
        newToDoPopup.classList.add("active");

    var newToDo = document.getElementById("new_todo");
        newToDo.classList.add("active");

    // Close Dialog Box
    document.querySelector(".new_todo .fa-times").addEventListener("click", function(){
      newToDoPopup.classList.remove("active");
      newToDo.classList.remove("active");
    });
  }

</script>

