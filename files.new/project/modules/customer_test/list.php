<?php
  include('../../../core/resources/includes/inc.core.php');
  $Menu   = new CoreMenu();
  $Head->SetTitle("Clientes");
  $Head->SetIcon($Menu->GetHTMLicon());
  $Head->SetSubTitle("Listado de Clientes");
  $Head->setHead();

  /* Header */
  include('../../../project/resources/includes/inc.top.php');

  /* Body Content */
  // Search List Box
  ?>
  <div class="box animated fadeIn">
    <div class="box-header">
      <!-- List -->
      <div class="row ListView ListElement animated fadeIn ">
        <div class="container-fluid">
        En proceso
        </div><!-- container-fluid -->
      </div>

      <!-- /List -->
    </div><!-- box -->
  </div><!-- box -->

<?php

  /* Footer */
  $Foot->SetScript('../../../core/resources/js/script.core.searchlist.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>
