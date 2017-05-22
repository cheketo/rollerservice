<?php
  include('../../includes/inc.main.php');
  
  $Status = $_GET['status'] ? $_GET['status'] : 'P';
  switch ($Status) {
    case 'P':
        $Title = "Pendientes";
      break;
    case 'A':
        $Title = "Activas";
      break;
    case 'F':
        $Title = "Finalizadas";
      break;
    case 'I':
        $Title = "Eliminadas";
      break;
  }
  $Company = new ProviderOrderBilling();
  $Head->setStyle('../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
  $Head->setTitle("Ordenes de Compra a Proveedores ".$Title);
  $Head->setIcon($Menu->GetHTMLicon());
  $Head->setSubTitle($Menu->GetTitle());
  $Head->setHead();

  /* Header */
  include('../../includes/inc.top.php');
  
  /* Body Content */ 
  // Search List Box
  $Company->ConfigureSearchRequest();
  echo $Company->InsertSearchList();
  // Help Modal
  //include('modal.help.php');
  
  /* Footer */
  $Foot->setScript('../../../vendors/datepicker/bootstrap-datepicker.js');
  $Foot->SetScript('../../js/script.searchlist.js');
  include('../../includes/inc.bottom.php');
?>