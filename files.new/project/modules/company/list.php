<?php
  include('../../../core/resources/includes/inc.core.php');
  $Company = new Company();
  $Head->SetTitle($Menu->GetTitle());
  $Head->SetIcon($Menu->GetHTMLicon());
  $Head->SetSubTitle("Listado de ".strtolower($Menu->GetTitle()));
  $Head->setHead();

  /* Header */
  include('../../../project/resources/includes/inc.top.php');
  
  /* Body Content */ 
  // Search List Box
  $Company->ConfigureSearchRequest();
  echo $Company->InsertSearchList();
  // Help Modal
  //include('modal.help.php');
  
  /* Footer */
  $Foot->SetScript('../../../core/resources/js/script.core.searchlist.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>