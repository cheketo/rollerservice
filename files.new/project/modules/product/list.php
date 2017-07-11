<?php
  include('../../../core/resources/includes/inc.core.php');
  $List = new Product();
  $Head->SetTitle("Art&iacute;culos");
  $Head->SetIcon($Menu->GetHTMLicon());
  $Head->SetSubTitle($Menu->GetTitle());
  $Head->setHead();

  /* Header */
  include('../../../project/resources/includes/inc.top.php');
  
  /* Body Content */ 
  // Search List Box
  $List->ConfigureSearchRequest();
  echo $List->InsertSearchList();
  // Help Modal
  //include('modal.help.php');
  
  /* Footer */
  $Foot->SetScript('../../js/script.searchlist.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>