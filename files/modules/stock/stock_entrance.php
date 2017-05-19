<?php
    include("../../includes/inc.main.php");
    
    $ID     = $_GET['id'];
    $Edit   = new Stock($ID);
    $Data   = $Edit->GetData();
    ValidateID($Data['order_id']);
    $Status = $Edit->Data['status'];
    $View   = strtolower($_GET['view']);
    if($Status!='A' && $Status!='C'  && $Edit->Data['delivery_status']!='P' && $Edit->Data['delivery_status']!='A')
    {
      if($View=='order')
        header('Location: ../provider_national_order/list.php?error=status');
      else
        header('Location: stock_pending_entrance.php?error=status');
	    die();
    }
    
    $Items        = $DB->fetchAssoc('provider_order_item a INNER JOIN product b ON (a.product_id = b.product_id)','b.code AS product,a.*,(a.price * a.quantity) AS total',"order_id=".$ID);
    $ItemsHistory = $DB->fetchAssoc('stock_entrance a INNER JOIN product b ON (a.product_id = b.product_id)','b.code AS product,a.*',"order_id=".$ID,'creation_date DESC');
    
    $Head->setStyle('../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    $Head->setTitle("Ingresar Stock de ".$Data['provider']);
    $Head->setSubTitle($Menu->GetTitle());
    $Head->setHead();
    include('../../includes/inc.top.php');
?>
  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-xs-12">
        
          <div class="innerContainer main_form">
            <!--<form id="new_order">-->
            <?php echo insertElement("hidden","action",'addstock'); ?>
            <?php echo insertElement("hidden","id",$ID); ?>
            <?php echo insertElement("hidden","status",$Status); ?>
            <?php echo insertElement("hidden","provider",$Data['provider_id']); ?>
            <?php echo insertElement("hidden","type",'N'); ?>
            
            <h4 class="subTitleB"><i class="fa fa-cubes"></i> Art&iacute;culos a recibir de <?php echo $Data['provider'] ?></h4>
            
            <div style="margin:0px 10px;">
              <div class="row form-group inline-form-custom bg-red" style="margin-bottom:0px!important;">
                
                <div class="col-xs-5 txC">
                  <strong>Art&iacute;culo</strong>
                </div>
                <div class="col-xs-1 txC">
                  <strong>Cantidad</strong>
                </div>
                <div class="col-xs-3 txC">
                  <strong>Entrega Programada</strong>
                </div>
                
                <div class="col-xs-3 txC">
                  <strong>Recibido</strong>
                </div>
              </div>
              <hr style="margin-top:0px!important;margin-bottom:0px!important;">
              <!--- ITEMS --->
              <div id="ItemWrapper">
                <?php $I = 1; ?>
                <?php foreach($Items as $Item)
                      {
                        if($Item['status']!='F')
                        {
                ?>
                <!--- NEW ITEM --->
                <?php 
                  $Date = explode(" ",$Item['delivery_date']); 
                  $Date = implode("/",array_reverse(explode("-",$Date[0]))); 
                  if($Class=='bg-gray-light')
                    $Class='';
                  else
                    $Class='bg-gray-light';
                    
                  $Quantity= $Item['quantity'] - $Item['quantity_received'];
                ?>
                    <div id="item_row_<?php echo $I ?>" item="<?php echo $I ?>" class="row form-group inline-form-custom ItemRow <?php echo $Class ?>" style="margin-bottom:0px!important;padding:10px 0px!important;">
                          
                        <div class="col-xs-5 txC">
                            <span id="Item<?php echo $I ?>" class=" ItemText<?php echo $I ?>"><span class="label label-warning"><?php echo $Item['product'] ?></span></span>
                        </div>
                          
                        <div class="col-xs-1 txC">
                            <span id="Quantity<?php echo $I ?>" class="ItemText<?php echo $I ?>"><?php echo insertElement('text','quantity'.$I,$Quantity,'form-control txC','validateMaxValue="'.$Quantity.'///Ingrese un n&uacute;mero menor o igual a '.$Quantity.'" validateOnlyNumbers="No puede ingresar letras o simbolos"') ?></span>
                        </div>
                          
                        <div class="col-xs-3 txC">
                            <span id="Date<?php echo $I ?>" class="ItemText<?php echo $I ?> OrderDate"><span class="label label-primary"><?php echo $Date ?></span></span>
                        </div>
                          
                        <div class="col-xs-3 txC">
                            <input type="checkbox" id="<?php echo $Item['item_id']; ?>" item="<?php echo $I ?>" value="<?php echo $Item['item_id']; ?>" class="iCheckbox" name="received[]" mustBeChecked="1///Seleccione al menos un art&iacute;culo" />
                            <?php echo insertElement('hidden','received'.$I); ?>
                            <?php echo insertElement('hidden','product'.$I,$Item['product_id']); ?>
                            <?php echo insertElement('hidden','total_quantity'.$I,$Item['quantity']); ?>
                            <?php echo insertElement('hidden','received_quantity'.$I,$Item['quantity_received']); ?>
              			</div>
                </div>
                <!--- NEW ITEM --->
                <?php $I++;}} $I--;?>
                <?php echo insertElement('hidden','total_items',$I); ?>
              </div>
            </div>
            
            
            <br>
            
            <!--- HISTORIAL --->
            <?php if(count($ItemsHistory)){ ?>
              <h4 class="subTitleB"><i class="fa fa-hourglass"></i> Art&iacute;culos recibidos previamente de <?php echo $Data['provider'] ?></h4>
              <div style="margin:0px 10px;">
              <div class="row form-group inline-form-custom bg-gray" style="margin-bottom:0px!important;">
                
                <div class="col-xs-5 txC">
                  <strong>Art&iacute;culo</strong>
                </div>
                <div class="col-xs-1 txC">
                  <strong>Cantidad</strong>
                </div>
                <div class="col-xs-3 txC">
                  <strong>Fecha Entrega</strong>
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
                  $Date = explode(" ",$Item['delivery_date']); 
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
                            <span class="label label-default"><?php echo $Item['quantity'] ?></span>
                        </div>
                          
                        <div class="col-xs-3 txC">
                            <span class="OrderDate"><span class="label label-default"><?php echo $Date ?></span></span>
                        </div>
                          
                        <div class="col-xs-3 txC">
                          <?php
                            if($Item['movement_type']=='I')
                            {
                              $Status = 'Recibido';
                              $BG     = 'success';
                            }else{
                              $Status = 'Devuelto';
                              $BG     = 'danger';
                            }
                          ?>
                          <span class="label label-<?php echo $BG ?>"><?php echo $Status ?></span>
              			    </div>
                </div>
                <!--- OLD ITEM --->
                <?php }?>
              </div>
            </div>
            <?php } ?>
            
            
            
            <?php if($Edit->Data['extra']){ ?>
            <h4 class="subTitleB"><i class="fa fa-info-circle"></i> Informaci&oacute;n Extra</h4><div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                  <p><?php echo $Edit->Data['extra'] ?></p>
              </div>
            </div>
            <?php } ?>
            <br>
            <div class="btn btn-block btn-social btn-twitter" style="font-size:1.2em!important;">
                <i style=""><?php echo insertElement('checkbox','confrim_sign','1','iCheckbox','name="received" mustBeChecked="1///Acepte la responsabilidad del ingreso de stock"') ?></i> Recibí y verifiqué todos los artículos seleccionados
                <?php echo insertElement('hidden','confirmed'); ?>
              </div>
          <hr>
          <div class="row txC">
            <button type="button" class="btn btn-success btnGreen" id="BtnAdd"><i class="fa fa-plus"></i> Ingresar Stock Recibido</button>
            <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
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