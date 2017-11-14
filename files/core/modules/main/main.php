<?php
  include('../../../core/resources/includes/inc.core.php');
  $Head->SetTitle($Menu->GetTitle());
  $Head->SetIcon($Menu->GetHTMLicon());
  $Head->SetHead();
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
   <button id="ShowWindow" type="button" class="btn btnBlue animated fadeIn">Show Window</button>
   
<div class="window Hidden">
    <div class="window-border"><h4><div class="pull-left">Title</div><div class="pull-right"><div class="window-close"><i class="fa fa-times"></i></div></div></h4></div>
    <div class="window-body">Body</div>
    <div class="window-border txC">
        <button type="button" class="btn btn-success btnGreen"><i class="fa fa-download"></i> Save</button>
        <button type="button" class="btn btn-success btnBlue"><i class="fa fa-dollar"></i> Save & Pay</button>
        <button type="button" class="btn btn-error btnRed"><i class="fa fa-times"></i> Cancel</button>
    </div>
</div>

<?php
    include('../../../project/resources/includes/inc.bottom.php');
?>
