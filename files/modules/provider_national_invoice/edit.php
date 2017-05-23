<?php
    include("../../includes/inc.main.php");
    
    $ID     = $_GET['id'];
    $Edit   = new ProviderOrder($ID);
    $Data   = $Edit->GetData();
    ValidateID($Data['order_id']);
    $Status = $Edit->Data['status'];
    if($Status!='P')
    {
      header('Location: list.php?error=status');
			die();
    }
    $Agents = $DB->fetchAssoc('provider_agent','agent_id,name','provider_id='.$Edit->Data['provider_id']);
    $Items  = $DB->fetchAssoc('provider_order_item a INNER JOIN product b ON (a.product_id = b.product_id)','b.code AS product,a.*,(a.price * a.quantity) AS total','order_id='.$ID);
    
    
    
    $Head->setTitle("Editar Orden de ".$Data['provider']);
    $Head->setSubTitle($Menu->GetTitle());
    $Head->setTitle("Editar Orden de ".$Data['provider']);
    $Head->setSubTitle($Menu->GetTitle());
    $Head->setStyle('../../../vendors/select2/select2.min.css'); // Select Inputs With Tags
    $Head->setStyle('../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    $Head->setStyle('../../../skin/css/maps.css'); // Google Maps CSS
    $Head->setHead();
    include('../../includes/inc.top.php');
?>
<?php echo insertElement("hidden","action",'update'); ?>
<?php echo insertElement("hidden","id",$ID); ?>
<?php echo insertElement("hidden","type",'N'); ?>
<?php echo insertElement("hidden","items",count($Items)); ?>

  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="innerContainer main_form">
          <!--<form id="new_order">-->
          
          <h4 class="subTitleB"><i class="fa fa-building"></i> Proveedor</h4>
          <div class="row form-group inline-form-custom">
            <div class="col-xs-12">
                <?php echo insertElement('select','providers',$Edit->Data['provider_id'],'form-control select2 selectTags','',$DB->fetchAssoc('provider','provider_id,name',"status='A' AND company_id=".$_SESSION['company_id'],'name'),'','Seleccione un Proveedor'); ?>
                <?php echo insertElement("text","provider",$Edit->Data['provider_id'],'Hidden','validateEmpty="Seleccione un Proveedor"'); ?>
            </div>
          </div>
          
          <h4 class="subTitleB"><i class="fa fa-male"></i> Contacto</h4>
          <div class="row form-group inline-form-custom">
            <div class="col-xs-12">
                
                  <?php if(empty($Agents))
                        {
                          echo '<div id="agent-wrapper">
                                  <select id="agents" class="form-control select2 selectTags" disabled="disabled" style="width: 100%;">
                                    <option value="0">Sin Contacto</option></select></div>';
                        }else{
                          echo '<div id="agent-wrapper">';
                          echo insertElement('select','agents',$Edit->Data['agent_id'],'form-control select2 selectTags','',$Agents);  
                          echo '</div>';
                        }
                        echo insertElement("text","agent",$Edit->Data['agent_id'],'Hidden');
                   ?>
                </div>
            </div>
            
          <h4 class="subTitleB"><i class="fa fa-money"></i> Moneda</h4>
          <div class="row form-group inline-form-custom">
            <div class="col-xs-12">
              <?php echo insertElement('select','currency_selector',$Edit->Data['currency_id'],'form-control',' ',$DB->fetchAssoc('currency','currency_id,title',"",'title DESC'),'','Seleccione una Moneda'); ?>
              <?php echo insertElement("text","currency",$Edit->Data['currency_id'],'Hidden','validateEmpty="Seleccione un Moneda"'); ?>
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
              <?php foreach($Items as $Item){?>
              <!--- NEW ITEM --->
              <?php 
                $Date = explode(" ",$Item['delivery_date']); 
                $Date = implode("/",array_reverse(explode("-",$Date[0]))); 
              ?>
              <div id="item_row_<?php echo $I ?>" item="<?php echo $I ?>" class="row form-group inline-form-custom ItemRow bg-gray" style="margin-bottom:0px!important;padding:10px 0px!important;">
                <form id="item_form_<?php echo $I ?>" name="item_form_<?php echo $I ?>">
                <div class="col-xs-4 txC">
                  <span id="Item<?php echo $I ?>" class="Hidden ItemText<?php echo $I ?>"><?php echo $Item['product'] ?></span>
                  <?php echo insertElement('select','items_'.$I,$Item['product_id'],'ItemField'.$I.'form-control select2 selectTags itemSelect','item="'.$I.'"',$DB->fetchAssoc('product','product_id,code',"status='A'",'code'),'','Seleccione un Art&iacute;culo'); ?>
                  <?php echo insertElement("text","item_".$I,$Item['product_id'],'Hidden','validateEmpty="Seleccione un Art&iacute;culo"'); ?>
                </div>
                <div class="col-xs-1 txC">
                  <span id="Price<?php echo $I ?>" class="Hidden ItemText<?php echo $I ?>">$ <?php echo $Item['price'] ?></span>
                  <?php echo insertElement('text','price_'.$I,$Item['price'],'ItemField'.$I.' form-control calcable','data-inputmask="\'mask\': \'9{+}.99\'" placeholder="Precio" validateEmpty="Ingrese un precio"'); ?>
                </div>
                <div class="col-xs-1 txC">
                  <span id="Quantity<?php echo $I ?>" class="Hidden ItemText<?php echo $I ?>"><?php echo $Item['quantity'] ?></span>
                  <?php echo insertElement('text','quantity_'.$I,$Item['quantity'],'ItemField'.$I.' form-control calcable QuantityItem','data-inputmask="\'mask\': \'9{+}\'" placeholder="Cantidad" validateEmpty="Ingrese una cantidad"'); ?>
                </div>
                <div class="col-xs-2 txC">
                  <span id="Date<?php echo $I ?>" class="Hidden ItemText<?php echo $I ?> OrderDate"><?php echo $Date ?></span>
                  <?php echo insertElement('text','date_'.$I,$Date,'ItemField'.$I.' form-control delivery_date','placeholder="Fecha de Entrega" validateEmpty="Ingrese una fecha"'); ?>
                </div>
                <div id="item_number_<?php echo $I ?>" class="col-xs-1 txC item_number" total="<?php echo $Item['total']; ?>" item="<?php echo $I ?>">$ <?php echo $Item['total']; ?></div>
                <div class="col-xs-3 txC">
									  <button type="button" id="SaveItem<?php echo $I ?>" class="btn btnGreen SaveItem" style="margin:0px;" item="<?php echo $I ?>"><i class="fa fa-check"></i></button>
									  <button type="button" id="EditItem<?php echo $I ?>" class="btn btnBlue EditItem Hidden" style="margin:0px;" item="<?php echo $I ?>"><i class="fa fa-pencil"></i></button>
									  <?php if($I!=1){ ?>
									    <button type="button" id="DeleteItem<?php echo $I ?>" class="btn btnRed DeleteItem" style="margin:0px;" item="<?php echo $I ?>"><i class="fa fa-trash"></i></button>
									  <?php } ?>
								</div>
								</form>
              </div>
              <!--- NEW ITEM --->
              <?php $I++;} ?>
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
          <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-plus"></i> Editar Orden</button>
          <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
        </div>
        <!--</form>-->
      </div>
    </div><!-- box -->
  </div><!-- box -->
<?php
$Foot->setScript('../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
$Foot->setScript('../../../vendors/select2/select2.min.js');
$Foot->setScript('../../../vendors/datepicker/bootstrap-datepicker.js');
include('../../includes/inc.bottom.php');
?>