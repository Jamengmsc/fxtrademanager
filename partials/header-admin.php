<?php
  include "../config/constants.php";
  include "../config/session.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
  <meta name="theme-color" content="#242424">

  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <!-- Project Icon -->
  <link rel="shortcut icon" href="../images/FXT.jpg" type="image/x-icon">

  <!-- font awesome icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href="../styles.css">
  <title><?php echo $caption; ?></title>

  <!-- My font awesome kit -->
  <script src="https://kit.fontawesome.come/d324110cbe.js" crossorigin="anonymous"></script>

  <script
  src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>

</head>
<body>

<!-- Get user details -->
<?php
  if(isset($_SESSION['id'])){
      $id = $_SESSION['id'];

      $query = mysqli_query($conn, "SELECT * FROM user_reg WHERE id=$id");
      if(mysqli_num_rows($query) == 1){
        $user = mysqli_fetch_assoc($query);

        $firstname = $user['firstname'];
        $lastname = $user['lastname'];
        $acctID = $user['account_id'];
        $user_email = $user['email'];
      }
  }
?>


<section class="header-wrap sticky-top" style="background-color: #242424; width:100%;">
  <div id="header" class="container-fluid px-md-5 py-2">
    <div class="d-flex <?php if($caption == "User Login" || $caption == "User Registration"){echo "justify-content-center";} else{echo "justify-content-between";} ?>  align-items-center">
      <div class="brand">
        <a href="<?= SITEURL ?>" class="text-decoration-none d-flex align-items-center">
        <img src="./images/fx_logo4.png" width="38px" height="38px">
          <h4 class="m-0"><span class="font-italic font-weight-bold text-secondary">Fx<span class="font-italic text-secondary d-none d-md-inline">Trade</span></span><span style="color: #FFB610;">Manager</span>
          </h4>
        </a>
      </div>

      <div class="menu">
        <div class="<?php if($caption == "User Login" || $caption == "User Registration"){echo "d-none";} ?>">
          <ul id="navbar" class="mt-md-0 mt-2">
            <i class="fa fa-ellipsis-vertical d-md-none d-inline p-2" style="font-size: 23px; color: rgba(255,255,255,0.9); cursor: pointer;"></i>
  
            <li class="d-none d-md-inline 
              <?php echo (basename($_SERVER["PHP_SELF"]) == "index.php")?"active":""; ?> 
              <?php echo (basename($_SERVER["PHP_SELF"]) == "deposit.php")?"active":""; ?> 
              <?php echo (basename($_SERVER["PHP_SELF"]) == "withdraw.php")?"active":""; ?> 
              <?php echo (basename($_SERVER["PHP_SELF"]) == "transfer.php")?"active":""; ?> 
              <?php echo (basename($_SERVER["PHP_SELF"]) == "acct_details.php")?"active":""; ?>"><a href="<?= SITEURL ?>">my accounts</a>
            </li>

            <li class="d-none d-md-inline <?php echo (basename($_SERVER["PHP_SELF"]) == "new-account.php")?"active":""; ?>"><a href="<?= SITEURL ?>new-account.php">Create</a></li>

            <li class="d-none d-md-inline <?php echo (basename($_SERVER["PHP_SELF"]) == "records.php")?"active":""; ?>"><a href="<?= SITEURL ?>records.php">Trades</a></li>

            <li class="d-none d-md-inline 
              <?= (basename($_SERVER["PHP_SELF"]) == "comp_plan.php")?"active":""; ?> 
              <?= (basename($_SERVER["PHP_SELF"]) == "comp_plan_item.php")?"active":""; ?> 
              <?= (basename($_SERVER["PHP_SELF"]) == "new_plan.php")?"active":""; ?> 
              <?= (basename($_SERVER["PHP_SELF"]) == "edit-plan.php")?"active":""; ?> 
              <?= (basename($_SERVER["PHP_SELF"]) == "savings.php")?"active":""; ?>
              <?= (basename($_SERVER["PHP_SELF"]) == "todo.php")?"active":""; ?>
              <?= (basename($_SERVER["PHP_SELF"]) == "budgets.php")?"active":""; ?>"><a href="<?= SITEURL ?>comp_plan.php">Plans</a>
            </li>

            <li class="d-none d-md-inline font-italic 
              <?php echo (basename($_SERVER["PHP_SELF"]) == "user-profile.php")?"active":""; ?> 
              <?php echo (basename($_SERVER["PHP_SELF"]) == "edit-profile.php")?"active":""; ?>"><a href="<?= SITEURL ?>user-profile.php">My Profile</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Display ToDo task of today's date -->
<?php
  if(isset($_SESSION['id'])){
    $today = date("Y-m-d");
    
    $task = mysqli_query($conn, "SELECT id, description FROM tasks WHERE task_date='$today' AND viewed=0 AND user_id=$id");
    if(mysqli_num_rows($task) > 0){
      $task_row = mysqli_fetch_array($task);
      ?>
        <section class="todo_now sticky-top">
          <div class="d-flex justify-content-start align-items-center">
            <i class="fa fa-check text-warning"></i>
            <p class="m-0 mx-3 d-inline"><?= $task_row['description'] ?></p>
          </div>
          <i class="fa fa-times text-light" onclick="viewTask(<?php echo $task_row['id'] ?>)"></i>
        </section>
      <?php
    }
  }
?>

<!-- Running message -->
<section class="run_msg sticky-top">
  <marquee behavior="" direction="left" class="bg-dark text-gray" style="font-size:10px; padding: 2px">Learn a skill that will pay you for the rest of  your life. <span class="text-warning font-italic">Pips Pay the Bills...</span>&nbsp; &nbsp; &nbsp; Trading currencies and other commodities is risky.</marquee>
</section>


<!-- Menu Items on smaller screen -->
<div class="menu-wrap">
  <div class="menu-list bg-dark">
    <div class="menu_head">
      <div class="d-flex justify-content-between align-items-center">
        <h6 class="m-0 text-dark" style="font-size:24px;">MENU</h6>
        <div class="close_menu"><i class="fa fa-times"></i></div>
      </div>

      <div class="signed_user">
        <div class="">
          <p>Signed in as: </p>
          <span><?= $firstname . " " . $lastname ?></span>
        </div>

        <a class="p-0 font-italic text-secondary" href="<?= SITEURL ?>logout.php" onclick="exitApp(event)">logout</a>
      </div>
    </div>

    <ul>
      <li><a href="<?= SITEURL ?>"><div class="icon_cont"><i class="fa fa-home text-gray"></i></div>My Account</a></li>
      <li><a href="<?= SITEURL ?>new-account.php"><div class="icon_cont"><i class="fa fa-plus text-gray"></i></div>Create Account</a></li>
      <li><a href="<?= SITEURL ?>deposit.php"><div class="icon_cont"><i class="fa fa-arrow-down text-gray"></i></div>Deposit</a></li>
      <li><a href="<?= SITEURL ?>withdraw.php"><div class="icon_cont"><i class="fa fa-arrow-up text-gray"></i></div>Withdraw</a></li>
      <li><a href="<?= SITEURL ?>transfer.php"><div class="icon_cont"><i class="fa fa-refresh text-gray"></i></div>Transfer Funds</a></li>
      <li><a href="<?= SITEURL ?>records.php"><div class="icon_cont"><i class="fa fa-book text-gray"></i></div>Records</a></li>
      <li><a href="<?= SITEURL ?>comp_plan.php"><div class="icon_cont"><i class="fab fa-microsoft text-gray"></i></div>Compensation Plans</a></li>
      <li><a href="<?= SITEURL ?>user-profile.php" class="text-warning"><div class="icon_cont"><i class="fa fa-user"></i></div>My Profile</a></li>
    </ul>
  </div>
</div>

  


<?php
  if($caption !== "User Login" || $caption !== "User Registration"){
      ?>
        <!-- Transactions -->
        <div class="trans_pop"></div>
        
        <div class="disp_trans border border-dark d-md-none">
            <div class="trans_item">
              <a href="<?= SITEURL ?>deposit.php" class="text-warning px-3 py-2 mt-2"><i class="fa fa-arrow-down mr-2"></i> Deposit</a>
              <a href="<?= SITEURL ?>withdraw.php" class="text-light px-3 py-2 font-italic"><i class="fa fa-arrow-up mr-2"></i> Withdraw</a>
              <a href="<?= SITEURL ?>transfer.php" class="text-secondary px-3 py-2 font-italic"><i class="fa fa-refresh mr-2"></i>Transfer Funds</a>
            </div>
        </div>
      <?php
  }
?>

<!-- VIEW ON LARGE SCREEN -->
<div class="footer p-2 border-top border-dark <?php if($caption == "User Login" || $caption == "User Registration"){echo "d-none";} else{echo "d-flex";} ?>">
    
  <!-- Deposit/withdraw -->
  <div class="dep_withdr ml-md-5 px-2">
    <a href="<?= SITEURL ?>deposit.php" class="text-warning font-italic border border-warning d-none d-md-inline">Deposit</a>

    <a href="<?= SITEURL ?>withdraw.php" class="text-secondary font-italic border border-secondary d-none d-md-inline">Withdraw</a>

    <a href="<?= SITEURL ?>transfer.php" class="text-info font-italic border border-info d-none d-md-inline ml-3">Internal Transfer</a>
  </div>

  <div class="signed align-self-center">
    <span class="font-italic mr-md-5 text-warning d-md-inline d-none" style="font-size:13px; color:lightgray">Signed in as: &nbsp;<a class="" href="<?php echo SITEURL ?>user-profile.php" style="font-style:normal; color:gray; text-decoration:none; font-size:15px"><?= $user['firstname'] . " " . $user['lastname']  ?></a></span>
  </div>
</div>

<!-- VIEW ON SMALL SCREEN -->
<div class="footer_menu border-top border-dark d-none d-md-none <?php if($caption == "User Login" || $caption == "User Registration"){echo "d-none";} else{echo "d-flex";} ?>">
  <a title="Home" 
    class="<?php echo (basename($_SERVER["PHP_SELF"]) == "index.php")?"active":""; ?> 
          <?php echo (basename($_SERVER["PHP_SELF"]) == "acct_details.php")?"active":""; ?>" 
    href="<?= SITEURL ?>"><i class="fab fa-windows"></i> <span>Account</span>
  </a>

  <a title="New Account" class="<?php echo (basename($_SERVER["PHP_SELF"]) == "new-account.php")?"active":""; ?>" href="<?= SITEURL ?>new-account.php"><i class="fa fa-plus"></i><span>Create</span></a>

  <a title="Trade Records" class="<?php echo (basename($_SERVER["PHP_SELF"]) == "records.php")?"active":""; ?>" href="<?= SITEURL ?>records.php"><i class="fa fa-book"></i><span>Trades</span></a>

  <a title="My Profile" 
    class="<?php echo (basename($_SERVER["PHP_SELF"]) == "user-profile.php")?"active":""; ?>
          <?php echo (basename($_SERVER["PHP_SELF"]) == "edit-profile.php")?"active":""; ?>" 
    href="<?= SITEURL ?>user-profile.php"><i class="fa fa-user-plus"></i><span>Profile</span>
  </a>

  <a title="Compensation Plan" 
    class="<?= (basename($_SERVER["PHP_SELF"]) == "comp_plan.php")?"active":""; ?> 
          <?= (basename($_SERVER["PHP_SELF"]) == "comp_plan_item.php")?"active":""; ?> 
          <?= (basename($_SERVER["PHP_SELF"]) == "new_plan.php")?"active":""; ?> 
          <?= (basename($_SERVER["PHP_SELF"]) == "edit-plan.php")?"active":""; ?>
          <?= (basename($_SERVER["PHP_SELF"]) == "savings.php")?"active":""; ?>
          <?= (basename($_SERVER["PHP_SELF"]) == "budgets.php")?"active":""; ?>" 
    href="<?= SITEURL ?>comp_plan.php"><i class="fab fa-microsoft"></i><span>Plans</span>
  </a>
</div> 


<!-- Exit Application -->
<div class="exit_popup">
  <div id="exit_app" class="p-3">
    <h5 class="font-weight-normal" style="color:#000">Exit Application</h5>
    <p class="mt-1" style="color:rgba(0,0,0,0.8); line-height:20px;">Are you sure you want to exit the application? <br><br> Press OK to exit now.</p>

    <div id="popup_btn">
      <button id="cancel_exit">Cancel</button>   
      <button id="ok_exit">OK</button>
    </div>
  </div>
</div>



<!-- Success message confirmation popup -->
<div class="affirm">
  <p class="text-light m-0"></p>
</div>
