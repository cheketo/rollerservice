<?php
  include('../../includes/inc.main.php');
  $New = new Category();
  $Head->setTitle("L&iacute;neas");
  $Head->setIcon($Menu->GetHTMLicon());
  $Head->setSubTitle($Menu->GetTitle());
  $Head->setHead();

  /* Header */
  include('../../includes/inc.top.php');
  
  /* Body Content */ 
  // Search List Box
  $New->ConfigureSearchRequest();
  echo $New->InsertSearchList();
  // Help Modal
  //include('modal.help.php');
  
  /* Footer */
  $Foot->SetScript('../../js/script.searchlist.js');
  include('../../includes/inc.bottom.php');
?>