<?php
  include('../../../core/resources/includes/inc.core.php');
  $Head->SetTitle("Menues");
  $Head->SetIcon($Menu->GetHTMLicon());
  $Head->SetSubTitle("Listado de Menues");
  $Head->setHead();

  /* Header */
  include('../../../project/resources/includes/inc.top.php');
  
  /* Body Content */ 
  // Search List Box
  $Menu->ConfigureSearchRequest();
  echo $Menu->InsertSearchList();
  // Help Modal
  include('modal.help.php');
  
  /* Footer */
  $Foot->SetScript('../../../core/resources/js/script.core.searchlist.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>