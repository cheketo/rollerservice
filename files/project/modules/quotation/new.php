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
      $RowTitleClass = 'brown';
    }elseif($_GET['customer']=='Y'){
      $Field  = 'customer';
      $Role   = 'Cliente';
      $Title  = ' a Clientes';
      $TitleIcon   = 'users';
      $CompanyType = 'receiver';
      $RowTitleClass = 'light-blue';
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
<?php echo Core::InsertElement("hidden","creation_date",date('Y-m-d')); ?>
<?php //echo Core::InsertElement("autocomplete","cocolo",'','form-control','iconauto="building"','Purchase','GetCompanies');?>

  <div class="window Hidden" id="window_traceability">
    <div class="window-border"><h4><div class="pull-left"><i class="fa fa-book"></i> Historial de Cotizaciones y Trazabilidad</div><div class="pull-right"><div id="WindowClose" class="BtnWindowClose text-red"><i class="fa fa-times"></i></div></div></h4></div>
    <div class="window-body">
      
      
      <div id="NewQuotationBox" class="box box-success txC">
        <div class="box-header">
          <h3 class="box-title QuotationBoxTitle cursor-pointer">Nueva Cotización de Proveedor</h3>

          <div class="box-tools">
            
            <button id="CollapseNewForm" type="button" class="btn btn-box-tool NewQuotationBoxToggle" data-widget="collapse"><i class="fa fa-plus"></i></button>
              
          </div>
        </div>
         
        <div class="box-body">
          <?php echo Core::InsertElement('hidden','new_quotation_dir'); ?>
          <?php echo Core::InsertElement('hidden','last_product',0); ?>
          <?php echo Core::InsertElement('hidden','product',0); ?>
          <?php echo Core::InsertElement('hidden','item',0); ?>
          <form id="tform">
            <div class="row">
              <div class="col-sm-6 col-xs-12">
                <?php echo Core::InsertElement("autocomplete","tprovider",'','txC form-control','validateEmpty="Ingrese un Proveedor" placeholder="Seleccione un Proveedor" placeholderauto="Proveedor no encontrado" item="1" iconauto="shopping-cart"','Company','SearchProviders');?>
              </div>
              <div class="col-sm-6 col-xs-12">
                <?php echo Core::InsertElement('select','tcurrency','','form-control chosenSelect','validateEmpty="Seleccione una Moneda" data-placeholder="Seleccione una Moneda"',Core::Select('currency','currency_id,title',"",'title DESC'),' ',''); ?>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-sm-6 col-xs-12">
                <?php echo Core::InsertElement('text','tprice','','form-control txC inputMask','data-inputmask="\'mask\': \'9{+}[.99]\'" placeholder="Precio" validateEmpty="Ingrese un precio"'); ?>
              </div>
              <div class="col-sm-6 col-xs-12">
                <?php echo Core::InsertElement('text','tquantity','',' form-control txC inputMask','data-inputmask="\'mask\': \'9{+}\'" placeholder="Cantidad" validateEmpty="Ingrese una cantidad"'); ?>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-sm-6 col-xs-12">
                <?php echo Core::InsertElement('text','tdate','','form-control txC delivery_date','placeholder="Fecha" validateEmpty="Ingrese una fecha"'); ?>
              </div>
              <div class="col-sm-6 col-xs-12">
                <?php echo Core::InsertElement('text','tday',"",'form-control txC inputMask','placeholder="D&iacute;as Entrega" data-inputmask="\'mask\': \'9{+}\'" validateEmpty="Ingrese una cantidad de d&iacute;as"'); ?>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-xs-12">
                <?php echo Core::InsertElement('file','tfile','','form-control','placeholder="Cargar Archivo"'); ?>
                <?php echo Core::InsertElement('hidden','filecount',0); ?>
              </div>
            </div>
            <div class="row txC" id="FileWrapper">
              <!--<div class="col-md-4 col-sm-6 col-xs-12 txC FileInfoDiv" style="margin-top:10px;" id="tfile_1" filename="Cotizaci&oacute;nRoller1" fileurl="../../../../skin/files/quotation/file.pdf">-->
              <!--  <span class="btn btn-danger DeleteFileFromWrapper" style="padding:0px 3px;"><i class="fa fa-times"></i></span>-->
              <!--  <img src="../../../../skin/images/body/icons/pdf.png" height="64" width="64"> <a href="../../../../skin/files/quotation/file.pdf" target="_blank">CotizaciónRoller1</a>-->
              <!--  <?php echo Core::InsertElement('hidden','fileid_1','20'); ?>-->
              <!--</div>-->
              
            </div>
            <br>
            <div class="row">
              <div class="col-xs-12">
                <?php echo Core::InsertElement('textarea','textra','','form-control',' placeholder="Datos adicionales"'); ?>
              </div>
            </div>
          </form>
          
        </div>
         
        <div class="box-footer clearfix">
          <div class="input-group input-group-sm txC">
            <div class="input-group-btn">
              <button type="button" class="btn btn-success btnGreen BtnSaveQuotation" id="BtnSaveQuotation"><i class="fa fa-check"></i> Guardar Cotizaci&oacute;n</button>
            </div>
          </div>
        </div>
      </div>
      
      <div id="ProvidersBox" class="box box-warning collapsed-box txC">
          <div class="box-header">
            <h3 class="box-title QuotationBoxTitle cursor-pointer">Cotizaciones de Proveedores</h3>
            <div class="box-tools pull-right">
              <div class="input-group input-group-sm" style="width: 150px;">
                <input name="table_search" class="form-control pull-right" placeholder="Buscar" type="text">
                <div class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  <button type="button" id="CollapseQuotations" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                </div>
              </div>
            </div>
          </div>
           
          <div class="box-body table-responsive no-padding">
            <table id="QuotationWrapper" class="table table-hover">
              <tbody><tr id="QuotationWrapperTh" name="QuotationWrapperTh">
                <th class="txC">Fecha</th>
                <th class="txC">Proveedor</th>
                <th class="txC">Precio</th>
                <th class="txC">Cantidad</th>
                <th class="txC">Total</th>
                <th class="txC">Entrega</th>
                <th class="txC">Datos Adicionales</th>
                <th class="txC">Archivos</th>
              </tr>
              <!--<tr class="ClearWindow">-->
              <!--  <td>18/10/2017</td>-->
              <!--  <td>SNK Australia</td>-->
              <!--  <td><span class="label label-success">$200</span></td>-->
              <!--  <td>20</td>-->
              <!--  <td>$200</td>-->
              <!--  <td>2 D&iacute;as</td>-->
              <!--  <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>-->
              <!--  <td><div><a href="../../../../skin/files/quotation/file.pdf" target="_blank"><img src="../../../../skin/images/body/icons/pdf.png"> CotizaciónRoller</a></div></td>-->
              <!--</tr>-->
              
            </tbody></table>
          </div>
           
        </div>
      
      <div id="QuotationsBox" class="box box-primary collapsed-box">
        <div class="box-header with-border txC">
          <h3 class="box-title QuotationBoxTitle cursor-pointer">&Uacute;ltimas cotizaciones al cliente</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
          </div>
        </div>
         
        <div class="box-body">
          <div class="table-responsive txC">
            <table id="CustomerQuotationWrapper" class="table no-margin">
              <thead>
              <tr>
                <th class="txC">Fecha</th>
                <th class="txC">Precio</th>
                <th class="txC">Cantidad</th>
                <th class="txC">Total</th>
                <th class="txC">Entrega</th>
                <th class="txC">Acciones</th>
              </tr>
              </thead>
              <tbody>
                <!--<tr class="ClearWindow">-->
                <!--  <td><span class="label label-default">18/10/2017</span></td>-->
                <!--  <td><span class="label label-success">$312.87</span></td>-->
                <!--  <td>10</td>-->
                <!--  <td><span class="label label-success">$3128.70</span></td>-->
                <!--  <td><span class="label label-warning">2 D&iacute;as</span></td>-->
                <!--  <td>-->
                <!--    <button type="button" class="btn btn-github SeeQuotation hint--bottom hint--bounce" aria-label="Ver Cotizaci&oacute;n" style="margin:0px;" item="1"><i class="fa fa-eye"></i></button>-->
                <!--    <button type="button" class="btn btn-primary CopyQuotation hint--bottom hint--bounce hint--info" aria-label="Copiar Datos" style="margin:0px;" item="1"><i class="fa fa-files-o"></i></button>-->
                <!--  </td>-->
                <!--</tr>-->
                <!--<tr class="ClearWindow">-->
                <!--  <td><span class="label label-default">02/01/2017</span></td>-->
                <!--  <td><span class="label label-success">$206.44</span></td>-->
                <!--  <td>5</td>-->
                <!--  <td><span class="label label-success">$1032.20</span></td>-->
                <!--  <td><span class="label label-warning">3 D&iacute;as</span></td>-->
                <!--  <td>-->
                <!--    <button type="button" class="btn btn-github SeeQuotation hint--bottom hint--bounce" aria-label="Ver Cotizaci&oacute;n" style="margin:0px;" item="1"><i class="fa fa-eye"></i></button>-->
                <!--    <button type="button" class="btn btn-primary CopyQuotation hint--bottom hint--bounce hint--info" aria-label="Copiar Datos" style="margin:0px;" item="1"><i class="fa fa-files-o"></i></button>-->
                <!--  </td>-->
                <!--</tr>-->
                <!--<tr>-->
                <!--  <td></td>-->
                <!--</tr>-->
              </tbody>
            </table>
          </div>
      
        </div>
      
        <!--<div class="box-footer clearfix">-->
          <!--<a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>-->
          <!--<a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>-->
        <!--</div>-->
      
      </div>
      
      
    </div>
    <div class="window-border txC">
        <button type="button" class="btn btn-primary btnBlue BtnWindowClose"><i class="fa fa-check"></i> OK</button>
        <!--<button type="button" class="btn btn-success btnBlue"><i class="fa fa-dollar"></i> Save & Pay</button>-->
        <!--<button type="button" class="btn btn-error btnRed"><i class="fa fa-times"></i> Cancel</button>-->
    </div>
  </div>
<!-------------------------------------------------------------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------------------------------------------------------------->
  <div class="box animated fadeIn" style="min-width:99%">
    <div class="box-header flex-justify-center">
      <div class="innerContainer main_form" style="min-width:100%">
            <form id="QuotationForm">
            
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
              <div class="row form-group inline-form-custom bg-<?php echo $RowTitleClass; ?>" style="margin-bottom:0px!important;">
                
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
                  <strong>Fecha de Entrega</strong>
                </div>
                <div class="col-xs-1 txC">
                  <strong>D&iacute;as</strong>
                </div>
                <div class="col-xs-1 txC"><strong>Costo</strong></div>
                <div class="col-xs-2 txC">
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
                    <?php echo Core::InsertElement("autocomplete","item_1",'','ItemField1 txC form-control itemSelect','validateEmpty="Seleccione un Art&iacute;culo" placeholder="Ingrese un c&oacute;digo" placeholderauto="C&oacute;digo no encontrado" item="1" iconauto="cube"','Product','SearchCodes');?>
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
                    <?php echo Core::InsertElement('text','date_1','','ItemField1 form-control txC delivery_date','disabled="disabled" placeholder="Fecha de Entrega" validateEmpty="Ingrese una fecha"'); ?>
                  </div>
                  <div class="col-xs-1 txC">
                    <span id="Day1" class="Hidden ItemText1 OrderDay"></span>
                    <?php echo str_replace("00","0",Core::InsertElement('text','day_1',"00",'ItemField1 form-control txC DayPicker inputMask','placeholder="D&iacute;as" data-inputmask="\'mask\': \'9{+}\'" validateEmpty="Ingrese una cantidad de d&iacute;as"')); ?>
                  </div>
                  <div id="item_number_1" class="col-xs-1 txC item_number" total="0" item="1">$ 0.00</div>
                  <div class="col-xs-2 txC">
  									  <button type="button" id="SaveItem1" class="btn btnGreen SaveItem" style="margin:0px;" item="1"><i class="fa fa-check"></i></button>
  									  <button type="button" id="EditItem1" class="btn btnBlue EditItem Hidden" style="margin:0px;" item="1"><i class="fa fa-pencil"></i></button>
  									  <button type="button" id="HistoryItem1" class="btn btn-github HistoryItem hint--bottom hint--bounce Hidden" aria-label="Trazabilidad" style="margin:0px;" item="1"><i class="fa fa-book"></i></button>
  									  <!--<button type="button" id="DeleteItem1" class="btn btnRed DeleteItem" style="margin:0px;" item="1"><i class="fa fa-trash"></i></button>-->
  								</div>
  								</form>
                </div>
                <!--- NEW ITEM --->
              </div>
              <!--- TOTALS --->
              <hr style="margin-top:0px!important;">
              <div class="row form-group inline-form-custom bg-<?php echo $RowTitleClass; ?>">
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
                  <button type="button" id="ChangeDays" class="btn bg-teal" style="margin:0px;"><i class="fa fa-flash"></i></button>
                </div>
                <!-- /btn-group -->
                <?php echo Core::InsertElement('text','change_day','','form-control',' placeholder="Modificar los d&iacute;as de todos los art&iacute;culos"'); ?>
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
        </form></div>
    </div><!-- box -->
  </div><!-- box -->
<?php
$Foot->SetScript('../../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
$Foot->SetScript('../../../../vendors/autocomplete/jquery.auto-complete.min.js');
$Foot->SetScript('../../../../vendors/datepicker/bootstrap-datepicker.js');
$Foot->SetScript('script.traceability.js');
include('../../../project/resources/includes/inc.bottom.php');
?>