<?php
    include("../../../core/resources/includes/inc.core.php");
    
    $ID     = $_GET['id'];
    $Edit   = new ProviderOrder($ID);
    $View   = strtoupper($_GET['view']);
    $Data   = $Edit->GetData();
    Core::ValidateID($Data['order_id']);
    $Status = $Edit->Data['status'];
    if($Status!='A')
    {
        header('Location: list.php?status='.$Status.'&error=invoice');
	    die();
    }
    
    $Items        = $Data['items'];//Core::Select('provider_order_item a INNER JOIN product b ON (a.product_id = b.product_id)','b.code AS product,a.*,(a.price * a.quantity) AS total',"order_id=".$ID);
    //$ItemsHistory = Core::Select('provider_payment_item a INNER JOIN product b ON (a.product_id = b.product_id)','b.code AS product,a.*',"order_id=".$ID,'creation_date DESC');
    
    //echo $DB->LastQuery();
    
    $Currency = $Items[0]['currency'];
    $CurrencyID = $Items[0]['currency_id'];
    
    $Head->SetStyle('../../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetSubTitle($Data['provider']);
    $Head->setHead();
    include('../../../project/resources/includes/inc.top.php');
?>
<?php echo Core::InsertElement("hidden","action",'generateinvoice'); ?>
<?php echo Core::InsertElement("hidden","id",$ID); ?>
<?php echo Core::InsertElement("hidden","provider",$Data['provider_id']); ?>
<?php echo Core::InsertElement("hidden","currency",$CurrencyID); ?>
<?php echo Core::InsertElement("hidden","type",'N'); ?>
  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-xs-12">
        
          <div class="innerContainer main_form">
            <!--<form id="new_order">-->
            
            
            
            <h4 class="subTitleB"><i class="fa fa-file"></i> Datos de la Factura</h4>
            <div class="row">
              <div class="col-sm-4 col-xs-12" style="margin-top:1em;"><span>Proveedor:</span> <strong><?php echo $Data['provider'] ?></strong></div>
              <div class="col-sm-4 col-xs-12" style="margin-top:1em;"><span>Nro. Factura:</span> <?php echo Core::InsertElement('text','invoice_number','','txC inputMask','data-inputmask="\'mask\': \'99999999\'" autofocus validateEmpty="Ingrese un n&uacute;mero de factura"'); ?></div>
              <div class="col-sm-4 col-xs-12">
                <!--<div class="row">-->
                <!--  <div class="col-xs-12 col-sm-5"><span style="margin-top:7px;">Tipo de Factura:</span></div>-->
                <!--  <div class="col-xs-12 col-sm-7"><?php echo Core::InsertElement('select','invoice_type','','chosenSelect txC','validateEmpty="Seleccione un tipo de factura"',Core::Select('invoice_type','type_id,name',"type_id IN (1,3,25)","name"),' ',''); ?></div>-->
                <!--</div>-->
                
                  <span>Tipo de Factura:</span>
                  <?php echo Core::InsertElement('select','invoice_type','','chosenSelect txC','validateEmpty="Seleccione un tipo de factura"',Core::Select('invoice_type','type_id,name',"type_id IN (1,3,25)","name"),' ',''); ?>
              </div>
            </div>
            
            <h4 class="subTitleB"><i class="fa fa-cubes"></i> Art&iacute;culos de la Factura</h4>
            <div style="margin:0px 10px;">
              <div class="row form-group inline-form-custom bg-primary" style="margin-bottom:0px!important;padding:10px 0px!important;">
                
                <div class="col-xs-4 txC">
                  <strong>Art&iacute;culo</strong>
                </div>
                <div class="col-xs-2 txC">
                  <strong>Precio</strong>
                </div>
                <div class="col-xs-2 txC">
                  <strong>Cantidad</strong>
                </div>
                <!--<div class="col-xs-1 txC">-->
                <!--  <strong>Entrega</strong>-->
                <!--</div>-->
                <!--<div class="col-xs-2 txC">-->
                <!--  <strong>Recibido</strong>-->
                <!--</div>-->
                <div class="col-xs-2 txC">
                  <strong>Total</strong>
                </div>
                <div class="col-xs-2 txC">
                  <strong>Facturar</strong>
                </div>
              </div>
              <hr style="margin-top:0px!important;margin-bottom:0px!important;">
              <!--- ITEMS --->
              <div id="ItemWrapper">
                <?php 
                    $I = 1; 
                    $Total = 0;
                ?>
                <?php foreach($Items as $Item)
                      {
                        if($Item['payment_status']!='F' && $Item['quantity_paid']<$Item['quantity'])
                        {
                ?>
                <!--- NEW ITEM --->
                <?php 
                  if($Class=='bg-gray-light')
                    $Class='';
                  else
                    $Class='bg-gray-light';
                    
                    // $TotalItem = $Item['price']*$Item['quantity'];
                    $Quantity = $Item['quantity'] - $Item['quantity_paid'];
                    $TotalItem = $Quantity * $Item['price'];
                    $Total += $TotalItem;
                ?>
                    <div id="item_row_<?php echo $I ?>" item="<?php echo $I ?>" class="row form-group inline-form-custom ItemRow ItemsToPay <?php echo $Class ?>" style="margin-bottom:0px!important;padding:10px 0px!important;">
                          
                        <div class="col-xs-4 txC">
                            <span id="Item<?php echo $I ?>" class=" ItemText<?php echo $I ?>"><span class="label bg-navy"><?php echo $Item['code'] ?></span></span>
                        </div>
                        
                        <div class="col-xs-2 txC">
                            <span id="ItemPrice<?php echo $I ?>" class="ItemText<?php echo $I ?>"><?php echo $Currency?> <span id="Price<?php echo $I ?>"><?php echo $Item['price']; ?></span></span>
                        </div>
                          
                        <div class="col-xs-2 txC">
                            <!--<span id="Quantity<?php echo $I ?>" class="ItemText<?php echo $I ?>"><?php echo Core::InsertElement('text',$Item['quantity']); ?></span>-->
                            <?php echo Core::InsertElement('text','quantity_'.$I,$Quantity,'ItemField'.$I.' form-control calcable txC inputMask QuantityField','item="'.$I.'"data-inputmask="\'mask\': \'9{+}\'" placeholder="Cantidad" validateMaxValue="'.$Quantity.'///Ingrese un n&uacute;mero menor o igual a '.$Quantity.'" validateMinValue="1///Ingrese un n&uacute;mero mayor o igual a 1" validateEmpty="Ingrese una cantidad"'); ?>
                        </div>
                          
                        <!--<div class="col-xs-1 txC">-->
                        <!--    <span id="Date<?php echo $I ?>" class="ItemText<?php echo $I ?> OrderDate"><span class="label label-default"><?php echo $Date ?></span></span>-->
                        <!--</div>-->
                        
                        <!--<div class="col-xs-2 txC">-->
                        <!--    <span id="Received<?php echo $I ?>" class="ItemText<?php echo $I ?>"><?php echo $Received ?></span>-->
                        <!--</div>-->
                          
                        
                        <div class="col-xs-2 txC">
                          <!--<span class="label btnBlue"><?php echo $Item['currency'] ?><span id="total_payment<?php echo $I ?>"><?php echo $TotalItem; ?></span></span>-->
                          <span id="Total<?php echo $I ?>" class="ItemText<?php echo $I ?>"><span class="label label-success"><?php echo $Currency ?> <span id="TotalPrice<?php echo $I ?>"><?php echo $Total; ?></span></span></span>
                          <?php echo Core::InsertElement('hidden','id'.$I); ?>
                          <?php echo Core::InsertElement('hidden','item'.$I,$Item['item_id']); ?>
                          <?php echo Core::InsertElement('hidden','description'.$I,$Item['code']); ?>
                          <?php echo Core::InsertElement('hidden','price'.$I,$Item['price']); ?>
                          <?php echo Core::InsertElement('hidden','product'.$I,$Item['product_id']); ?>
                          <?php echo Core::InsertElement('hidden','total'.$I,$TotalItem); ?>
                          <?php echo Core::InsertElement('hidden','total_quantity'.$I,$Item['quantity']); ?>
                          <?php echo Core::InsertElement('hidden','quantity_paid'.$I,$Item['quantity_paid']); ?>
                        </div>
                        <div class="col-xs-2 txC">
                          <?php $MustBeChecked = $I==1? 'mustBeChecked="1///Seleccione al menos un art&iacute;culo"' : ''; ?>
                          <input type="checkbox" id="<?php echo $Item['item_id']; ?>" item="<?php echo $I ?>" value="<?php echo $Item['item_id']; ?>" class="iCheckbox" name="received[]" <?php echo $MustBeChecked; ?> />
                        </div>
                </div>
                <!--- NEW ITEM --->
                <?php $I++;}} $I--;?>
                <?php echo Core::InsertElement('hidden','total_items',$I); ?>
                <div class="row bg-primary" style="padding:10px 0px!important;">
                    <!--<div class="col-xs-5 text-right"><strong>Total Entregado: <?php echo $Items[0]['currency'].' '.$TotalDelivered; ?></strong></div>-->
                    <div class="col-xs-8 text-right"><strong>Sub-total:</strong></div>
                    <div class="col-xs-2 txC">
                    <span class="label bg-green"><span id="total_currency"><?php echo $Currency; ?> </span><span id="total_payment">0.00<?php //echo $Total; ?></span></span>
                    <?php echo Core::InsertElement('hidden','total',"0"); ?>
                    </div>
                    <div class="col-xs-2"></div>
                </div>
              </div>
            </div>
            
            <br>
            
            
            <!--- HISTORIAL --->
            <?php if(count($ItemsHistory) && 1==2){ ?>
              <h4 class="subTitleB"><i class="fa fa-hourglass"></i> Art&iacute;culos facturados a <?php echo $Data['provider'] ?></h4>
              <div style="margin:0px 10px;">
              <div class="row form-group inline-form-custom bg-gray" style="margin-bottom:0px!important;">
                
                <div class="col-xs-5 txC">
                  <strong>Art&iacute;culo</strong>
                </div>
                <div class="col-xs-1 txC">
                  <strong>Monto</strong>
                </div>
                <div class="col-xs-3 txC">
                  <strong>Fecha del pago</strong>
                </div>
                
                <div class="col-xs-3 txC">
                  <strong>Estado</strong>
                </div>
              </div>
              <hr style="margin-top:0px!important;margin-bottom:0px!important;">
              <!--- ITEMS --->
              <div id="ItemWrapper">
                <?php $I = 1; ?>
                <?php foreach($ItemsHistory as $Item)
                      {
                        // if($Item['status']!='P')
                        // {
                ?>
                <!--- OLD ITEM --->
                <?php 
                  $Date = explode(" ",$Item['creation_date']); 
                  $Date = implode("/",array_reverse(explode("-",$Date[0])))." ".$Date[1]; 
                  if($Class=='bg-gray-light')
                    $Class='';
                  else
                    $Class='bg-gray-light';
                ?>
                    <div id="item_row_<?php echo $I ?>" item="<?php echo $I ?>" class="row form-group inline-form-custom ItemRow <?php echo $Class ?>" style="margin-bottom:0px!important;padding:10px 0px!important;">
                          
                        <div class="col-xs-5 txC">
                            <span class="label label-default"><?php echo $Item['product'] ?></span>
                        </div>
                          
                        <div class="col-xs-1 txC">
                            <span class="label label-default"><?php echo $Currency.' '.$Item['amount'] ?></span>
                        </div>
                          
                        <div class="col-xs-3 txC">
                            <span class="OrderDate"><span class="label label-default"><?php echo $Date ?></span></span>
                        </div>
                          
                        <div class="col-xs-3 txC">
                          <span class="label label-success">Pagado</span>
              			    </div>
                </div>
                <!--- OLD ITEM --->
                <?php }?>
                
                <!--- ITEMS TO BE PAID  ---->
                <?php
                  if($View=='F')
                  {
                    foreach($Items as $Item)
                    {
                      if($Item['payment_status']!='F')
                      {
                        if($Class=='bg-gray-light')
                          $Class='';
                        else
                          $Class='bg-gray-light';
                          
                        
                        $Item['amount'] = $Item['price'] * $Item['quantity'];
                ?>
                <div id="item_row_<?php echo $I ?>" item="<?php echo $I ?>" class="row form-group inline-form-custom ItemRow <?php echo $Class ?>" style="margin-bottom:0px!important;padding:10px 0px!important;">
                          
                        <div class="col-xs-5 txC">
                            <span class="label label-default"><?php echo $Item['code'] ?></span>
                        </div>
                          
                        <div class="col-xs-1 txC">
                            <span class="label label-default"><?php echo $Currency.' '.$Item['amount'] ?></span>
                        </div>
                          
                        <div class="col-xs-3 txC">
                            <span class="OrderDate">-</span>
                        </div>
                          
                        <div class="col-xs-3 txC">
                          <span class="label label-danger">Pendiente</span>
              			    </div>
                </div>
                <?php
                      }
                    }
                  }
                ?>
                <!--- /ITEMS TO BE PAID  ---->
              </div>
            </div>
            <?php } ?>
          <hr>
          <div class="row txC">
            <button type="button" class="btn btn-success btnGreen" id="BtnAdd"><i class="fa fa-file-text"></i> Generar Factura</button>
            <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
          </div>
          <!--</form>-->
        </div>
        </div> <!-- container -->
      </div>
    </div><!-- box -->
  </div><!-- box -->
<?php
    $Foot->SetScript('../../../../vendors/datepicker/bootstrap-datepicker.js');
    $Foot->SetScript('../../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
    include('../../../project/resources/includes/inc.bottom.php');
?>