<?php
    include("../../includes/inc.main.php");
    
    $ID     = $_GET['id'];
    $Edit   = new ProviderOrder($ID);
    $Data   = $Edit->GetData();
    ValidateID($Data['order_id']);
    $Status = $Edit->Data['status'];
    if($Status=='F' || $Status=='Z')
    {
      header('Location: list.php?error=status');
			die();
    }
    $Agents     = $DB->fetchAssoc('provider_agent','agent_id,name','provider_id='.$Edit->Data['provider_id']);
    $Items      = $DB->fetchAssoc('provider_order_item a INNER JOIN product b ON (a.product_id = b.product_id)','b.code AS product,a.*,(a.price * a.quantity) AS total','order_id='.$ID);
    $ItemsPaid  = array();
    $TotalItems = 0;
    
    foreach($Items as $Key=>$Item)
    {
      if($Item['quantity']>=$Item['quantity_paid'] && $Item['quantity_paid']>0)
      {
        $TotalItems++;
        $PaidItem = $Item;
        $PaidItem['quantity'] = $Item['quantity_paid'];
        $Items[$Key]['quantity'] = $Item['quantity']-$Item['quantity_paid'];
        $Items[$Key]['payment_status'] = 'P';
        if($Item['quantity_received']>=$Item['quantity_paid'])
        {
          $PaidItem['quantity_received'] = $Item['quantity_paid'];
          $PaidItem['delivery_status'] = 'F';
          $Items[$Key]['quantity_received'] = $Item['quantity_received']-$Item['quantity_paid'];
          if($Items[$Key]['quantity_received']>0)
            $Items[$Key]['delivery_status'] = 'P';
          else
            $Items[$Key]['delivery_status'] = 'A';
        }else{
          $Items[$Key]['quantity_received'] = 0;
          $Items[$Key]['delivery_status'] = 'P';
        }
        $Items[$Key]['quantity_paid'] = 0;
        $Items[$Key]['total'] = $Items[$Key]['quantity'] * $Items[$Key]['price'];
        if($Items[$Key]['quantity']>0)
          $TotalItems++;
        
        $PaidItem['total'] = $PaidItem['quantity'] * $PaidItem['price'];
        $ItemsPaid[] = $PaidItem;
      }else{
        $TotalItems++;
      }
    }
    
    
    $Head->setTitle("Editar Orden de ".$Data['provider']);
    $Head->setSubTitle($Menu->GetTitle());
    $Head->setIcon($Menu->GetHTMLicon());
    $Head->setStyle('../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    $Head->setHead();
    include('../../includes/inc.top.php');
?>
<?php echo insertElement("hidden","action",'update'); ?>
<?php echo insertElement("hidden","id",$ID); ?>
<?php echo insertElement("hidden","type",'N'); ?>
<?php echo insertElement("hidden","items",$TotalItems); ?>

  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="innerContainer main_form">
          <!--<form id="new_order">-->
          
          <h4 class="subTitleB"><i class="fa fa-building"></i> Proveedor</h4>
          <div class="row form-group inline-form-custom">
            <div class="col-xs-12">
                <?php echo insertElement('select','provider',$Edit->Data['provider_id'],'form-control chosenSelect','validateEmpty="Seleccione un Proveedor" data-placeholder="Seleccione un Proveedor"',$DB->fetchAssoc('provider','provider_id,name',"status='A' AND company_id=".$_SESSION['company_id'],'name'),' ',''); ?>
            </div>
          </div>
          
          <h4 class="subTitleB"><i class="fa fa-male"></i> Contacto</h4>
          <div class="row form-group inline-form-custom">
            <div class="col-xs-12">
                
                  <?php if(empty($Agents))
                        {
                          echo '<div id="agent-wrapper">
                                  <select id="agent" class="form-control chosenSelect" disabled="disabled">
                                    <option value="0">Sin Contacto</option></select></div>';
                        }else{
                          echo '<div id="agent-wrapper">';
                          echo insertElement('select','agent',$Edit->Data['agent_id'],'form-control chosenSelect','',$Agents);  
                          echo '</div>';
                        }
                        
                   ?>
                </div>
            </div>
            
          <h4 class="subTitleB"><i class="fa fa-money"></i> Moneda</h4>
          <div class="row form-group inline-form-custom">
            <div class="col-xs-12">
              <?php echo insertElement('select','currency',$Edit->Data['currency_id'],'form-control chosenSelect','validateEmpty="Seleccione un Moneda" data-placeholder="Seleccione una Moneda"',$DB->fetchAssoc('currency','currency_id,title',"",'title DESC'),' ',''); ?>
            </div>
          </div>
          <br>
          
          <h4 class="subTitleB"><i class="fa fa-cubes"></i> Art&iacute;culos</h4>
          
          <div style="margin:0px 10px;">
            <div class="row form-group inline-form-custom bg-light-blue" style="margin-bottom:0px!important;">  
              <div class="col-xs-4 txC">
                <strong>Art&iacute;culo</strong>
              </div>
              <div class="col-xs-1 txC">
                <strong>Precio</strong>
              </div>
              <div class="col-xs-1 txC">
                <strong>Cantidad</strong>
              </div>
              <div class="col-xs-2 txC">
                <strong>Fecha Entrega</strong>
              </div>
              <div class="col-xs-1 txC"><strong>Costo</strong></div>
              <div class="col-xs-3 txC">
                <strong>Acciones</strong>
              </div>
            </div>
            
            <hr style="margin-top:0px!important;margin-bottom:0px!important;">
            <!--- ITEMS --->
            <div id="ItemWrapper">
              <?php $I = 1; ?>
              <?php foreach($ItemsPaid as $Item){?>
              <!--- NEW ITEM --->
              <?php 
                $Date = explode(" ",$Item['delivery_date']); 
                $Date = implode("/",array_reverse(explode("-",$Date[0]))); 
              ?>
              <div id="item_row_<?php echo $I ?>" item="<?php echo $I ?>" class="row form-group inline-form-custom ItemRow bg-gray" style="margin-bottom:0px!important;padding:10px 0px!important;">
                <?php echo insertElement('hidden','payment_status_'.$I,$Item['payment_status']); ?>
                <?php echo insertElement('hidden','delivery_status_'.$I,$Item['delivery_status']); ?>
                <?php echo insertElement('hidden','actual_delivery_date_'.$I,$Item['actual_delivery_date']); ?>
                <?php echo insertElement('hidden','creation_date_'.$I,$Item['creation_date']); ?>
                <?php echo insertElement('hidden','quantity_received_'.$I,$Item['quantity_received']); ?>
                <?php echo insertElement('hidden','quantity_paid_'.$I,$Item['quantity_paid']); ?>
                <div class="col-xs-4 txC">
                  <span id="Item<?php echo $I ?>" class="ItemText<?php echo $I ?>"><?php echo $Item['product'] ?></span>
                  <?php echo insertElement('hidden','item_'.$I,$Item['product_id']); ?>
                </div>
                <div class="col-xs-1 txC">
                  <span id="Price<?php echo $I ?>" class="ItemText<?php echo $I ?>">$ <?php echo $Item['price'] ?></span>
                  <?php echo insertElement('hidden','price_'.$I,$Item['price']); ?>
                </div>
                <div class="col-xs-1 txC">
                  <span id="Quantity<?php echo $I ?>" class="ItemText<?php echo $I ?>"><?php echo $Item['quantity'] ?></span>
                  <?php echo insertElement('hidden','quantity_'.$I,$Item['quantity']); ?>
                </div>
                <div class="col-xs-2 txC">
                  <span id="Date<?php echo $I ?>" class="ItemText<?php echo $I ?> OrderDate Restricted"><?php echo $Date ?></span>
                  <?php echo insertElement('hidden','date_'.$I,$Date,'Restricted'); ?>
                </div>
                <div id="item_number_<?php echo $I ?>" class="col-xs-1 txC item_number" total="<?php echo $Item['total']; ?>" item="<?php echo $I ?>">$ <?php echo $Item['total']; ?></div>
								<div class="col-xs-3 txC">
									  
								</div>
              </div>
              <!--- NEW ITEM --->
              <?php $I++;} ?>
              <?php foreach($Items as $Item){if($Item['quantity']>0){?>
              <!--- NEW ITEM --->
              <?php 
                $Date = explode(" ",$Item['delivery_date']); 
                $Date = implode("/",array_reverse(explode("-",$Date[0]))); 
                
                if($Item['quantity_received']>0)
                  $MinQty = $Item['quantity_received'];
                else
                  $MinQty = 0;
              ?>
              <div id="item_row_<?php echo $I ?>" item="<?php echo $I ?>" class="row form-group inline-form-custom ItemRow bg-gray" style="margin-bottom:0px!important;padding:10px 0px!important;">
                <form id="item_form_<?php echo $I ?>" name="item_form_<?php echo $I ?>">
                <div class="col-xs-4 txC">
                  <span id="Item<?php echo $I ?>" class="Hidden ItemText<?php echo $I ?>"><?php echo $Item['product'] ?></span>
                  <?php echo insertElement('select','item_'.$I,$Item['product_id'],'ItemField'.$I.'form-control chosenSelect itemSelect','validateEmpty="Seleccione un Art&iacute;culo" data-placeholder="Seleccione un Art&iacute;culo" item="'.$I.'"',$DB->fetchAssoc('product','product_id,code',"status='A'",'code'),' ',''); ?>
                </div>
                <div class="col-xs-1 txC">
                  <span id="Price<?php echo $I ?>" class="Hidden ItemText<?php echo $I ?>">$ <?php echo $Item['price'] ?></span>
                  <?php echo insertElement('text','price_'.$I,$Item['price'],'ItemField'.$I.' form-control txC calcable inputMask','data-inputmask="\'mask\': \'9{+}.99\'" placeholder="Precio" validateEmpty="Ingrese un precio"'); ?>
                </div>
                <div class="col-xs-1 txC">
                  <span id="Quantity<?php echo $I ?>" class="Hidden ItemText<?php echo $I ?>"><?php echo $Item['quantity'] ?></span>
                  <?php echo insertElement('text','quantity_'.$I,$Item['quantity'],'ItemField'.$I.' form-control txC calcable QuantityItem inputMask','validateMinValue="'.$MinQty.'///Ingrese un valor igual o mayor a '.$MinQty.'"  data-inputmask="\'mask\': \'9{+}\'" placeholder="Cantidad" validateEmpty="Ingrese una cantidad"'); ?>
                </div>
                <div class="col-xs-2 txC">
                  <span id="Date<?php echo $I ?>" class="Hidden ItemText<?php echo $I ?> OrderDate"><?php echo $Date ?></span>
                  <?php echo insertElement('text','date_'.$I,$Date,'ItemField'.$I.' form-control txC delivery_date','placeholder="Fecha de Entrega" validateEmpty="Ingrese una fecha"'); ?>
                </div>
                <div id="item_number_<?php echo $I ?>" class="col-xs-1 txC item_number" total="<?php echo $Item['total']; ?>" item="<?php echo $I ?>">$ <?php echo $Item['total']; ?></div>
                <div class="col-xs-3 txC">
									  <button type="button" id="SaveItem<?php echo $I ?>" class="btn btnGreen SaveItem" style="margin:0px;" item="<?php echo $I ?>"><i class="fa fa-check"></i></button>
									  <button type="button" id="EditItem<?php echo $I ?>" class="btn btnBlue EditItem Hidden" style="margin:0px;" item="<?php echo $I ?>"><i class="fa fa-pencil"></i></button>
									  <?php if($I!=1 && $MinQty==0){ ?>
									    <button type="button" id="DeleteItem<?php echo $I ?>" class="btn btnRed DeleteItem" style="margin:0px;" item="<?php echo $I ?>"><i class="fa fa-trash"></i></button>
									  <?php } ?>
								</div>
								</form>
              </div>
              <!--- NEW ITEM --->
              <?php $I++;}} ?>
            </div>
            <!--- TOTALS --->
            <hr style="margin-top:0px!important;">
            <div class="row form-group inline-form-custom bg-light-blue">
              <div class="col-xs-4 txC">
                Art&iacute;culos Totales: <strong id="TotalItems" >1</strong>
              </div>
              <div class="col-xs-3 txC">
                Cantidad Total: <strong id="TotalQuantity" >0</strong>
              </div>
              <div class="col-xs-3 txC">
                Costo Total: <strong  id="TotalPrice">$ 0.00</strong> <span class="text-danger">(Sin IVA)</span>
                <?php echo insertElement("hidden","total_price","0"); ?>
              </div>
            </div>
            <!--- TOTALS --->
          </div>
          
          <div class="row">
            <div class="col-sm-6 col-xs-12 txC">
              <button type="button" id="add_order_item" class="btn btn-warning"><i class="fa fa-plus"></i> <strong>Agregar Art&iacute;culo</strong></button>
            </div>
            <div class="col-sm-6 col-xs-12 txC">
              <div class="input-group">
              <div class="input-group-btn">
                <button type="button" id="ChangeDates" class="btn bg-teal" style="margin:0px;"><i class="fa fa-flash"></i></button>
              </div>
              <!-- /btn-group -->
              <?php echo insertElement('text','change_date','','form-control delivery_date',' placeholder="Modificar la fecha de todos los art&iacute;culos"'); ?>
            </div>
            </div>
          </div>
          
          <h4 class="subTitleB"><i class="fa fa-info-circle"></i> Informaci&oacute;n Extra</h4>
          <div class="row form-group inline-form-custom">
            <div class="col-xs-12">
              <?php echo insertElement('textarea','extra',$Edit->Data['extra'],'form-control',' placeholder="Ingrese otros datos..."'); ?>
            </div>
          </div>
        <hr>
        <div class="row txC">
          <?php $BtnText = $Status=='P'?'Cotizaci&oacute;n':'Orden'; ?>
          <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-plus"></i> Editar <?php echo $BtnText; ?></button>
          <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
        </div>
        <!--</form>-->
      </div>
    </div><!-- box -->
  </div><!-- box -->
<?php
$Foot->setScript('../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');

$Foot->setScript('../../../vendors/datepicker/bootstrap-datepicker.js');
include('../../includes/inc.bottom.php');
?>