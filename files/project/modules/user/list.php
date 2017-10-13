<?php
  include('../../../core/resources/includes/inc.core.php');
  $Menu   = new CoreMenu();
  $Head->SetTitle("Usuarios");
  $Head->SetIcon($Menu->GetHTMLicon());
  $Head->SetSubTitle("Listado de Usuarios");
  $Head->setHead();

  /* Header */
  include('../../../project/resources/includes/inc.top.php');
  
  /* Body Content */ 
  // Search List Box
  $CoreUser->ConfigureSearchRequest();
  echo $CoreUser->InsertSearchList();
  // Help Modal
  include('modal.help.php');
  
  /* Footer */
  $Foot->SetScript('../../../core/resources/js/script.core.searchlist.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>