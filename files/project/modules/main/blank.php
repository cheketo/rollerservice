<?php
  include('../../../core/resources/includes/inc.core.php');
  $Menu   = new CoreMenu();
  $Head->SetTitle("Blank");
  $Head->SetSubTitle("Page");
  $Head->setHead();
  include('../../../project/resources/includes/inc.top.php');
 ?>

 <div class="box box-success">
   <div class="box-header with-border">
     <h3 class="box-title">Default Box Example</h3>
     <div class="box-tools pull-right">
       <!-- Buttons, labels, and many other things can be placed here! -->
     </div><!-- /.box-tools -->
   </div><!-- /.box-header -->
   <div class="box-body">
     The body of the box
   </div><!-- /.box-body -->
   <div class="box-footer">
     The footer of the box
   </div><!-- box-footer -->
 </div><!-- /.box -->

<?php
  include('../../../project/resources/includes/inc.bottom.php');
?>
