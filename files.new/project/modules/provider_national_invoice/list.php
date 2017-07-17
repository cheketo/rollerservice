<?php
  include('../../../core/resources/includes/inc.core.php');
  
  $Status = $_GET['status'] ? $_GET['status'] : 'P';
  switch ($Status) {
    case 'P':
        $Title = "Pendientes";
      break;
    case 'A':
        $Title = "En Proceso";
      break;
    case 'F':
        $Title = "Finalizadas";
      break;
    case 'I':
        $Title = "Rechazadas";
      break;
  }
  $List = new Invoice();
  $Head->SetStyle('../../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
  $Head->SetTitle("Facturas de Proveedores ".$Title);
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
  $Foot->SetScript('../../../../vendors/datepicker/bootstrap-datepicker.js');
  $Foot->SetScript('../../../core/resources/js/script.core.searchlist.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>