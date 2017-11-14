<?php
  include('../../../core/resources/includes/inc.core.php');
  $List = new ProductRelation();
  $Head->SetTitle("Relaciones de Art&iacute;culos y Marcas");
  $Head->SetSubTitle($Menu->GetTitle());
  $Head->SetIcon($Menu->GetHTMLicon());
  $Head->setHead();

  /* Header */
  include('../../../project/resources/includes/inc.top.php');
  
  /* Body Content */
  echo $List->InsertSearchList();
  /* Footer */
  $Foot->SetScript('../../../core/resources/js/script.core.searchlist.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>