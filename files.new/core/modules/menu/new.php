<?php
    include("../../../core/resources/includes/inc.core.php");
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetStyle('../../../../vendors/bootstrap-switch/bootstrap-switch.css'); // Switch On Off
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->setHead();
    include('../../../project/resources/includes/inc.top.php');
?>
  <?php echo Core::InsertElement("hidden","action",'insert'); ?>
  <?php echo Core::InsertElement("hidden","groups",""); ?>
  <?php echo Core::InsertElement("hidden","profiles",""); ?>
  <?php echo Core::InsertElement("hidden","icon","fa-bars"); ?>
  <div class="ProductDetails box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-lg-8 col-sm-12">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-plus-circle"></i> Complete los campos para agregar un nuevo men&uacute;</h4>
          
            <div class="row form-group inline-form-custom-2">
              <div class="col-xs-12 col-sm-4 inner">
                <label>T&iacute;tulo</label>
                <?php echo Core::InsertElement('text','title','','form-control','placeholder="Ingrese un T&iacute;tulo" validateEmpty="Ingrese un t&iacute;tulo."'); ?>
              </div>
              <div class="col-xs-12 col-sm-4 inner">
                <label for="">Ubicaci&oacute;n</label>
                <?php echo Core::InsertElement('select','parent','','form-control chosenSelect','',Core::Select(CoreMenu::TABLE." a LEFT JOIN ".CoreMenu::TABLE." b ON ((a.parent_id=b.".CoreMenu::TABLE_ID." OR b.menu_id=0) AND a.link<>'#')","a.".CoreMenu::TABLE_ID.",COALESCE(CONCAT(b.title,'/',a.title), a.title) AS title","a.status<>'I' AND a.".CoreOrganization::TABLE_ID." IN (0,".$_SESSION[CoreOrganization::TABLE_ID].")","a.parent_id"),'0','Men&uacute; Principal'); ?>
                
              </div>
              <div class="col-xs-12 col-sm-4 inner">
                <label>Link</label>
                <?php echo Core::InsertElement('text','link','','form-control','placeholder="Ingrese una Ruta"'); ?>
              </div>
              <div class="col-xs-12 col-sm-4 inner">
                <label for="">Perfiles</label>
                <div class="form-group" id="groups-wrapper">
                  <?php echo Core::InsertElement('select','profiles','','form-control chosenSelect','multiple="multiple" data-placeholder="Seleccione Perfiles" style="width: 100%;"',Core::Select(CoreProfile::TABLE,CoreProfile::TABLE_ID.',title',"status<>'I' AND ".CoreOrganization::TABLE_ID." = ".$_SESSION[CoreOrganization::TABLE_ID])); ?>
                </div>
              </div>
              <div class="col-xs-12 col-sm-4 inner">
                <label for="">Grupos</label>
                <div class="form-group" id="groups-wrapper">
                  <?php echo Core::InsertElement('select','groups','','form-control chosenSelect','multiple="multiple" data-placeholder="Seleccione Grupos"',Core::Select(CoreGroup::TABLE,CoreGroup::TABLE_ID.',title',"status<>'I' AND ".CoreOrganization::TABLE_ID." = ".$_SESSION[CoreOrganization::TABLE_ID])); ?>
                </div>
              </div>
              <div class="col-xs-12 col-sm-4 inner">
                <label for="">Posici&oacute;n  </label><br>
                <?php echo Core::InsertElement('text','position','','form-control','placeholder="Ingrese un n&uacute;mero" validateOnlyNumbers="Ingrese un n&uacute;mero."'); ?>
              </div>
              <div class="col-md-12 padL0">
                <div class="col-xs-12 col-sm-4 inner">
                  <label for="">Privacidad</label><br>
                  <?php echo Core::InsertElement('checkbox','public','','','data-on-text="Privado" data-off-text="P&uacute;blico" data-size="mini" checked'); ?>
                </div>
                <div class="col-xs-12 col-sm-4 inner">
                  <label for="">Visibilidad</label><br>
                  <?php echo Core::InsertElement('checkbox','status','','','data-on-text="Visible" data-off-text="Oculto" data-size="mini" checked'); ?>
                </div>
                <div class="col-xs-12 col-sm-4 inner">
                  <label for="">Icono</label>
                  <div class="input-group">
                    <span class="input-group-addon cursor-pointer IconInput"><i class="fa fa-bars"></i></span>
                    <!--<input class="IconInput form-control cursor-pointer" placeholder="Seleccione un &iacute;cono" type="text">-->
                  </div>
                </div>
              </div>
            </div><!-- inline-form -->
            <hr>
            <div class="txC">
              <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-plus"></i> Crear Nuevo Men&uacute;</button>
              <button type="button" class="btn btn-success btnBlue" id="BtnCreateNext"><i class="fa fa-plus"></i> Crear y Agregar Otro</button>
              <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
            </div>
          
        </div>
      </div>
    </div>
  </div>
  
<?php include ('modal.icon.php'); ?>
<?php
$Foot->SetScript('../../../../vendors/bootstrap-switch/script.bootstrap-switch.min.js');

include('../../../project/resources/includes/inc.bottom.php');
?>
