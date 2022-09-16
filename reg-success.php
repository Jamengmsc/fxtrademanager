<?php
   $caption = "Registration Successful";
   include "partials/header.php";
?>

<section class="reg-success mt-4">
  <div class="container-fluid px-md-5">
    <h4 class="text-dark alert alert-success text-center">Your registration was successful!</h4>

    <p class="text-dark" style="font-weight:600">An email containing your User Account ID and Password details has been sent to you. <span style="font-style: italic; font-size: 16px"><a class="text-warning" href="<?= SITEURL ?>login.php">Proceed to login</a></span></p>
  </div>
</section>

<script src="./script.js"></script>
</body>
</html>