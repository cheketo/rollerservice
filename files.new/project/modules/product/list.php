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
  echo $List->InsertSearchList();
  
  /* Footer */
  $Foot->SetScript('../../../core/resources/js/script.core.searchlist.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>