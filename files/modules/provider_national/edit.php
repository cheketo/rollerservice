<?php
    include("../../includes/inc.main.php");
    $ID           = $_GET['id'];
    $Edit         = new Provider($ID);
    $Data         = $Edit->GetData();
    $IVA = array(1=>"Excento",2=>"Responsable Inscripto",3=>"Monotributista");
    ValidateID($Data);
    $Agents = $DB->fetchAssoc('provider_agent','*','provider_id='.$ID);
    
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
            <h4 class="subTitleB"><i class="fa fa-newspaper-o"></i> Datos del Proveedor</h4>
            <?php echo insertElement("hidden","action",'update'); ?>
            <?php echo insertElement("hidden","type",'N'); ?>
            <?php echo insertElement("hidden","id",$ID); ?>
            <?php echo insertElement("hidden","newimage",$Edit->GetImg());?>
            <?php echo insertElement("hidden","total_agents",count($Agents)); ?>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-building"></i></span>
                  <?php echo insertElement('text','name',$Data['name'],'form-control',' placeholder="Nombre de la Empresa" validateEmpty="Ingrese un nombre." validateFromFile="../../library/processes/proc.common.php///El nombre ya existe///action:=validate///actualname:='.$Data['name'].'///object:=Provider" autofocus'); ?>
                </span>
              </div>
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-book"></i></span>
                  <?php echo insertElement('select','iva_select',$Data['iva'],'form-control select2 selectTags','',$DB->fetchAssoc('config_iva_type','type_id,name',"status='A'",'name'),'0','Seleccione una Opci&oacute;n'); ?>
                  <?php echo insertElement("hidden","iva",$Data['iva']); ?>
                </span>
              </div>
            </div>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                  <?php echo insertElement('text','cuit',$Data['cuit'],'form-control','data-inputmask="\'mask\': \'99-99999999-9\'" placeholder="N&uacute;mero CUIT" validateEmpty="Ingrese un CUIT." '); ?>
                </span>
              </div>
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                  <?php echo insertElement('text','gross_income_number',$Data['gross_income_tax'],'form-control',' placeholder="N&uacute;mero Ingresos Brutos" validateMinLength="10///El n&uacute;mero debe contener 11 caracteres como m&iacute;nimo." validateOnlyNumbers="Ingrese n&uacute;meros &uacute;nicamente."'); ?>
                </span>
              </div>
            </div>
            <br>
            <h4 class="subTitleB"><i class="fa fa-globe"></i> Geolocalizaci&oacute;n</h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                  <?php echo insertElement('text','address_1',$Data['address'],'form-control','disabled="disabled" placeholder="Direcci&oacute;n" validateMinLength="4///La direcci&oacute;n debe contener 4 caracteres como m&iacute;nimo."'); ?>
                </span>
              </div>
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-bookmark"></i></span>
                  <?php echo insertElement('text','postal_code_1',$Data['postal_code'],'form-control','disabled="disabled" placeholder="C&oacute;digo Postal" validateMinLength="4///La direcci&oacute;n debe contener 4 caracteres como m&iacute;nimo."'); ?>
                </span>
              </div>
            </div>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-12 MapWrapper">
                <!--- GOOGLE MAPS FRAME --->
                <?php echo InsertAutolocationMap(1,$Data); ?>
              </div>
            </div>
            <br>
            <h4 class="subTitleB"><i class="fa fa-globe"></i> Datos de contacto</h4>
            
            
            <div class="row form-group inline-form-custom">
              <div class="col-sm-6 col-xs-12">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                  <?php echo insertElement('text','email',$Data['email'],'form-control',' placeholder="Email"'); ?>
                </span>
              </div>
              <div class="col-sm-6 col-xs-12">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                  <?php echo insertElement('text','phone',$Data['phone'],'form-control',' placeholder="Tel&eacute;fono"'); ?>
                </span>
              </div>
            </div>
            <div class="row form-group inline-form-custom">
              <div class="col-sm-6 col-xs-12">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-desktop"></i></span>
                  <?php echo insertElement('text','website',$Data['website'],'form-control',' placeholder="Sitio Web"'); ?>
                </span>
              </div>
              <div class="col-sm-6 col-xs-12">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-fax"></i></span>
                  <?php echo insertElement('text','fax',$Data['fax'],'form-control',' placeholder="Fax"'); ?>
                </span>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12 col-xs-12 simple_upload_image">
                  <h4 class="subTitleB"><i class="fa fa-image"></i> Logo</h4>
                <div class="image_sector">
                  <img id="company_logo" src="<?php echo $Edit->GetImg(); ?>" width="100" alt="" class="animated" />
                  <div id="image_upload" class="overlay-text"><span><i class="fa fa-upload"></i> Subir Im&aacute;gen</span></div>
                  <?php echo insertElement('file','image','','form-control Hidden',' placeholder="Sitio Web"'); ?>
                </div>
              </div>
            </div>
          </form>
          <br>
          <div class="row">
            <div class="col-md-12 info-card">
              <h4 class="subTitleB"><i class="fa fa-male"></i> Representantes</h4>
              <!--<span id="empty_agent" class="Info-Card-Empty info-card-empty">No hay representantes ingresados</span>-->
              <div id="agent_list" class="row">
                <?php $X=1; foreach($Agents as $Agent){ ?>
                  <div class="col-md-6 col-sm-6 col-xs-12 AgentCard">
                    <div class="info-card-item">
                      <div class="close-btn DeleteAgent"><i class="fa fa-times"></i></div>
                      <?php 
                        if($Agent['name'])
                        {
                          echo '<input type="hidden" id="agent_name_'.$X.'" value="'.$Agent['name'].'" />';
                          echo '<span><i class="fa fa-user"></i> <b>'.$Agent['name'].'</b></span><br>';
                        }
                        if($Agent['charge']) 
                        {
                          echo '<input type="hidden" id="agent_charge_'.$X.'" value="'.$Agent['charge'].'" />';
                          echo '<span><i class="fa fa-briefcase"></i> '.$Agent['charge'].'</span><br>';
                        }
                        if($Agent['email']) 
                        {
                          echo '<input type="hidden" id="agent_email_'.$X.'" value="'.$Agent['email'].'" />';
                          echo '<span><i class="fa fa-envelope"></i> '.$Agent['email'].'</span><br>';
                        }
                        if($Agent['phone']) 
                        {
                          echo '<input type="hidden" id="agent_phone_'.$X.'" value="'.$Agent['phone'].'" />';
                          echo '<span><i class="fa fa-phone"></i> '.$Agent['phone'].'</span><br>';
                        }
                        if($Agent['extra']) 
                        {
                          echo '<input type="hidden" id="agent_extra_'.$X.'" value="'.$Agent['extra'].'" />';
                          echo '<span><i class="fa fa-info-circle"></i> '.$Agent['extra'].'</span><br>';
                        }
                      ?>
                    </div>
                  </div>
                <?php $X++;} ?>
                
              </div>
              <button id="agent_new" type="button" class="btn btn-warning Info-Card-Form-Btn"><i class="fa fa-plus"></i> Agregar un representante</button>

              <!-- New representative form -->
              <div id="agent_form" class="Info-Card-Form Hidden">
                <form id="new_agent_form">
                  <div class="info-card-arrow">
                    <div class="arrow-up"></div>
                  </div>
                  <div class="info-card-form animated fadeIn">
                    <div class="row form-group inline-form-custom">
                      <div class="col-xs-12 col-sm-6">
                        <span class="input-group">
                          <span class="input-group-addon"><i class="fa fa-user"></i></span>
                          <?php echo insertElement('text','agentname','','form-control',' placeholder="Nombre y Apellido" validateEmpty="Ingrese un nombre"'); ?>
                          </span>
                      </div>
                      <div class="col-xs-12 col-sm-6">
                        <span class="input-group">
                          <span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
                          <?php echo insertElement('text','agentcharge','','form-control',' placeholder="Cargo"'); ?>
                        </span>
                      </div>
                    </div>
                    <div class="row form-group inline-form-custom">
                      <div class="col-xs-12 col-sm-6">
                        <span class="input-group">
                          <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                          <?php echo insertElement('text','agentemail','','form-control',' placeholder="Email" validateEmail="Ingrese un email v&aacute;lido."'); ?>
                        </span>
                      </div>
                      <div class="col-xs-12 col-sm-6">
                        <span class="input-group">
                          <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                          <?php echo insertElement('text','agentphone','','form-control',' placeholder="Tel&eacute;fono"'); ?>
                        </span>
                      </div>
                    </div>
                    <div class="row form-group inline-form-custom">
                      <div class="col-xs-12 col-sm-12">
                        <span class="input-group">
                          <span class="input-group-addon"><i class="fa fa-info-circle"></i></span>
                          <?php echo insertElement('textarea','agentextra','','form-control','rows="1" placeholder="Informaci&oacute;n Extra"'); ?>
                        </span>
                      </div>
                    </div>
                    <div class="row txC">
                      <button id="agent_add" type="button" class="Info-Card-Form-Done btn btnGreen"><i class="fa fa-check"></i> Agregar</button>
                      <button id="agent_cancel" type="button" class="Info-Card-Form-Done btn btnRed"><i class="fa fa-times"></i> Cancelar</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- New representative form -->
            </div>
          </div>
          <hr>
          <div class="row txC">
            <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-plus"></i> Modificar Proveedor</button>
            <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
          </div>
        </div>
      </div>
    </div><!-- box -->
  </div><!-- box -->

<?php
$Foot->setScript('../../js/script.map.autolocation.js');
$Foot->setScript('https://maps.googleapis.com/maps/api/js?key=AIzaSyCuMB_Fpcn6USQEoumEHZB_s31XSQeKQc0&libraries=places&callback=initMaps&language=es','async defer');
$Foot->setScript('../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
$Foot->setScript('../../../vendors/select2/select2.min.js');
include('../../includes/inc.bottom.php');
?>