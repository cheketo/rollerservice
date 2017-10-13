<?php
  include('../../../core/resources/includes/inc.core.php');
  $Group = new CoreFoo();
  $Head->SetTitle("Grupos");
  $Head->SetIcon($Menu->GetHTMLicon());
  $Head->SetSubTitle("Listado de Grupos");
  $Head->setHead();

  /* Header */
  include('../../../project/resources/includes/inc.top.php');
  
  /* Body Content */ 
  // Search List Box
  $Group->ConfigureSearchRequest();
  echo $Group->InsertSearchList();
  // Help Modal
  //include('modal.help.php');
  
  /* Footer */
  $Foot->SetScript('../../../core/resources/js/script.core.searchlist.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>