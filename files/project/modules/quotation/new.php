<?php
    include("../../../core/resources/includes/inc.core.php");
    
    $International = $_GET['international']? $_GET['international']:'N';
    $Customer = $_GET['customer']? $_GET['customer']:'N';
    $Provider = $_GET['provider']? $_GET['provider']:'N';
    $New = new Quotation();
    if($_GET['provider']=='Y')
    {
      $Field  = 'provider';
      $Role   = 'Proveedor';
      $Title  = ' de Proveedores';
      $TitleIcon   = 'shopping-cart';
      $CompanyType = 'sender';
    }elseif($_GET['customer']=='Y'){
      $Field  = 'customer';
      $Role   = 'Cliente';
      $Title  = ' a Clientes';
      $TitleIcon   = 'users';
      $CompanyType = 'receiver';
    }else{
      // Send it back if customer o provider is not obtained
      header('Location: list.php?customer='.$_GET['customer'].'&provider='.$_GET['provider'].'&international='.$_GET['international']);
    	die();
    }
    $TypeID = Core::Select("purchase_type","type_id","international='".$International."' AND customer='".$Customer."' AND provider='".$Provider."'")[0]['type_id'];
    
    $FieldInternational = $_GET['international']? "AND international='".$_GET['international']."' ":"";
    
    $ProductCodes = Product::GetFullCodes();
    
    $Head->SetTitle($Menu->GetTitle().$Title);
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    $Head->SetStyle('../../../../vendors/autocomplete/jquery.auto-complete.css'); // Autocomplete
    $Head->setHead();
    include('../../../project/resources/includes/inc.top.php');
?>
<?php echo Core::InsertElement("hidden","action",'insert'); ?>
<?php echo Core::InsertElement("hidden","type_id",$TypeID); ?>
<?php echo Core::InsertElement("hidden","items","1"); ?>
<?php echo Core::InsertElement("hidden","company_type",$CompanyType); ?>
<?php //echo Core::InsertElement("autocomplete","cocolo",'','form-control','iconauto="building"','Purchase','GetCompanies');?>
  

  <div class="box animated fadeIn" style="min-width:99%">
    <div class="box-header flex-justify-center">
      <div class="innerContainer main_form" style="min-width:100%">
            <!--<form id="new_quotation">-->
            
            <h4 class="subTitleB"><i class="fa fa-<?php echo $TitleIcon ?>"></i> <?php echo $Role; ?></h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                  <?php echo Core::InsertElement('select','company','','form-control chosenSelect','validateEmpty="Seleccione un '.$Role.'" data-placeholder="Seleccione un '.$Role.'"',Core::Select(Company::TABLE,Company::TABLE_ID.',name',$Field."= 'Y' ".$FieldInternational." AND status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],'name'),' ',''); ?>
              </div>
            </div>
            <h4 class="subTitleB"><i class="fa fa-male"></i> Contacto</h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                  <div id="agent-wrapper"><?php echo Core::InsertElement('select','agent','','form-control chosenSelect','validateEmpty="Seleccione un Contacto" disabled="disabled"','','0','Sin Contacto'); ?></div>
              </div>
            </div>
            
            <h4 class="subTitleB"><i class="fa fa-money"></i> Moneda</h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                <?php echo Core::InsertElement('select','currency','','form-control chosenSelect','validateEmpty="Seleccione una Moneda" data-placeholder="Seleccione una Moneda"',Core::Select('currency','currency_id,title',"",'title DESC'),' ',''); ?>
              </div>
            </div>
            <br>
            <h4 class="subTitleB"><i class="fa fa-cubes"></i> Art&iacute;culos</h4>
            
            <div style="margin:0px 10px; min-width:90%;">
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
                    <?php //echo Core::InsertElement('select','item_1','','ItemField1 form-control chosenSelect itemSelect','validateEmpty="Seleccione un Art&iacute;culo" data-placeholder="Seleccione un Art&iacute;culo" item="1"',$ProductCodes,' ',''); ?>
                    <?php echo Core::InsertElement("autocomplete","item_1",'','ItemField1 txC form-control itemSelect','validateEmpty="Seleccione un Art&iacute;culo" placeholder="Ingrese un c&oacute;digo" placeholderauto="C&oacute;digo no encontrado" item="1" iconauto="cog"','Product','SearchCodes');?>
                    <?php //echo Core::InsertElement("text","item_1",'','Hidden',''); ?>
                  </div>
                  <div class="col-xs-1 txC">
                    <span id="Price1" class="Hidden ItemText1"></span>
                    <?php echo Core::InsertElement('text','price_1','','ItemField1 form-control txC calcable inputMask','data-inputmask="\'mask\': \'9{+}.99\'" placeholder="Precio" validateEmpty="Ingrese un precio"'); ?>
                  </div>
                  <div class="col-xs-1 txC">
                    <span id="Quantity1" class="Hidden ItemText1"></span>
                    <?php echo Core::InsertElement('text','quantity_1','','ItemField1 form-control txC calcable QuantityItem inputMask','data-inputmask="\'mask\': \'9{+}\'" placeholder="Cantidad" validateEmpty="Ingrese una cantidad"'); ?>
                  </div>
                  <div class="col-xs-2 txC">
                    <span id="Date1" class="Hidden ItemText1 OrderDate"></span>
                    <?php echo Core::InsertElement('text','date_1','','ItemField1 form-control txC delivery_date','placeholder="Fecha de Entrega" validateEmpty="Ingrese una fecha"'); ?>
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
                  <?php echo Core::InsertElement("hidden","total_price","0"); ?>
                </div>
              </div>
              <!--- TOTALS --->
            </div>
            
            
            <div class="row">
              <div class="col-sm-6 col-xs-12 txC">
                <button type="button" id="add_quotation_item" class="btn btn-warning"><i class="fa fa-plus"></i> <strong>Agregar Art&iacute;culo</strong></button>
              </div>
              <div class="col-sm-6 col-xs-12 txC">
                <div class="input-group">
                <div class="input-group-btn">
                  <button type="button" id="ChangeDates" class="btn bg-teal" style="margin:0px;"><i class="fa fa-flash"></i></button>
                </div>
                <!-- /btn-group -->
                <?php echo Core::InsertElement('text','change_date','','form-control delivery_date',' placeholder="Modificar la fecha de todos los art&iacute;culos"'); ?>
              </div>
              </div>
            </div>
            
            <h4 class="subTitleB"><i class="fa fa-info-circle"></i> Informaci&oacute;n Extra</h4><div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                  <?php echo Core::InsertElement('textarea','extra','','form-control',' placeholder="Datos adicionales"'); ?>
              </div>
          </div>
          <hr>
          <div class="row txC">
            <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-plus"></i> Crear Cotizaci&oacute;n</button>
            <button type="button" class="btn btn-success btnBlue" id="BtnCreateNext"><i class="fa fa-plus"></i> Crear y Agregar Otra</button>
            <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
          </div>
          <!--</form>-->
        </div>
    </div><!-- box -->
  </div><!-- box -->
<?php
$Foot->SetScript('../../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
$Foot->SetScript('../../../../vendors/autocomplete/jquery.auto-complete.min.js');
$Foot->SetScript('../../../../vendors/datepicker/bootstrap-datepicker.js');
include('../../../project/resources/includes/inc.bottom.php');
?>