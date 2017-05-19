<?php
    include("../../includes/inc.main.php");
    
    $ID     = $_GET['id'];
    $Edit   = new ProviderOrder($ID);
    $Data   = $Edit->GetData();
    ValidateID($Data['order_id']);
    $Status = $Edit->Data['status'];
    $View   = strtolower($_GET['view']);
    if($Status!='A' || $Edit->Data['payment_status']=='F')
    {
      header('Location: list.php?error=status');
	    die();
    }
    
    $Items = $Edit->GetItems();
    $Currency = $Items[0]['currency'];
    //$ItemsHistory = $DB->fetchAssoc('stock_entrance a INNER JOIN product b ON (a.product_id = b.product_id)','b.code AS product,a.*',"order_id=".$ID,'delivery_date DESC');
    
    $Head->setStyle('../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    $Head->setTitle("Crear Factura de ".$Data['provider']);
    $Head->setSubTitle($Menu->GetTitle());
    $Head->setHead();
    include('../../includes/inc.top.php');
?>
<?php echo insertElement("hidden","action",'createinvoice'); ?>
<?php echo insertElement("hidden","id",$ID); ?>
<?php echo insertElement("hidden","provider",$Data['provider_id']); ?>
<?php echo insertElement("hidden","type",'N'); ?>

<div class="box animated fadeIn">
  <div class="box-header flex-justify-center">
    
    
    <!--////FIRST PART////-->
    <div class="innerContainer main_form" style="width:100%!important;padding:0px;" id="ItemSelection">
      <h4 class="subTitleB" style="margin-top:0px;"><i class="fa fa-cubes"></i> Seleccionar los art&iacute;culos de la factura</h4>
        <div style="margin:0px 10px;width:97%;padding-left:1%;" class="txC">
          <div class="row form-group inline-form-custom bg-light-blue" style="margin-bottom:0px!important;">  
            <div class="col-xs-4 txC">
              <strong>Art&iacute;culo</strong>
            </div>
            <div class="col-xs-2 txC">
              <strong>Cantidad</strong>
            </div>
            <div class="col-xs-2 txC">
              <strong>Precio</strong>
            </div>
            <div class="col-xs-2 txC">
              <strong>Total</strong>
            </div>
            <div class="col-xs-2 txC">
              <strong>Inclu&iacute;r</strong>
            </div>
          </div>
          <hr style="margin-top:0px!important;margin-bottom:0px!important;">
          <!--- ITEMS --->
          <div id="ItemWrapper">
            <!--- NEW ITEM --->
            <?php 
              $I = 1;
              foreach($Items as $Item)
              {
                if($Item['payment_status']!='F')
                {        
                  if($Class=='bg-gray-light')
                    $Class='';
                  else
                    $Class='bg-gray-light';
                  $Quantity = $Item['quantity'] - $Item['quantity_paid'];
                  $Total = $Quantity * $Item['price'];
            ?>
            <div id="item_row_<?php echo $I ?>" item="<?php echo $I ?>" class="row form-group inline-form-custom ItemRow <?php echo $Class ?>" style="margin-bottom:0px!important;padding:10px 0px!important;">            
              <div class="col-xs-4 txC">
                <span id="Item<?php echo $I ?>" class=" ItemText<?php echo $I ?>"><span class="label bg-navy"><?php echo $Item['code'] ?></span></span>
              </div>
              <div class="col-xs-2 txC">
                <span id="ItemPrice<?php echo $I ?>" class="ItemText<?php echo $I ?>"><?php echo $Currency?> <span id="Price<?php echo $I ?>"><?php echo $Item['price']; ?></span></span>
              </div>
              <div class="col-xs-2 txC">
                <span id="Quantity<?php echo $I ?>" class="ItemText<?php echo $I ?>"><?php echo insertElement('text','quantity'.$I,$Quantity,'form-control txC QuantityField','item="'.$I.'" validateMaxValue="'.$Quantity.'///Ingrese un n&uacute;mero menor o igual a '.$Quantity.'" validateMinValue="1///Ingrese un n&uacute;mero mayor o igual a 1" validateOnlyNumbers="No puede ingresar letras o simbolos"') ?></span>
              </div>
              <div class="col-xs-2 txC">
                <span id="Total<?php echo $I ?>" class="ItemText<?php echo $I ?>"><span class="label label-success"><?php echo $Currency ?> <span id="TotalPrice<?php echo $I ?>"><?php echo $Total; ?></span></span></span>
              </div>
              <div class="col-xs-2 txC">
                <input type="checkbox" id="<?php echo $Item['item_id']; ?>" item="<?php echo $I ?>" value="<?php echo $Item['item_id']; ?>" class="iCheckbox" name="received[]" mustBeChecked="1///Seleccione al menos un art&iacute;culo" />
                <?php echo insertElement('hidden','id'.$I); ?>
                <?php echo insertElement('hidden','product'.$I,$Item['product_id']); ?>
                <?php echo insertElement('hidden','total_quantity'.$I,$Item['quantity']); ?>
                <?php echo insertElement('hidden','received_quantity'.$I,$Item['quantity_paid']); ?>
              </div>
            </div>
            <!--- NEW ITEM --->
            <?php
                  $I++;
                }
              } 
              $I--;
            ?>
            <?php echo insertElement('hidden','total_items',$I); ?>
          </div>
        </div>
        <hr>
        <div class="row txC">
          <button type="button" class="btn btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
          <button type="button" class="btn btnBlue" id="BtnContinue">Continuar <i class="fa fa-arrow-circle-right"></i></button>
        </div>
      </div>
      <!--////FIRST PART////-->
      
      <!--////SECOND PART////-->
      <div class="innerContainer main_form Hidden" id="FillData">
        
        <hr>
        <div class="row txC">
          <button type="button" class="btn btnGreen" id="BtnAdd"><i class="fa fa-file"></i> Crear Factura</button>
          <button type="button" class="btn btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
        </div>
      </div>
      <!--////SECOND PART////-->  
        
    
  </div><!-- box -->
</div><!-- box -->
<?php
    $Foot->setScript('../../../vendors/datepicker/bootstrap-datepicker.js');
    include('../../includes/inc.bottom.php');
?>