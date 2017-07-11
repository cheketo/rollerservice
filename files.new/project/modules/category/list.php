<?php
  include('../../../core/resources/includes/inc.core.php');
  $New = new Category();
  $Head->SetTitle("L&iacute;neas");
  $Head->SetIcon($Menu->GetHTMLicon());
  $Head->SetSubTitle($Menu->GetTitle());
  $Head->setHead();

  /* Header */
  include('../../../project/resources/includes/inc.top.php');
  
  /* Body Content */ 
  // Search List Box
  $New->ConfigureSearchRequest();
  echo $New->InsertSearchList();
  // Help Modal
  //include('modal.help.php');
  
  /* Footer */
  $Foot->SetScript('../../js/script.searchlist.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>