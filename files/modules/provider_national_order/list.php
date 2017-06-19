<?php
  include('../../includes/inc.main.php');
  
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
  $Head->setStyle('../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
  $Head->setTitle($Title);
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