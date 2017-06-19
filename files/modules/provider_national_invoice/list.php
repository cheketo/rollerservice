<?php
  include('../../includes/inc.main.php');
  
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
  $Head->setStyle('../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
  $Head->setTitle("Facturas de Proveedores ".$Title);
  $Head->setIcon($Menu->GetHTMLicon());
  $Head->setSubTitle($Menu->GetTitle());
  $Head->setHead();

  /* Header */
  include('../../includes/inc.top.php');
  
  /* Body Content */ 
  // Search List Box
  $List->ConfigureSearchRequest();
  echo $List->InsertSearchList();
  // Help Modal
  //include('modal.help.php');
  
  /* Footer */
  $Foot->setScript('../../../vendors/datepicker/bootstrap-datepicker.js');
  $Foot->SetScript('../../js/script.searchlist.js');
  include('../../includes/inc.bottom.php');
?>