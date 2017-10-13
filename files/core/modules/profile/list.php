<?php
  include('../../../core/resources/includes/inc.core.php');
  $Profile = new CoreProfile();
  $Head->SetTitle("Perfiles");
  $Head->SetIcon($Menu->GetHTMLicon());
  $Head->SetSubTitle("Listado de Perfiles");
  $Head->setHead();

  /* Header */
  include('../../../project/resources/includes/inc.top.php');
  
  /* Body Content */ 
  // Search List Box
  $Profile->ConfigureSearchRequest();
  echo $Profile->InsertSearchList();
  // Help Modal
  //include('modal.help.php');
  
  /* Footer */
  $Foot->SetScript('../../../core/resources/js/script.core.searchlist.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>