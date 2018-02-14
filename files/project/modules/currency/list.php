<?php
  include('../../../core/resources/includes/inc.core.php');
  $List = new Currency();
  $Head->SetTitle($Menu->GetTitle());
  $Head->SetIcon($Menu->GetHTMLicon());
  $Head->SetSubTitle('Monedas');
  $Head->setHead();
  include('../../../project/resources/includes/inc.top.php');
  
  /* Body Content */
  echo $List->InsertSearchList();
  
  /* Footer */
  $Foot->SetScript('../../../core/resources/js/script.core.searchlist.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>