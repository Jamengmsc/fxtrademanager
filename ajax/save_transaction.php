<?php
  include "../config/constants.php";
  include "../config/session.php";

  $trans_type = mysqli_real_escape_string($conn, $_POST['trans_type']);
  $trans_desc = mysqli_real_escape_string($conn, $_POST['desc']);
  $amount = mysqli_real_escape_string($conn, $_POST['amount']);

  // Select and load image to database
  if(isset($_FILES['receipt']['name'])){

    $receipt_name = $_FILES['receipt']['name'];

    // check if image is selected
    if($receipt_name != ""){
      // Rename receipt name
      $ext = explode(".", $receipt_name);
      $extension = end($ext);
      $receipt_name = "Receipt" . rand(10,99) . "_" . date("Ymd") . "." . $extension; // Custom Receipt Name

      $src = $_FILES['receipt']['tmp_name'];
      $dest_path = "../images/receipts/" . $receipt_name;

      $upload_receipt = move_uploaded_file($src, $dest_path);

      if($upload_receipt==false){
        $_SESSION['receipt-image'] = "<div class='error'>Failed to Upload Receipt</div>";
        die();
      }
    }
  }
 else {
    $receipt_name = "";
 }


//  Save Transaction to DB
    // Check for empty transaction type and amount form values
    if(empty($trans_type) || empty($amount)){
      $_SESSION['trans-failed'] = "<div class='error'>Incomplete transaction details!</div>";
      die();
    }
    else{
      $saveTrans = "INSERT INTO savings SET
        user_id = $id,
        trans_type = '$trans_type',
        trans_desc = '$trans_desc', 
        amount = '$amount',
        receipt = '$receipt_name'
      ";

      $saveTrans_res = mysqli_query($conn, $saveTrans);

      if($saveTrans_res == true){
        $_SESSION['trans-success'] = "<div class='error'>Your transaction was successful</div>";
      }
    }
