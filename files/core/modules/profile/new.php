<?php
    include("../../../core/resources/includes/inc.core.php");
    $Profile  = new CoreProfile();
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/bootstrap-switch/bootstrap-switch.css'); // Switch On Off
    $Head->setHead();
    include('../../../project/resources/includes/inc.top.php');
?>
  <?php echo Core::InsertElement("hidden","action",'insert'); ?>
  <?php echo Core::InsertElement("hidden","menues",""); ?>
  <?php echo Core::InsertElement("hidden","newimage",CoreProfile::DEFAULT_IMG); ?>
  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-lg-8 col-sm-12">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-plus-circle"></i> Complete los campos para crear un nuevo perfil</h4>
            <div class="row form-group inline-form-custom-2">
              <div class="col-xs-12 col-sm-6 inner">
                <label>T&iacute;tulo</label>
                <?php echo Core::InsertElement('text','title','','form-control','placeholder="Ingrese un T&iacute;tulo" validateEmpty="Ingrese un t&iacute;tulo." validateFromFile='.PROCESS.'///El perfil ya existe///action:=validate///object:=CoreProfile"'); ?>
              </div>
              <div class="col-xs-12 col-sm-6 inner">
                <label for="">Grupos</label>
                <div class="form-group" id="groups-wrapper">
                  <?php echo Core::InsertElement('multiple','groups','','form-control chosenSelect','data-placeholder="Seleccione Grupos"',Core::Select(CoreGroup::TABLE,CoreGroup::TABLE_ID.',title',"status<>'I' AND ".CoreOrganization::TABLE_ID." = ".$_SESSION[CoreOrganization::TABLE_ID])); ?>
                </div>
              </div>
              <!--<div class="col-xs-12 col-sm-12 inner">-->
              <!--  <label for="">Usuarios</label>-->
              <!--  <div class="form-group" id="groups-wrapper">-->
              <!--    <?php //echo Core::InsertElement('multiple','users','','form-control chosenSelect','data-placeholder="Seleccione Usuarios"',Core::Select('core_user','user_id,user',"status='A' AND organization_id = ".$_SESSION['organization_id'])); ?>-->
              <!--  </div>-->
              <!--</div>-->
              <div class="col-xs-12 col-sm-6 inner">
                <label for="">Im&aacute;gen</label>
                <div class="lineContainer txC">

                  <div class="flex-allCenter imgSelector">
                    <div class="imgSelectorInner">
                      <img src="<?php echo $Profile->GetImg(); ?>" class="img-responsive MainImg animated">
                      <?php echo Core::InsertElement('file','image','','Hidden'); ?>
                      <div class="imgSelectorContent">
                        <div id="SelectImg">
                          <i class="fa fa-upload"></i><br>
                           <p>Cargar Nueva Im&aacute;gen</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="text-bottom">
                    <p><i class="fa fa-upload" aria-hidden="true"></i>
                    Haga Click sobre la im&aacute;gen </br> para cargar una desde su dispositivo</p>
                  </div>

                </div>
              </div>
              <div class="col-xs-12 col-sm-6 inner">
                <label for="">Permisos</label>
                <div class="lineContainer">
                  <div id="treeview-checkbox">
                    <?php echo $Menu->MakeTree(); ?>
                  </div><!-- treeview-checkbox -->
                </div>
              </div>
            </div><!-- inline-form -->
            <hr>
            <div class="txC">
              <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-plus"></i> Crear Perfil</button>
              <button type="button" class="btn btn-success btnBlue" id="BtnCreateNext"><i class="fa fa-plus"></i> Crear y Agregar Otro</button>
              <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
            </div>
        </div>
      </div>
    </div>
  </div>

<?php
  $Foot->SetScript('../../../../vendors/bootstrap-switch/script.bootstrap-switch.min.js');
  
  $Foot->SetScript('../../../../vendors/treemultiselect/logger.min.js');
  $Foot->SetScript('../../../../vendors/treemultiselect/treeview.min.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>
