<?php
  include "../config/constants.php";

  if(isset($_GET['acct_type'])){
    $acct_type = $_GET['acct_type'];

    if($acct_type == "Live"){
      echo "<div class='text-warning mt-2 font-italic' style='font-size:12px'>To start trading Live Account type, deposit to your FxTrade Wallet and then transfer to your Live Account</div>";
    }
    else{
      echo '
        <label for="acct-bal">Opening Balance:</label>
        <input type="text" name="balance" class="w-100" placeholder="Current account balance...">
      ';
    }
  }

?>