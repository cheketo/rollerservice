<?php
  include('../../includes/inc.main.php');
  $Menu   = new AdminMenu();
  $Head->setTitle($Menu->GetTitle());
  $Head->setIcon($Menu->GetHTMLicon());
  $Head->setHead();

  include('../../includes/inc.top.php');
 ?>

   <div class="form-group heckbox icheck">
    <button id="alertDemoError" type="button" class="btn btnRed">Error</button>
    <button id="alertDemoSuccess" type="button" class="btn btnGreen">Success</button>
    <button id="alertDemoInfo" type="button" class="btn btnBlue">Info</button>
    <button id="alertDemoWarning" type="button" class="btn btnYellow">Warning</button>
   </div>

   <button id="activateLoader" type="button" onclick="toggleLoader()" class="btn btnBlue animated fadeIn">Activate Loader</button>
   <br><br>

<?php
  include('../../includes/inc.bottom.php');
?>
