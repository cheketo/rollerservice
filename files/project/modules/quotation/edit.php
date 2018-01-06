<?php
    include("../../../core/resources/includes/inc.core.php");
    
    $International = $_GET['international']? $_GET['international']:'N';
    $Customer = $_GET['customer']? $_GET['customer']:'N';
    $Provider = $_GET['provider']? $_GET['provider']:'N';
    
    $ID     = $_GET['id'];
    $Edit   = new Quotation($ID);
    $Data   = $Edit->GetData();
    Core::ValidateID($Data['quotation_id']);
    $Status = $Data['status'];
    if($Status=='F')
    {
      header('Location: list.php?error=status'.Quotation::GetParams());
			die();
    }
    $Agents     = Core::Select('company_agent','agent_id,name',Company::TABLE_ID.'='.$Data[Company::TABLE_ID]);
    $TotalItems = count($Data['items']);
    
    
    if($Data['provider']=='Y')
    {
      $Field  = 'provider';
      $Role   = 'Proveedor';
      $Prefix = ' de ';
      $Title  = $Prefix.'Proveedores';
      $TitleIcon   = 'shopping-cart';
      $CompanyType = 'sender';
      $RowTitleClass = 'navy';
    }elseif($Data['customer']=='Y'){
      $Field  = 'customer';
      $Prefix = ' a ';
      $Role   = 'Cliente';
      $Title  = $Prefix.'Clientes';
      $TitleIcon   = 'users';
      $CompanyType = 'receiver';
      $RowTitleClass = 'light-blue';
    }else{
      // Send it back if customer o provider is not obtained
      header('Location: list.php?'.Quotation::GetParams());
    	die();
    }
    $TypeID = Core::Select("purchase_type","type_id","international='".$International."' AND customer='".$Customer."' AND provider='".$Provider."'")[0]['type_id'];
    
    $FieldInternational = $_GET['international']? "AND international='".$_GET['international']."' ":"";
    $ProductCodes = Product::GetFullCodes();
    
    $Head->SetTitle("Cotizaci&oacute;n de ".$Data['company']);
    $Head->SetSubTitle($Menu->GetTitle().$Prefix.$Role);
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    $Head->SetStyle('../../../../vendors/autocomplete/jquery.auto-complete.css'); // Autocomplete
    $Head->setHead();
    include('../../../project/resources/includes/inc.top.php');
?>
<?php echo Core::InsertElement("hidden","action",'update'); ?>
<?php echo Core::InsertElement("hidden","id",$ID); ?>
<?php echo Core::InsertElement("hidden","type_id",$TypeID); ?>
<?php echo Core::InsertElement("hidden","items",$TotalItems); ?>
<?php echo Core::InsertElement("hidden","company_type",$CompanyType); ?>
<?php echo Core::InsertElement("hidden","creation_date",$Data['creation_date']); ?>

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
                  <?php echo Core::InsertElement('select','company',$Data['company_id'],'form-control chosenSelect','validateEmpty="Seleccione un '.$Role.'" data-placeholder="Seleccione un '.$Role.'"',Core::Select(Company::TABLE,Company::TABLE_ID.',name',$Field."= 'Y' ".$FieldInternational." AND status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],'name'),' ',''); ?>
                  <?php //echo Core::InsertElement("text","provider",'','Hidden',''); ?>
              </div>
            </div>
            <h4 class="subTitleB"><i class="fa fa-male"></i> Contacto</h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                  <div id="agent-wrapper">
                    <?php if(empty($Agents))
                          {
                            echo '<select id="agent" class="form-control chosenSelect" disabled="disabled"><option value="0">Sin Contacto</option></select>';
                          }else{
                            echo Core::InsertElement('select','agent',$Data['agent_id'],'form-control chosenSelect','',$Agents);
                          }
                     ?>
                </div>
              </div>
            </div>
            
            <h4 class="subTitleB"><i class="fa fa-money"></i> Moneda</h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                <?php echo Core::InsertElement('select','currency',$Edit->Data['currency_id'],'form-control chosenSelect','validateEmpty="Seleccione una Moneda" data-placeholder="Seleccione una Moneda"',Core::Select('currency','currency_id,title',"",'title DESC'),' ',''); ?>
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
                  <strong>Fecha Entrega</strong>
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
                <?php $I = 1;
                      foreach($Data['items'] as $Item)
                      {
                        // $Date = Core::FromDBToDate($Item['delivery_date']);
                        echo Core::InsertElement("hidden","creation_date_".$I,$Item['creation_date_item']);
                        $Days = $Item['days']?$Item['days']:"00";
                      ?>
                <!--- NEW ITEM --->
                <div id="item_row_<?php echo $I ?>" item="<?php echo $I ?>" class="row form-group inline-form-custom ItemRow bg-gray" style="margin-bottom:0px!important;padding:10px 0px!important;">
                  <form id="item_form_<?php echo $I ?>" name="item_form_<?php echo $I ?>">
                  <div class="col-xs-4 txC">
                    <span id="Item<?php echo $I ?>" class="Hidden ItemText<?php echo $I ?>"></span>
                    <?php //echo Core::InsertElement('select','item_'.$I,$Item['product_id'],'ItemField'.$I.' form-control chosenSelect itemSelect','validateEmpty="Seleccione un Art&iacute;culo" data-placeholder="Seleccione un Art&iacute;culo" item="'.$I.'"',$ProductCodes,' ',''); ?>
                    <?php echo Core::InsertElement("autocomplete","item_".$I,$Item['product_id'].','.$Item['code'],'ItemField'.$I.' txC form-control itemSelect','validateEmpty="Seleccione un Art&iacute;culo" placeholder="Ingrese un c&oacute;digo" placeholderauto="C&oacute;digo no encontrado" item="'.$I.'" iconauto="cube"','Product','SearchCodes');?>
                    <?php //echo Core::InsertElement("text","item_1",'','Hidden',''); ?>
                  </div>
                  <div class="col-xs-1 txC">
                    <span id="Price<?php echo $I ?>" class="Hidden ItemText<?php echo $I ?>"></span>
                    <?php echo Core::InsertElement('text','price_'.$I,$Item['price'],'ItemField'.$I.' form-control txC calcable inputMask','data-inputmask="\'mask\': \'9{+}.99\'" placeholder="Precio" validateEmpty="Ingrese un precio"'); ?>
                  </div>
                  <div class="col-xs-1 txC">
                    <span id="Quantity<?php echo $I ?>" class="Hidden ItemText<?php echo $I ?>"></span>
                    <?php echo Core::InsertElement('text','quantity_'.$I,$Item['quantity'],'ItemField'.$I.' form-control txC calcable QuantityItem inputMask','data-inputmask="\'mask\': \'9{+}\'" placeholder="Cantidad" validateEmpty="Ingrese una cantidad"'); ?>
                  </div>
                  <div class="col-xs-2 txC">
                    <span id="Date<?php echo $I ?>" class="Hidden ItemText<?php echo $I ?> OrderDate"></span>
                    <?php echo Core::InsertElement('text','date_'.$I,'','ItemField'.$I.' form-control txC delivery_date','disabled="disabled" placeholder="Fecha de Entrega" validateEmpty="Ingrese una fecha"'); ?>
                  </div>
                  <div class="col-xs-1 txC">
                    <span id="Day<?php echo $I ?>" class="Hidden ItemText<?php echo $I ?> OrderDay"></span>
                    <?php echo str_replace("00","0",Core::InsertElement('text','day_'.$I,$Days,'ItemField'.$I.' form-control txC DayPicker','placeholder="D&iacute;as de Entrega" validateEmpty="Ingrese una cantidad de d&iacute;as"')); ?>
                  </div>
                  <div id="item_number_<?php echo $I ?>" class="col-xs-1 txC item_number" total="<?php echo $Item['total_item'] ?>" item="<?php echo $I ?>">$ <?php echo $Item['total_item'] ?></div>
                  <div class="col-xs-2 txC">
  									  <button type="button" id="SaveItem<?php echo $I ?>" class="btn btnGreen SaveItem" style="margin:0px;" item="<?php echo $I ?>"><i class="fa fa-check"></i></button>
  									  <button type="button" id="HistoryItem<?php echo $I ?>" class="btn btn-github HistoryItem hint--bottom hint--bounce Hidden" aria-label="Trazabilidad" style="margin:0px;" item="<?php echo $I ?>"><i class="fa fa-book"></i></button>
  									  <button type="button" id="EditItem<?php echo $I ?>" class="btn btnBlue EditItem Hidden" style="margin:0px;" item="<?php echo $I ?>"><i class="fa fa-pencil"></i></button>
  									  <?php if($I!=1){ ?>
									    <button type="button" id="DeleteItem<?php echo $I ?>" class="btn btnRed DeleteItem" style="margin:0px;" item="<?php echo $I ?>"><i class="fa fa-trash"></i></button>
									    <?php } ?>
  								</div>
  								</form>
                </div>
                <!--- NEW ITEM --->
                <?php $I++; } ?>
              </div>
              <!--- TOTALS --->
              <hr style="margin-top:0px!important;">
              <div class="row form-group inline-form-custom bg-<?php echo $RowTitleClass; ?>">
                <div class="col-xs-4 txC">
                  Art&iacute;culos Totales: <strong id="TotalItems" ><?php echo $TotalItems ?></strong>
                </div>
                <div class="col-xs-3 txC">
                  Cantidad Total: <strong id="TotalQuantity">0</strong>
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
                  <?php echo Core::InsertElement('textarea','extra',$Data['extra'],'form-control',' placeholder="Datos adicionales"'); ?>
              </div>
          </div>
          <hr>
          <div class="row txC">
            <button type="button" class="btn btn-success btnGreen" id="BtnEdit"><i class="fa fa-plus"></i> Editar Cotizaci&oacute;n</button>
            <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
          </div>
          </form>
        </div>
    </div><!-- box -->
  </div><!-- box -->
<?php
$Foot->SetScript('../../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
$Foot->SetScript('../../../../vendors/autocomplete/jquery.auto-complete.min.js');
$Foot->SetScript('../../../../vendors/datepicker/bootstrap-datepicker.js');
$Foot->SetScript('script.traceability.js');
include('../../../project/resources/includes/inc.bottom.php');
?>