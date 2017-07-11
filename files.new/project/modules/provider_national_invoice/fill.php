<?php
    include("../../../core/resources/includes/inc.core.php");
    $ID         = $_GET['id'];
    $Invoice    = new Invoice($ID);
    $Data       = $Invoice->GetData();
    Core::ValidateID($Data['invoice_id']);
    
    $Data['entity'] = $Invoice->GetEntity();
    $Data['items']  = $Invoice->GetItems();
    $Data['taxes']  = $Invoice->GetTaxes();
    
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetSubTitle('de Proveedor');
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    $Head->setHead();
    include('../../../project/resources/includes/inc.top.php');
    echo Core::InsertElement("hidden","action",'update');
    echo Core::InsertElement("hidden","id",$ID);
?>
    <section class="invoice">
      <!-- title row -->
      <h2 class="page-header">
      <div class="row">
        <div class="col-sm-9 col-xs-12">
          
            <i class="fa fa-globe"></i> <?php echo $Data['entity']['name'] ?>
            <!--Roller Service S.A.-->
          
        </div>
        <div class="col-sm-3 col-xs-12">
          <small><b>Tipo factura:</b><?php echo Core::InsertElement('select','type',$Data['type_id'],'chosenSelect','',Core::Select('invoice_type','type_id,name'),' ',''); ?></small>
        </div>
        <!-- /.col -->
      </div>
      </h2>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          De
          <address>
            <strong><?php echo $Data['entity']['name'] ?></strong><br>
            <?php echo $Data['entity']['address'] ?><br>
            <?php echo $Data['entity']['province'].', CP '.$Data['entity']['postal_code'] ?><br>
            CUIT <b><?php echo Core::CUITFormat($Data['entity']['cuit']) ?></b><br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Para
          <address>
            <strong>Roller Service S.A.</strong><br>
            Av. Caseros 3217<br>
            CABA, CP 1263<br>
            CUIT 33-64765677-9
            
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <div class="row">
            <div class="col-xs-3" style="margin-right:0px;padding-right:0px;">
              <b>N&uacute;mero:</b>
            </div>
            <div class="col-xs-2" style="margin:0px;padding-left:0px;padding-right:3px;">
              <?php echo Core::InsertElement('text','prefix','0000','txC inputMask','style="width:100%!important;" data-inputmask="\'mask\': \'9999\'" validateMinValue="1///Ingrese un n&uacute;mero mayor a 0."'); ?>
            </div>
            <div class="col-xs-6" style="margin:0px;padding:0px;">
              <?php echo " - ".Core::InvoiceNumber($Data['number']) ?>
            </div>
              <br>
          </div>
          <b>Fecha Emisión:</b> <?php echo Core::InsertElement('text','from_date','','txC datePicker'); ?><br>
          <b>Vencimiento:</b> a <?php echo Core::InsertElement('text','due',30,'txC','style="max-width:30px;"'); ?> d&iacute;as<br>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped txC">
            <thead>
            <tr>
              <th class="txC">Cantidad</th>
              <th class="txC">Descripci&oacute;n</th>
              <th class="txC">Precio</th>
              <th class="txC">Subtotal</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($Data['items'] as $Item){ ?>
            <tr>
              <td><?php echo $Item['quantity'] ?></td>
              <td><?php echo $Item['description'] ?></td>
              <td><?php echo $Data['currency'].$Item['price'] ?></td>
              <td><?php echo $Data['currency'].$Item['total'] ?></td>
            </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-sm-6 col-xs-12">
          <?php
            $TotalTax = 0;
            $TotalTaxPercentage = 0;
            if(count($Data['taxes'])>0)
            {
          ?>
          <p class="lead">Impuestos</p>
          <div class="table-responsive">
            <table class="table">
              <?php
                foreach($Data['taxes'] as $Tax)
                {
                  $TotalTax += $Tax['amount'];
                  $TotalTaxPercentage += $Tax['percentage'];
              ?>
              <tr>
                <th><?php echo $Tax['name']; ?></th>
                <td><?php echo $Data['currency'].$Tax['amount'].' ('.Core::DecimalNumber($Tax['percentage'], 3).'%)'; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <th>Total Impuestos:</th>
                <td><?php echo $Data['currency'].$TotalTax.' ('.$TotalTaxPercentage.'%)'; ?></td>
              </tr>
            </table>
          </div>
        <?php } ?>
        </div>
        <!-- /.col -->
        <div class="col-sm-6 col-xs-12">
          <p class="lead">Importe</p>
          <!--<p class="lead">Vencimiento <?php echo Core::InsertElement('text','due_date','','datePicker'); ?></p>-->

          <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td><?php echo $Data['currency'].$Data['subtotal'] ?></td>
              </tr>
              <tr>
                <th>Impuestos</th>
                <td><?php echo $Data['currency'].$TotalTax.' ('.$TotalTaxPercentage.'%)'; ?></td>
              </tr>
              <tr>
                <th>Total:</th>
                <th><?php echo $Data['currency'].$Data['total']; ?></th>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12 txC">
          <!--<a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Imprimir</a>-->
          <!--<button type="button" class="btn btn-primary" style="margin-right: 5px;">-->
          <!--  <i class="fa fa-file-pdf-o"></i> Generar PDF-->
          <!--</button>-->
          <button type="button" class="btn btn-primary"><i class="fa fa-download"></i> Terminar Carga
          </button>
        </div>
      </div>
    </section>
  
<?php
$Foot->SetScript('../../../../vendors/datepicker/bootstrap-datepicker.js'); // Date Picker Calendar
include('../../../project/resources/includes/inc.bottom.php');
?>