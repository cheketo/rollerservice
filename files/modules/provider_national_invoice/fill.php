<?php
    include("../../includes/inc.main.php");
    $ID         = $_GET['id'];
    $Invoice    = new Invoice($ID);
    $Data       = $Invoice->GetData();
    ValidateID($Data['invoice_id']);
    
    $Data['entity'] = $Invoice->GetEntity();
    $Data['items']  = $Invoice->GetItems();
    $Data['taxes']  = $Invoice->GetTaxes();
    
    $Head->setTitle($Menu->GetTitle());
    $Head->setStyle('../../../vendors/chosen-js/chosen.css'); // Chosen Select Input
    $Head->setStyle('../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    $Head->setHead();
    include('../../includes/inc.top.php');
    echo insertElement("hidden","action",'generateprovider');
?>
    <section class="invoice">
      <!-- title row -->
      <h2 class="page-header">
      <div class="row">
        <div class="col-sm-9 col-xs-12">
          
            <i class="fa fa-globe"></i> Roller Service S.A.
          
        </div>
        <div class="col-sm-3 col-xs-12">
          <small class="pull-right"><b>Tipo factura:</b><?php echo insertElement('select','type','','form-control chosenSelect','',$DB->fetchAssoc('invoice_type','type_id,name'),' ',''); ?><br></small>
        </div>
        <!-- /.col -->
      </div>
      </h2>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          De
          <address>
            <strong><?php echo $Data['entity_name'] ?></strong><br>
            <?php echo $Data['entity']['address'] ?><br>
            <?php echo $Data['entity']['province'].', CP '.$Data['entity']['postal_code'] ?><br>
            CUIT <b><?php echo CUITFormat($Data['entity']['cuit']) ?></b><br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Para
          <address>
            <strong>Roller Service S.A.</strong><br>
            Av. Caseros 3217
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
              <?php echo insertElement('text','prefix','0000','txC inputMask','style="width:100%!important;" data-inputmask="\'mask\': \'9999\'"'); ?>
            </div>
            <div class="col-xs-6" style="margin:0px;padding:0px;">
              <?php echo " - ".InvoiceNumber($Data['number']) ?>
            </div>
              <br>
          </div>
          <b>Order ID:</b> 4F3S8J<br>
          <b>Payment Due:</b> 2/22/2014<br>
          <b>Account:</b> 968-34567
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
        <div class="col-xs-6">
          <p class="lead">Payment Methods:</p>
          <img src="../../dist/img/credit/visa.png" alt="Visa">
          <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
          <img src="../../dist/img/credit/american-express.png" alt="American Express">
          <img src="../../dist/img/credit/paypal2.png" alt="Paypal">

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg
            dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
          </p>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <p class="lead">Amount Due 2/22/2014</p>

          <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td><?php echo $Data['currency'].$Data['subtotal'] ?></td>
              </tr>
              <tr>
                <th>Tax (9.3%)</th>
                <td>$10.34</td>
              </tr>
              <tr>
                <th>Shipping:</th>
                <td>$5.80</td>
              </tr>
              <tr>
                <th>Total:</th>
                <td>$265.24</td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Imprimir</a>
          <button type="button" class="btn btn-primary" style="margin-right: 5px;">
            <i class="fa fa-file-pdf-o"></i> Generar PDF
          </button>
          <button type="button" class="btn btn-success pull-right"><i class="fa fa-download"></i> Guardar
          </button>
        </div>
      </div>
    </section>
  
<?php
$Foot->setScript('../../../vendors/inputmask3/jquery.inputmask.bundle.min.js'); // Input Mask
$Foot->setScript('../../../vendors/chosen-js/chosen.jquery.js'); // Chosen Select Input
$Foot->setScript('../../../vendors/datepicker/bootstrap-datepicker.js'); // Date Picker Calendar
include('../../includes/inc.bottom.php');
?>