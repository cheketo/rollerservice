<?php
    include("../../../core/resources/includes/inc.core.php");
    $New = new Company();
    $Branch = new CompanyBranch();
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../skin/css/maps.css'); // Google Maps CSS
    $Head->setHead();
    include('../../../project/resources/includes/inc.top.php');
?>
  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-md-8 col-sm-12">
        
          <div class="innerContainer main_form">
            <form id="new_company_form">
            <h4 class="subTitleB"><i class="fa fa-id-card"></i> Datos de la Empresa</h4>
            <?php echo Core::InsertElement("hidden","action",'insert'); ?>
            <?php echo Core::InsertElement("hidden","newimage",Company::DEFAULT_IMG); ?>
            <?php echo Core::InsertElement("hidden","total_branches","1"); ?>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                  <?php echo Core::InsertElement('text','name','','form-control',' placeholder="Nombre de la Empresa" validateEmpty="Ingrese un nombre." validateFromFile="'.PROCESS.'///El nombre de empresa ya existe///action:=validate///object:=Company" autofocus'); ?>
                </span>
              </div>
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                  <?php echo Core::InsertElement('select','international','','form-control chosenSelect','data-placeholder="Nacionalidad" validateEmpty="Ingrese un tipo de nacionalidad."',array("N"=>"Nacional","Y"=>"Internacional"),' ',''); ?>
                </span>
              </div>
            </div>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-building"></i></span>
                  <?php echo Core::InsertElement('select','type','','form-control chosenSelect','validateEmpty="El tipo de cliente es obligatorio." data-placeholder="Seleccione un Tipo de Empresa"',Core::Select('company_type','type_id,name',"status='A'",'name'),' ',''); ?>
                </span>
              </div>
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-exchange"></i></span>
                  <?php echo Core::InsertElement('select','relation','','form-control chosenSelect','validateEmpty="Seleccione un tipo de relaci&oacute;n" data-placeholder="Relaci&oacute;n"',array(1=>"Cliente",2=>"Proveedor",3=>"Cliente y Proveedor"),' ',''); ?>
                </span>
              </div>
            </div>
            <br>
            
            <div id="Billing" class="Hidden">
              <h4 class="subTitleB"><i class="fa fa-file-excel-o"></i> Datos de Facturaci&oacute;n</h4>
              
              <div id="BillingInternational">
                <div class="row form-group inline-form-custom">
                  <div class="col-xs-12">
                    <span class="input-group">
                      <span class="input-group-addon"><i class="fa fa-ship"></i></span>
                      <?php echo Core::InsertElement('text','vat','','form-control','placeholder="VAT"'); ?>
                    </span>
                  </div>
                </div>
              </div>  
              
              <div id="BillingNational">
                <div class="row form-group inline-form-custom">
                  <div class="col-xs-12">
                    <span class="input-group">
                      <span class="input-group-addon"><i class="fa fa-book"></i></span>
                      <?php echo Core::InsertElement('select','iva','','form-control chosenSelect','validateEmpty="Ingrese IVA" data-placeholder="IVA"',Core::Select('tax_iva_type','type_id,name',"status='A'",'name'),' ',''); ?>
                    </span>
                  </div>
                </div>
                <div class="row form-group inline-form-custom">
                  <div class="col-xs-12 col-sm-6">
                    <span class="input-group">
                      <span class="input-group-addon"><i class="fa fa-ticket"></i></span>
                      <?php echo Core::InsertElement('text','cuit','','form-control inputMask','data-inputmask="\'mask\': \'99-99999999-9\'" placeholder="N&uacute;mero CUIT" validateEmpty="Ingrese un CUIT."'); ?>
                    </span>
                  </div>
                  <div class="col-xs-12 col-sm-6">
                    <span class="input-group">
                      <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                      <?php echo Core::InsertElement('text','gross_income_number','','form-control',' placeholder="N&uacute;mero Ingresos Brutos" validateMinLength="10///El n&uacute;mero debe contener 11 caracteres como m&iacute;nimo." validateOnlyNumbers="Ingrese n&uacute;meros &uacute;nicamente."'); ?>
                    </span>
                  </div>
                </div>
              </div>
              
            </div>  
            </form>
            <br>
            <div class="row">
              <div class="col-md-12 col-xs-12 simple_upload_image">
                  <h4 class="subTitleB"><i class="fa fa-image"></i> Logo</h4>
                <div class="image_sector">
                  <img id="company_logo" src="<?php echo Company::DEFAULT_IMG ?>" width="100" alt="" class="animated" />
                  <div id="image_upload" class="overlay-text"><span><i class="fa fa-upload"></i> Subir Im&aacute;gen</span></div>
                  <?php echo Core::InsertElement('file','image','','form-control Hidden',' placeholder="Sitio Web"'); ?>
                </div>
              </div>
            </div>
            <hr>
          <h4 class="subTitleB"><i class="fa fa-map-pin"></i> Sucursales</h4>
          <div id="MapsErrorMessage" class="Hidden ErrorText Red">Complete los datos de la sucursal central.</div>
          <div id="branches_container">
            
            <div class="row branch_row listRow2 bg-gray" style="margin:0px!important;">
              <div class="col-lg-1 col-md-2 col-sm-3 col-xs-0 flex-justify-center hideMobile990">
									<div class="listRowInner">
										<img class="img" style="margin-top:5px!important;" src="<?php echo CompanyBranch::DEFAULT_IMG ?>" alt="Sucursal" title="Sucursal">
									</div>
								</div>
								<div class="col-lg-9 col-md-7 col-sm-5 col-xs-7 flex-justify-center" style="margin-right:0px;">
										<span class="listTextStrong" style="margin-top:15px!important;">Sucursal Central</span>
								</div>
								<div class="col-lg-1 col-md-2 col-sm-3 col-xs-5 flex-justify-center" style="margin-left:0px;">
									  <button type="button" class="btn btnBlue EditBranch LoadedMap" branch="1"><i class="fa fa-pencil"></i></button>
								</div>
							</div>
								
            </div>

      </div>
          <div class="row txC" id="add_branch_button_container">
            <button id="add_branch" type="button" class="btn btn-primary Info-Card-Form-Btn"><i class="fa fa-plus"></i> Agregar una sucursal</button>
          </div>
          <hr>
          <div class="row txC">
            <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-plus"></i> Crear Empresa</button>
            <button type="button" class="btn btn-success btnBlue" id="BtnCreateNext"><i class="fa fa-plus"></i> Crear y Agregar Otra</button>
            <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
          </div>
        </div>
      </div>
    </div><!-- box -->
  </div><!-- box -->
  <div id="ModalBranchesContainer">
  <?php $Branch->Getbranchmodal();  ?>
  </div>
<?php
$Foot->SetScript('../../../core/resources/js/script.core.map.autolocation.js');
$Foot->SetScript('https://maps.googleapis.com/maps/api/js?key=AIzaSyCuMB_Fpcn6USQEoumEHZB_s31XSQeKQc0&libraries=places&language=es','async defer');
$Foot->SetScript('../../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
include('../../../project/resources/includes/inc.bottom.php');
?>