<?php
  include('../../../core/resources/includes/inc.core.php');
  $Head->SetTitle($Menu->GetTitle());
  $Head->SetIcon($Menu->GetHTMLicon());
  $Head->setHead();
  include('../../../project/resources/includes/inc.top.php');
  print_r($_SESSION);
 ?>
    
   <div class="form-group heckbox icheck">
    <button id="alertDemoError" type="button" class="btn btnRed">Error</button>
    <button id="alertDemoSuccess" type="button" class="btn btnGreen">Success</button>
    <button id="alertDemoInfo" type="button" class="btn btnBlue">Info</button>
    <button id="alertDemoWarning" type="button" class="btn btn-warning">Warning</button>
   </div>

   <button id="activateLoader" type="button" onclick="toggleLoader()" class="btn btnBlue animated fadeIn">Activate Loader</button>
   <br><br>

<?php
  include('../../../project/resources/includes/inc.bottom.php');
?>
