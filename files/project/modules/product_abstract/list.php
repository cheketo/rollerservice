<?php
  include('../../../core/resources/includes/inc.core.php');
  $List = new ProductAbstract();
  $Head->SetStyle('../../../../vendors/autocomplete/jquery.auto-complete.css'); // Autocomplete
  
  $Head->SetTitle("Art&iacute;culos Gen&eacute;ricos");
  $Head->SetIcon($Menu->GetHTMLicon());
  $Head->SetSubTitle($Menu->GetTitle());
  $Head->setHead();

  /* Header */
  include('../../../project/resources/includes/inc.top.php');
  
  /* Body Content */
  echo $List->InsertSearchList();
  
  /* Footer */
  $Foot->SetScript('../../../../vendors/autocomplete/jquery.auto-complete.min.js');
  $Foot->SetScript('../../../core/resources/js/script.core.searchlist.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>