<?php
  include('../../../core/resources/includes/inc.core.php');
  
    $Company = new Stock();
    $Head->SetStyle('../../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    $Head->SetTitle("Ingresos Pendientes de Stock ");
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetSubTitle($Menu->GetTitle());
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
  $Foot->SetScript('../../../../vendors/datepicker/bootstrap-datepicker.js');
  $Foot->SetScript('../../../core/resources/js/script.core.searchlist.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>