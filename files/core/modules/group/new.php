<?php
    include("../../../core/resources/includes/inc.core.php");
    $Group  = new CoreGroup();
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/bootstrap-switch/bootstrap-switch.css'); // Switch On Off
    $Head->setHead();
    include('../../../project/resources/includes/inc.top.php');
?>
  <?php echo Core::InsertElement("hidden","action",'insert'); ?>
  <?php echo Core::InsertElement("hidden","menues",""); ?>
  <?php echo Core::InsertElement("hidden","newimage",CoreGroup::DEFAULT_IMG); ?>
  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-lg-8 col-sm-12">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-plus-circle"></i> Complete los campos para agregar un nuevo grupo</h4>
            <div class="row form-group inline-form-custom-2">
              <div class="col-xs-12 col-sm-6 inner">
                <label>T&iacute;tulo</label>
                <?php echo Core::InsertElement('text','title','','form-control','placeholder="Ingrese un T&iacute;tulo" validateEmpty="Ingrese un t&iacute;tulo." validateFromFile='.PROCESS.'///El t&iacute;tulo ya existe///action:=validate///object:=CoreFoo"'); ?>
              </div>
              <div class="col-xs-12 col-sm-6 inner">
                <label for="">Asociar Perfiles</label>
                <div class="form-group" id="groups-wrapper">
                  <?php echo Core::InsertElement('multiple','profiles','','form-control chosenSelect','data-placeholder="Seleccione Perfiles"',Core::Select(CoreProfile::TABLE,CoreProfile::TABLE_ID.',title',"status<>'I' AND ".CoreProfile::TABLE_ID." >= ".$_SESSION[CoreProfile::TABLE_ID]." AND ".CoreOrganization::TABLE_ID." = ".$_SESSION[CoreOrganization::TABLE_ID])); ?>
                </div>
              </div>
              <div class="col-xs-12 col-sm-12 inner">
                <label for="">Asociar Usuarios</label>
                <div class="form-group" id="groups-wrapper">
                  <?php echo Core::InsertElement('multiple','users','','form-control chosenSelect','data-placeholder="Seleccione Usuarios"',Core::Select(CoreUser::TABLE,CoreUser::TABLE_ID.',user',"status='A' AND ".CoreOrganization::TABLE_ID." = ".$_SESSION[CoreOrganization::TABLE_ID])); ?>
                </div>
              </div>
              <div class="col-xs-12 col-sm-6 inner">
                <label for="">Im&aacute;gen</label>
                <div class="lineContainer txC">
                  <div class="flex-allCenter imgSelector">
                    <div class="imgSelectorInner">
                      <img src="<?php echo $Group->GetImg(); ?>" class="img-responsive MainImg animated">
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
                </div><!-- treeview-checkbox -->
              </div>
            </div><!-- inline-form -->
            <hr>
            <div class="txC">
              <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-plus"></i> Crear Grupo</button>
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
