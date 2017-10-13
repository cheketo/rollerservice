<?php
  include('../../../core/resources/includes/inc.core.php');
  
  $Status = $_GET['status'] ? $_GET['status'] : 'P';
  switch (strtoupper($Status)) {
    case 'P':
        $Title = "Cotizaciones";
    break;
    case 'A':
        $Title = "Ord. de Compra Encargadas a Prov.";
    break;
    case 'Z':
        $Title = "Archivo";
    break;
    case 'F':
        $Title = "Historial de Ordenes de Compra a Prov.";
    break;
    case 'I':
        $Title = "Eliminadas";
    break;
  }
  $Company = new ProviderOrder();
  $Head->SetStyle('../../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
  $Head->SetTitle($Title);
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