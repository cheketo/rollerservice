<?php
    include("../../includes/inc.main.php");
    $New = new Customer();
    $Head->setTitle($Menu->GetTitle());
    $Head->setStyle('../../../vendors/select2/select2.min.css'); // Select Inputs With Tags
    //$Head->setStyle('../../../skin/css/maps.css'); // Google Maps CSS
    $Head->setHead();
    include('../../includes/inc.top.php');
    
    
?>
  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-md-8 col-sm-12">
        
          <div class="innerContainer main_form">
            <form id="new_company_form">
            <h4 class="subTitleB"><i class="fa fa-newspaper-o"></i> Datos del Cliente</h4>
            <?php echo insertElement("hidden","action",'insert'); ?>
            <?php echo insertElement("hidden","international",'N'); ?>
            <?php echo insertElement("hidden","newimage",$New->GetDefaultImg()); ?>
            <?php echo insertElement("hidden","total_branches","1"); ?>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-building"></i></span>
                  <?php echo insertElement('text','name','','form-control',' placeholder="Nombre de la Empresa" validateEmpty="Ingrese un nombre." validateFromFile="../../library/processes/proc.common.php///El nombre ya existe///action:=validate///object:=Customer" autofocus'); ?>
                </span>
              </div>
            </div>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-industry"></i></span>
                  <?php echo insertElement('select','type','','form-control','validateEmpty="Seleccione un tipo de cliente."',$DB->fetchAssoc('customer_type','type_id,name',"status='A'",'name'),'0','Seleccione una Opci&oacute;n'); ?>
                </span>
              </div>
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-book"></i></span>
                  <?php echo insertElement('select','iva_select','','form-control select2 selectTags','',$DB->fetchAssoc('config_iva_type','type_id,name',"status='A'",'name'),'0','Seleccione una Opci&oacute;n'); ?>
                  <?php echo insertElement("hidden","iva"); ?>
                </span>
              </div>
            </div>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                  <?php echo insertElement('text','cuit','','form-control','data-inputmask="\'mask\': \'99-99999999-9\'" placeholder="N&uacute;mero CUIT" validateEmpty="Ingrese un CUIT."'); ?>
                </span>
              </div>
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                  <?php echo insertElement('text','gross_income_number','','form-control',' placeholder="N&uacute;mero Ingresos Brutos" validateMinLength="10///El n&uacute;mero debe contener 11 caracteres como m&iacute;nimo." validateOnlyNumbers="Ingrese n&uacute;meros &uacute;nicamente."'); ?>
                </span>
              </div>
            </div>
            </form>
            <br>
            <div class="row">
              <div class="col-md-12 col-xs-12 simple_upload_image">
                  <h4 class="subTitleB"><i class="fa fa-image"></i> Logo</h4>
                <div class="image_sector">
                  <img id="company_logo" src="<?php echo $New->GetDefaultImg(); ?>" width="100" alt="" class="animated" />
                  <div id="image_upload" class="overlay-text"><span><i class="fa fa-upload"></i> Subir Im&aacute;gen</span></div>
                  <?php echo insertElement('file','image','','form-control Hidden',' placeholder="Sitio Web"'); ?>
                </div>
              </div>
            </div>
            <hr>
          <div id="branches_container">
<!------------------------------------------ MAIN BRANCH ---------------------------------------------------->
          <div id="branch_1">
            <h4 class="subTitleB"><i class="fa fa-globe"></i> Geolocalizaci&oacute;n</h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                  <?php echo insertElement('text','address_1','','form-control','disabled="disabled" placeholder="Direcci&oacute;n" validateMinLength="4///La direcci&oacute;n debe contener 4 caracteres como m&iacute;nimo."'); ?>
                </span>
              </div>
              <div class="col-xs-12 col-sm-6">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-bookmark"></i></span>
                  <?php echo insertElement('text','postal_code_1','','form-control','disabled="disabled" placeholder="C&oacute;digo Postal" validateMinLength="4///La direcci&oacute;n debe contener 4 caracteres como m&iacute;nimo."'); ?>
                </span>
              </div>
            </div>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-12">
                <!--- GOOGLE MAPS FRAME --->
                <?php InsertAutolocationMap(1); ?>
              </div>
            </div>

            <br>
            <h4 class="subTitleB"><i class="fa fa-globe"></i> Datos de contacto</h4>
            <div class="row form-group inline-form-custom">
              <div class="col-sm-6 col-xs-12">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                  <?php echo insertElement('text','email_1','','form-control',' placeholder="Email"'); ?>
                </span>
              </div>
              <div class="col-sm-6 col-xs-12">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                  <?php echo insertElement('text','phone_1','','form-control',' placeholder="Tel&eacute;fono"'); ?>
                </span>
              </div>
            </div>
            <div class="row form-group inline-form-custom">
              <div class="col-sm-6 col-xs-12">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-desktop"></i></span>
                  <?php echo insertElement('text','website_1','','form-control',' placeholder="Sitio Web"'); ?>
                </span>
              </div>
              <div class="col-sm-6 col-xs-12">
                <span class="input-group">
                  <span class="input-group-addon"><i class="fa fa-fax"></i></span>
                  <?php echo insertElement('text','fax_1','','form-control',' placeholder="Fax"'); ?>
                </span>
              </div>
            </div>
          <br>
          <div class="row">
            <div class="col-md-12 info-card">
              <h4 class="subTitleB"><i class="fa fa-male"></i> Representantes</h4>
              <!--<span id="empty_agent" class="Info-Card-Empty info-card-empty">No hay representantes ingresados</span>-->
              <div id="agent_list" class="row">
              </div>
              <div class="row txC">
                <button id="agent_new" type="button" class="btn btn-warning Info-Card-Form-Btn"><i class="fa fa-plus"></i> Agregar un representante</button>
              </div>
              <?php echo insertElement("hidden","total_agents_1","0",'','branch="1"'); ?>
              <?php echo insertElement("hidden","branch_name_1",'central','','branch="1"'); ?>
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
                          <?php echo insertElement('text','agentname_1','','form-control',' placeholder="Nombre y Apellido" validateEmpty="Ingrese un nombre"'); ?>
                          </span>
                      </div>
                      <div class="col-xs-12 col-sm-6">
                        <span class="input-group">
                          <span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
                          <?php echo insertElement('text','agentcharge_1','','form-control',' placeholder="Cargo"'); ?>
                        </span>
                      </div>
                    </div>
                    <div class="row form-group inline-form-custom">
                      <div class="col-xs-12 col-sm-6">
                        <span class="input-group">
                          <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                          <?php echo insertElement('text','agentemail_1','','form-control',' placeholder="Email" validateEmail="Ingrese un email v&aacute;lido."'); ?>
                        </span>
                      </div>
                      <div class="col-xs-12 col-sm-6">
                        <span class="input-group">
                          <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                          <?php echo insertElement('text','agentphone_1','','form-control',' placeholder="Tel&eacute;fono"'); ?>
                        </span>
                      </div>
                    </div>
                    <div class="row form-group inline-form-custom">
                      <div class="col-xs-12 col-sm-12">
                        <span class="input-group">
                          <span class="input-group-addon"><i class="fa fa-info-circle"></i></span>
                          <?php echo insertElement('textarea','agentextra_1','','form-control','rows="1" placeholder="Informaci&oacute;n Extra"'); ?>
                        </span>
                      </div>
                    </div>
                    <div class="row txC">
                      <button id="agent_add_1" branch="1" type="button" class="Info-Card-Form-Done btn btnGreen"><i class="fa fa-check"></i> Agregar</button>
                      <button id="agent_cancel_1" branch="1" type="button" class="Info-Card-Form-Done btn btnRed"><i class="fa fa-times"></i> Cancelar</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- New representative form -->
            </div>
          </div>
          <br>
          <h4 class="subTitleB"><i class="fa fa-briefcase"></i> Corredores</h4>
          <div id="agent_list_1" branch="1" class="row">
            <div class="col-xs-12 col-sm-6">
              <?php echo insertElement('select','select_broker_1','','form-control select2 selectTags BrokerSelect','',$DB->fetchAssoc('admin_user',"admin_id,CONCAT(first_name,' ',last_name) as name","status='A' AND profile_id = 361",'name'),'0','Seleccione una Opci&oacute;n'); ?>
              <?php echo insertElement('hidden','brokers_1',''); ?>
            </div>
            <div class="col-xs-12 col-sm-6">
              <button id="add_broker" branch="1" style="margin:0px!important;" type="button" class="btn btn-success Info-Card-Form-Btn"><i class="fa fa-plus"></i> Agregar Corredor</button>
            </div>
          </div>
          <hr>
        </div>
<!------------------------------------------ END MAIN BRANCH ---------------------------------------------------->
      </div>
          <div class="row txC" id="add_branch_button_container">
            <button id="add_branch" type="button" class="btn btn-primary Info-Card-Form-Btn"><i class="fa fa-plus"></i> Agregar una sucursal</button>
          </div>
          <hr>
          <div class="row txC">
            <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-plus"></i> Crear Proveedor</button>
            <button type="button" class="btn btn-success btnBlue" id="BtnCreateNext"><i class="fa fa-plus"></i> Crear y Agregar Otro</button>
            <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
          </div>
        </div>
      </div>
    </div><!-- box -->
  </div><!-- box -->
<?php $Foot->setScript('../../js/script.map.autolocation.js'); ?>
<?php
// $Foot->setScript('../../../vendors/inputmask3/inputmask.min.js');
// $Foot->setScript('../../../vendors/inputmask3/inputmask.numeric.extensions.min.js');
$Foot->setScript('../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
$Foot->setScript('../../../vendors/select2/select2.min.js');
include('../../includes/inc.bottom.php');
?>