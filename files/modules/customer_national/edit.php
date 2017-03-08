<?php
    include("../../includes/inc.main.php");
    
    $ID           = $_GET['id'];
    $Edit         = new Customer($ID);
    $Data         = $Edit->GetData();
    ValidateID($Data);
    $Branches = $DB->fetchAssoc('customer_branch a, geolocation_country b, geolocation_province c, geolocation_region d, geolocation_zone e','a.*,b.name as country, c.name as province, d.name as region, e.name as zone','a.country_id = b.country_id AND a.province_id = c.province_id AND a.region_id = d.region_id AND a.zone_id = e.zone_id AND customer_id='.$ID,'a.branch_id');
    
    $Head->setTitle($Data['name']);
    $Head->setSubTitle($Menu->GetTitle());
    $Head->setStyle('../../../vendors/select2/select2.min.css'); // Select Inputs With Tags
    $Head->setStyle('../../../skin/css/maps.css'); // Google Maps CSS
    $Head->setHead();
    include('../../includes/inc.top.php');
    
?>
  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-md-8 col-sm-12">
        
          <div class="innerContainer main_form">
            <form id="new_company_form">
            <h4 class="subTitleB"><i class="fa fa-newspaper-o"></i> Datos del Cliente</h4>
            <?php echo insertElement("hidden","id",$ID); ?>
            <?php echo insertElement("hidden","action",'update'); ?>
            <?php echo insertElement("hidden","international",'N'); ?>
            <?php echo insertElement("hidden","newimage",$Edit->GetImg()); ?>
            <?php echo insertElement("hidden","total_branches",count($Branches)); ?>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-building"></i></span>
                  <?php echo insertElement('text','name',$Data['name'],'form-control',' placeholder="Nombre de la Empresa" validateEmpty="Ingrese un nombre." validateFromFile="../../library/processes/proc.common.php///El nombre ya existe///action:=validate///actualname:='.$Data['name'].'///object:=Customer" autofocus'); ?>
                </span>
              </div>
            </div>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-industry"></i></span>
                  <?php echo insertElement('select','type',$Data['type_id'],'form-control','validateEmpty="El tipo de cliente es obligatorio."',$DB->fetchAssoc('customer_type','type_id,name',"status='A'",'name'),'','Seleccione un Tipo de Cliente'); ?>
                </span>
              </div>
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-book"></i></span>
                  <?php echo insertElement('select','iva_select',$Data['iva'],'form-control select2 selectTags','',$DB->fetchAssoc('config_iva_type','type_id,name',"status='A'",'name'),'','Seleccione una Opci&oacute;n'); ?>
                  <?php echo insertElement("hidden","iva"); ?>
                </span>
              </div>
            </div>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                  <?php echo insertElement('text','cuit',$Data['cuit'],'form-control','data-inputmask="\'mask\': \'99-99999999-9\'" placeholder="N&uacute;mero CUIT" validateEmpty="Ingrese un CUIT."'); ?>
                </span>
              </div>
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                  <?php echo insertElement('text','gross_income_number',$Data['iibb'],'form-control',' placeholder="N&uacute;mero Ingresos Brutos" validateMinLength="10///El n&uacute;mero debe contener 11 caracteres como m&iacute;nimo." validateOnlyNumbers="Ingrese n&uacute;meros &uacute;nicamente."'); ?>
                </span>
              </div>
            </div>
            </form>
            <br>
            <div class="row">
              <div class="col-md-12 col-xs-12 simple_upload_image">
                  <h4 class="subTitleB"><i class="fa fa-image"></i> Logo</h4>
                <div class="image_sector">
                  <img id="company_logo" src="<?php echo $Edit->GetImg(); ?>" width="100" alt="" class="animated" />
                  <div id="image_upload" class="overlay-text"><span><i class="fa fa-upload"></i> Subir Im&aacute;gen</span></div>
                  <?php echo insertElement('file','image',$Edit->GetImg(),'form-control Hidden',' placeholder="Sitio Web"'); ?>
                </div>
              </div>
            </div>
            <hr>
          <h4 class="subTitleB"><i class="fa fa-map-pin"></i> Sucursales</h4>
          <div id="MapsErrorMessage" class="Hidden ErrorText Red">Complete los datos de la sucursal central.</div>
          <div id="branches_container">
          <?php 
            $I=0;
            $Class = "bg-gray";
            $Image = 'coal_power_plant.png';
            foreach($Branches as $Branch)
            {
              $I++;
          ?>
          
          <div id="branch_row_<?php echo $I ?>" class="row branch_row listRow2 <?php echo $Class ?>" style="margin:0px!important;">
            <div class="col-lg-1 col-md-2 flex-justify-center hideMobile990">
              <div class="listRowInner">
                <img class="img" style="margin-top:5px!important;" src="../../../skin/images/body/pictures/<?php echo $Image; ?>" alt="Sucursal" title="Sucursal">
              </div>
            </div>
            <div class="col-lg-9 col-md-7 col-sm-8 flex-justify-center">
              <span class="listTextStrong" style="margin-top:15px!important;" id="branch_row_name_<?php echo $I ?>">Sucursal <?php echo $Branch['name']; ?></span>
            </div>
            <div class="col-lg-1 col-md-2 col-sm-4 flex-justify-center">
              <button type="button" branch="<?php echo $I ?>" id="EditBranch<?php echo $I ?>" class="btn btnBlue EditBranch LoadedMap"><i class="fa fa-pencil"></i></button>
              <?php if($I>1){ ?>
              &nbsp;
              <button type="button" id="DeleteBranch<?php echo $I ?>" branch="<?php echo $I ?>" class="btn btnRed DeleteBranch"><i class="fa fa-trash"></i></button>
              <?php } ?>
            </div>
          </div>
					<?php
					    $Class = "";
					    $Image = 'main_branch.png';
            }
					?>
            </div>

      </div>
          <div class="row txC" id="add_branch_button_container">
            <button id="add_branch" type="button" class="btn btn-primary Info-Card-Form-Btn"><i class="fa fa-plus"></i> Agregar una sucursal</button>
          </div>
          <hr>
          <div class="row txC">
            <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-pencil"></i> Editar Proveedor</button>
            <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
          </div>
        </div>
      </div>
    </div><!-- box -->
  </div><!-- box -->
  <div id="ModalBranchesContainer">
    <?php 
            $I=0;
            foreach($Branches as $Branch)
            {
              $I++;
              
              $Edit->Getbranchmodal($I,$Branch);
            }
  ?>
  
  </div>
<?php
$Foot->setScript('../../js/script.map.autolocation.js');
$Foot->setScript('https://maps.googleapis.com/maps/api/js?key=AIzaSyCuMB_Fpcn6USQEoumEHZB_s31XSQeKQc0&libraries=places&language=es','async defer');
$Foot->setScript('../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
$Foot->setScript('../../../vendors/select2/select2.min.js');

include('../../includes/inc.bottom.php');
?>

