<?php
    include("../../includes/inc.main.php");
    
    $ID     = $_GET['id'];
    $Edit   = new ProviderOrder($ID);
    $View   = strtoupper($_GET['view']);
    $Data   = $Edit->GetData();
    ValidateID($Data['order_id']);
    $Status = $Edit->Data['status'];
    if($Status=='P')
    {
        header('Location: list.php?status=A&error=status');
	    die();
    }
    
    $Items        = $Data['items'];//$DB->fetchAssoc('provider_order_item a INNER JOIN product b ON (a.product_id = b.product_id)','b.code AS product,a.*,(a.price * a.quantity) AS total',"order_id=".$ID);
    $ItemsHistory = $DB->fetchAssoc('provider_payment_item a INNER JOIN product b ON (a.product_id = b.product_id)','b.code AS product,a.*',"order_id=".$ID,'creation_date DESC');
    
    //echo $DB->lastQuery();
    
    $Currency = $Items[0]['currency'];
    
    $Head->setStyle('../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    $Head->setTitle("Pagar Compra a ".$Data['provider']);
    $Head->setSubTitle($Menu->GetTitle());
    $Head->setHead();
    include('../../includes/inc.top.php');
?>
  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-xs-12">
        
          <div class="innerContainer main_form">
            <!--<form id="new_order">-->
            <?php echo insertElement("hidden","action",'payorder'); ?>
            <?php echo insertElement("hidden","id",$ID); ?>
            <?php echo insertElement("hidden","provider",$Data['provider_id']); ?>
            <?php echo insertElement("hidden","type",'N'); ?>
            
            
            
            <?php if($View!='F'){ ?>
            <h4 class="subTitleB"><i class="fa fa-cubes"></i> Pagar art&iacute;culos a <?php echo $Data['provider'] ?></h4>
            <div style="margin:0px 10px;">
              <div class="row form-group inline-form-custom bg-primary" style="margin-bottom:0px!important;padding:10px 0px!important;">
                
                <div class="col-xs-4 txC">
                  <strong>Art&iacute;culo</strong>
                </div>
                <div class="col-xs-1 txC">
                  <strong>Precio</strong>
                </div>
                <div class="col-xs-1 txC">
                  <strong>Cantidad</strong>
                </div>
                <div class="col-xs-1 txC">
                  <strong>Entrega</strong>
                </div>
                <div class="col-xs-2 txC">
                  <strong>Recibido</strong>
                </div>
                <div class="col-xs-2 txC">
                  <strong>Total</strong>
                </div>
                <div class="col-xs-1 txC">
                  <strong>Pagar</strong>
                </div>
              </div>
              <hr style="margin-top:0px!important;margin-bottom:0px!important;">
              <!--- ITEMS --->
              <div id="ItemWrapper">
                <?php 
                    $I = 1; 
                    $Total = 0;
                    $TotalDelivered = 0;
                ?>
                <?php foreach($Items as $Item)
                      {
                        if($Item['payment_status']!='F')
                        {
                            $TotalDelivered += ($Item['quantity_received']*$Item['price']);
                            switch ($Item['status']) {
                                case 'F': 
                                    $Received = '<span class="label label-success">Si</span>';
                                break;
                                case 'A': 
                                    $Received = '<span class="label label-warning">En Proceso ('.$Item['quantity_received'].'/'.$Item['quantity'].')</span>'; 
                                break;
                                default: 
                                    $Received = '<span class="label label-danger">No</span>'; 
                                break;
                            }
                ?>
                <!--- NEW ITEM --->
                <?php 
                  $Date = explode(" ",$Item['delivery_date']); 
                  $Date = implode("/",array_reverse(explode("-",$Date[0]))); 
                  if($Class=='bg-gray-light')
                    $Class='';
                  else
                    $Class='bg-gray-light';
                    
                    $TotalItem = $Item['price']*$Item['quantity'];
                    
                    $Total += $TotalItem;
                ?>
                    <div id="item_row_<?php echo $I ?>" item="<?php echo $I ?>" class="row form-group inline-form-custom ItemRow ItemsToPay <?php echo $Class ?>" style="margin-bottom:0px!important;padding:10px 0px!important;">
                          
                        <div class="col-xs-4 txC">
                            <span id="Item<?php echo $I ?>" class=" ItemText<?php echo $I ?>"><span class="label bg-navy"><?php echo $Item['code'] ?></span></span>
                        </div>
                        
                        <div class="col-xs-1 txC">
                            <span id="Price<?php echo $I ?>" class="ItemText<?php echo $I ?>"><?php echo $Item['currency'].' '.$Item['price'] ?></span>
                        </div>
                          
                        <div class="col-xs-1 txC">
                            <span id="Quantity<?php echo $I ?>" class="ItemText<?php echo $I ?>"><?php echo $Item['quantity'] ?></span>
                        </div>
                          
                        <div class="col-xs-1 txC">
                            <span id="Date<?php echo $I ?>" class="ItemText<?php echo $I ?> OrderDate"><span class="label label-default"><?php echo $Date ?></span></span>
                        </div>
                        
                        <div class="col-xs-2 txC">
                            <span id="Received<?php echo $I ?>" class="ItemText<?php echo $I ?>"><?php echo $Received ?></span>
                        </div>
                          
                        
                        <div class="col-xs-2 txC">
                            <span class="label btnBlue">
                                <?php echo $Item['currency'] ?>
                                <span id="total_payment<?php echo $I ?>"><?php echo $TotalItem; ?></span>
                            </span>
                            <?php echo insertElement('hidden','paid'.$I,$Item['item_id']); ?>
                            <?php echo insertElement('hidden','product'.$I,$Item['product_id']); ?>
                            <?php echo insertElement('hidden','total_amount'.$I,$TotalItem); ?>
                            <?php echo insertElement('hidden','total_quantity'.$I,$Item['quantity']); ?>
                            <?php echo insertElement('hidden','received_quantity'.$I,$Item['quantity_received']); ?>
              			</div>
              			
              			<div class="col-xs-1 txC">
                            <input type="checkbox" id="<?php echo $Item['item_id']; ?>" item="<?php echo $I ?>" value="<?php echo $Item['item_id']; ?>" class="iCheckbox" name="received[]" mustBeChecked="1///Seleccione al menos un art&iacute;culo" checked />
                        </div>
                </div>
                <!--- NEW ITEM --->
                <?php $I++;}} $I--;?>
                <?php echo insertElement('hidden','total_items',$I); ?>
                <div class="row bg-primary" style="padding:10px 0px!important;">
                    <div class="col-xs-5 text-right"><strong>Total Entregado: <?php echo $Items[0]['currency'].' '.$TotalDelivered; ?></strong></div>
                    <div class="col-xs-4 text-right"><strong>Total a Pagar:</strong></div>
                    <div class="col-xs-2 txC">
                    <span class="label bg-green"><span id="total_currency"><?php echo $Currency; ?> </span><span id="total_payment"><?php echo $Total; ?></span></span>
                    </div>
                    <div class="col-xs-1"></div>
                </div>
              </div>
            </div>
            <?php } ?>
            
            <?php if($View!='F') echo '<br>';  ?>
            
            
            <!--- HISTORIAL --->
            <?php if(count($ItemsHistory) || $View=='F'){ ?>
            <?php if($View!='F'){  ?>
              <h4 class="subTitleB"><i class="fa fa-hourglass"></i> Art&iacute;culos pagados a <?php echo $Data['provider'] ?></h4>
            <?php }else{ ?>
              <h4 class="subTitleB"><i class="fa fa-hourglass"></i> Estado de pagos a <?php echo $Data['provider'] ?></h4>
            <?php } ?>
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
            
            
            
            <?php if($Edit->Data['extra'] && $View!='F'){ ?>
            <h4 class="subTitleB"><i class="fa fa-info-circle"></i> Informaci&oacute;n Extra</h4><div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                  <p><?php echo $Edit->Data['extra'] ?></p>
              </div>
            </div>
            <?php } ?>
          <hr>
          <div class="row txC">
          <?php if($View!='F') { ?>
            <button type="button" class="btn btn-success btnGreen" id="BtnAdd"><i class="fa fa-dollar"></i> Pagar</button>
            <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
          <?php }else{ ?>
            <button type="button" class="btn btn-primary" id="BtnCancel"><i class="fa fa-arrow-circle-left"></i> Regresar</button>
          <?php } ?>
          </div>
          <!--</form>-->
        </div>
        </div> <!-- container -->
      </div>
    </div><!-- box -->
  </div><!-- box -->
<?php
    $Foot->setScript('../../../vendors/datepicker/bootstrap-datepicker.js');
    include('../../includes/inc.bottom.php');
?>