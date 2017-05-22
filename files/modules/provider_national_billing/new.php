<?php
    include("../../includes/inc.main.php");
    $New = new ProviderOrder();
    $Head->setTitle($Menu->GetTitle());
    $Head->setStyle('../../../vendors/select2/select2.min.css'); // Select Inputs With Tags
    $Head->setStyle('../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    $Head->setStyle('../../../skin/css/maps.css'); // Google Maps CSS
    $Head->setHead();
    include('../../includes/inc.top.php');
?>
<?php echo insertElement("hidden","action",'insert'); ?>
<?php echo insertElement("hidden","type",'N'); ?>
<?php echo insertElement("hidden","items","1"); ?>

  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="innerContainer main_form">
            <!--<form id="new_order">-->
            
            <h4 class="subTitleB"><i class="fa fa-building"></i> Proveedor</h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                  <?php echo insertElement('select','providers','','form-control select2 selectTags','',$DB->fetchAssoc('provider','provider_id,name',"status='A' AND company_id=".$_SESSION['company_id'],'name'),'','Seleccione un Proveedor'); ?>
                  <?php echo insertElement("text","provider",'','Hidden','validateEmpty="Seleccione un Proveedor"'); ?>
              </div>
            </div>
            <h4 class="subTitleB"><i class="fa fa-male"></i> Contacto</h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                  <div id="agent-wrapper"><?php echo insertElement('select','agents','','form-control select2 selectTags','','','0','Sin Contacto'); ?></div>
                  <?php echo insertElement("text","agent",'','Hidden','validateEmpty="Seleccione un Contacto"'); ?>
              </div>
            </div>
            
            <h4 class="subTitleB"><i class="fa fa-money"></i> Moneda</h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                <?php echo insertElement('select','currency_selector','','form-control',' ',$DB->fetchAssoc('currency','currency_id,title',"",'title DESC'),'','Seleccione una Moneda'); ?>
                <?php echo insertElement("text","currency",'','Hidden','validateEmpty="Seleccione un Moneda"'); ?>
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
                
                <!--- NEW ITEM --->
                <div id="item_row_1" item="1" class="row form-group inline-form-custom ItemRow bg-gray" style="margin-bottom:0px!important;padding:10px 0px!important;">
                  <form id="item_form_1" name="item_form_1">
                  <div class="col-xs-4 txC">
                    <span id="Item1" class="Hidden ItemText1"></span>
                    <?php echo insertElement('select','items_1','','ItemField1 form-control select2 selectTags itemSelect','item="1"',$DB->fetchAssoc('product','product_id,code',"status='A'",'code'),'','Seleccione un Art&iacute;culo'); ?>
                    <?php echo insertElement("text","item_1",'','Hidden','validateEmpty="Seleccione un Art&iacute;culo"'); ?>
                  </div>
                  <div class="col-xs-1 txC">
                    <span id="Price1" class="Hidden ItemText1"></span>
                    <?php echo insertElement('text','price_1','','ItemField1 form-control calcable','data-inputmask="\'mask\': \'9{+}.99\'" placeholder="Precio" validateEmpty="Ingrese un precio"'); ?>
                  </div>
                  <div class="col-xs-1 txC">
                    <span id="Quantity1" class="Hidden ItemText1"></span>
                    <?php echo insertElement('text','quantity_1','','ItemField1 form-control calcable QuantityItem','data-inputmask="\'mask\': \'9{+}\'" placeholder="Cantidad" validateEmpty="Ingrese una cantidad"'); ?>
                  </div>
                  <div class="col-xs-2 txC">
                    <span id="Date1" class="Hidden ItemText1 OrderDate"></span>
                    <?php echo insertElement('text','date_1','','ItemField1 form-control delivery_date','placeholder="Fecha de Entrega" validateEmpty="Ingrese una fecha"'); ?>
                  </div>
                  <div id="item_number_1" class="col-xs-1 txC item_number" total="0" item="1">$ 0.00</div>
                  <div class="col-xs-3 txC">
  									  <button type="button" id="SaveItem1" class="btn btnGreen SaveItem" style="margin:0px;" item="1"><i class="fa fa-check"></i></button>
  									  <button type="button" id="EditItem1" class="btn btnBlue EditItem Hidden" style="margin:0px;" item="1"><i class="fa fa-pencil"></i></button>
  									  <!--<button type="button" id="DeleteItem1" class="btn btnRed DeleteItem" style="margin:0px;" item="1"><i class="fa fa-trash"></i></button>-->
  								</div>
  								</form>
                </div>
                <!--- NEW ITEM --->
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
            
            <h4 class="subTitleB"><i class="fa fa-info-circle"></i> Informaci&oacute;n Extra</h4><div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                  <?php echo insertElement('textarea','extra','','form-control',' placeholder="Ingrese otros datos..."'); ?>
              </div>
          </div>
          <hr>
          <div class="row txC">
            <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-plus"></i> Crear Orden</button>
            <button type="button" class="btn btn-success btnBlue" id="BtnCreateNext"><i class="fa fa-plus"></i> Crear y Agregar Otra</button>
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