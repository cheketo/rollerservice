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
    if(empty($Agents))
    {
      $AgentClass = "warning";
      $AgentIcon = "times";
      $AgentName = "Sin Contacto";
    }else{
      $AgentClass = "success";
      $AgentIcon = "male";
      foreach ($Agents as $Agent)
      {
        if($Agent[CompanyAgent::TABLE_ID]==$Data['agent_id'])
          $AgentName = $Agent['name'];
      }
    }
    
    
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
    
    $Date1 = date_create(Core::DateTimeFormat($Edit->Data['creation_date'],'date'));
    $Date2 = date_create(Core::DateTimeFormat($Edit->Data['expire_date'],'date'));
    $Interval = date_diff($Date1, $Date2);
    $ExpireDays = $Interval->format('%a');
     
    
    
    $Head->SetTitle("Cotizaci&oacute;n de ".$Data['company']);
    $Head->SetSubTitle($Menu->GetTitle().$Prefix.$Role);
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    $Head->SetStyle('../../../../vendors/dropzone/dropzone.min.css'); // Dropzone
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
<?php echo Core::InsertElement('hidden','qfilecount',"0.00"); ?>
<?php echo Core::InsertElement('hidden','efilecount',"0.00"); ?>

<?php include_once('window.quotation.php'); ?>
<?php include_once('window.product.php'); ?>
<?php include_once('window.agent.php'); ?>
<?php if($Customer=='Y') include_once('window.email.php'); ?>

  <div class="box animated fadeIn" style="min-width:99%">
    <div class="box-header flex-justify-center">
      <div class="innerContainer main_form" style="min-width:100%">
            <form id="QuotationForm">
            
            <h4 class="subTitleB"><i class="fa fa-<?php echo $TitleIcon ?>"></i> <?php echo $Role; ?></h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                  <?php 
                    $CompanyName = $Field=="provider"?"CONCAT(name,' - ',IF(old_id>0,old_id,company_id))":"CONCAT(name,' - ',company_id)";
                    echo Core::InsertElement('select','company',$Data['company_id'],'form-control chosenSelect','validateEmpty="Seleccione un '.$Role.'" data-placeholder="Seleccione un '.$Role.'"',Core::Select(Company::TABLE,Company::TABLE_ID.','.$CompanyName,$Field."= 'Y' ".$FieldInternational." AND status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],'name'),' ','');
                  ?>
              </div>
            </div>
            <!--<h4 class="subTitleB"><i class="fa fa-male"></i> Contacto</h4>-->
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                  <div id="agent-wrapper">
                    
                    <?php echo Core::InsertElement("hidden","agent",$Data['agent_id']); ?>
                    <strong><button type="button" class="btn btn-lg btn-<?php echo $AgentClass ?>" id="ShowAgentBtn"><i class="fa fa-<?php echo $AgentIcon ?>"></i> <?php echo $AgentName ?></button></strong>
                </div>
              </div>
            </div>
            
            <h4 class="subTitleB"><i class="fa fa-money"></i> Moneda</h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                <?php echo Core::InsertElement('select','currency',$Edit->Data['currency_id'],'form-control chosenSelect','validateEmpty="Seleccione una Moneda" data-placeholder="Seleccione una Moneda"',Core::Select('currency',"currency_id,CONCAT('[',prefix,'] ',title)","",'title DESC'),' ',''); ?>
              </div>
            </div>
            
            <h4 class="subTitleB"><i class="fa fa-calendar"></i> Fecha de Cotizaci&oacute;n</h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                <?php echo Core::InsertElement('text','real_date',date('d/m/Y'),'form-control delivery_date','validateEmpty="Seleccione una Fecha" placeholder="Seleccione una fecha"'); ?>
              </div>
            </div>
            
            <h4 class="subTitleB"><i class="fa fa-calendar-times-o"></i> Vencimiento</h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-3">
                <?php echo Core::InsertElement('text','expire_days',$ExpireDays,'form-control','validateEmpty="Ingrese cantidad de d&iacute;as" placeholder="Ingrese cantidad de d&iacute;as"'); ?>
              </div>
              <div class="col-xs-12 col-sm-9">
                <?php echo Core::InsertElement('text','expire_date',Core::FromDBToDate($Edit->Data['expire_date']),'form-control','disabled="disabled" placeholder="Fecha Vencimiento"'); ?>
              </div>
            </div>
            
            <?php if($Provider=="Y"){ ?>
            <h4 class="subTitleB"><i class="fa fa-file"></i> Archivos Adjuntos</h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                <div id="DropzoneQuotation" class="dropzone txC">
                </div>
              </div>
            </div>
            <div class="row Hidden" id="QFileWrapper">
            </div>
            <br>
            <?php } ?>
            
            <?php if($Customer=="Y")
              { 
                $SentEmails = $Edit->GetSentEmails();
                if(count($SentEmails)>0)
                {
            ?>
                
            <h4 class="subTitleB"><i class="fa fa-envelope"></i> Emails de Cotizaciones</h4>
            <?php 
              
              foreach($SentEmails as $SentEmail)
              {
                switch ($SentEmail['status'])
                {
                  case 'P':
                    $LabelClass = 'warning';
                    $LabelText = 'ENVIADO';
                  break;
                  case 'A':
                    $LabelClass = 'success';
                    $LabelText = 'ABIERTO';
                  break;
                  default:
                    $LabelClass = 'default';
                  break;
                }
            ?>
            
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-3 txC">
                <?php echo $SentEmail['email_to']?>
              </div>
              <div class="col-xs-12 col-sm-3 txC">
                <span class="label label-<?php echo $LabelClass ?>"><?php echo $LabelText ?></span>
              </div>
              <div class="col-xs-12 col-sm-3 txC">
                <h4><a href="<?php echo $SentEmail['file']?>" style="color:#000" class="hint--bottom hint--bounce" aria-label="Descargar PDF"><span class="fa fa-download"></span></a></h4>
              </div>
            </div>
            <br>
            <?php } } } ?>
            
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
                    <?php echo Core::InsertElement("autocomplete","item_".$I,$Item['product_id'].','.$Item['code'].' - '.$Item['brand'],'ItemField'.$I.' txC form-control itemSelect','validateEmpty="Seleccione un Art&iacute;culo" placeholder="Ingrese un c&oacute;digo" placeholderauto="C&oacute;digo no encontrado" item="'.$I.'" iconauto="cube"','Product','SearchCodes');?>
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
                <button type="button" id="BtnCreateProduct" class="btn btn-info"><i class="fa fa-cube"></i> <strong>Crear Art&iacute;culo</strong></button>
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
            
            <h4 class="subTitleB"><i class="fa fa-info-circle"></i> Informaci&oacute;n Extra para el Cliente</h4><div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                  <?php echo Core::InsertElement('textarea','extra',$Data['extra'],'form-control',' placeholder="Datos adicionales"'); ?>
              </div>
          </div>
          <hr>
          <div class="row txC">
            <button type="button" class="btn btn-success btnGreen" id="BtnEdit"><i class="fa fa-plus"></i> Editar Cotizaci&oacute;n</button>
            <?php if($Customer=='Y') { ?><button type="button" class="btn btn-success btnBlue" id="BtnCreateAndEmail"><i class="fa fa-envelope"></i> Editar y Enviar por Email</button><?php } ?>
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
$Foot->SetScript('../../../../vendors/dropzone/dropzone.min.js');
$Foot->SetScript('script.traceability.js');
$Foot->SetScript('script.dropzone.js');
$Foot->SetScript('script.agent.js');
$Foot->SetScript('script.email.js');
$Foot->SetScript('script.product.js');
include('../../../project/resources/includes/inc.bottom.php');
?>