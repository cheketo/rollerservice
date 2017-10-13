<?php
    include("../../../core/resources/includes/inc.core.php");
    //$Head->SetTitle("Nuevo Usuario");
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->setHead();
    include('../../../project/resources/includes/inc.top.php');
?>
  <?php echo Core::InsertElement("hidden","action",'insert'); ?>
  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-lg-8 col-sm-12">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-plus-circle"></i> Complete los campos para agregar un nuevo pa&iacute;s</h4>
            <div class="row form-group inline-form-custom-2">
              <div class="col-xs-12 col-sm-6 inner">
                <label>T&iacute;tulo</label>
                <?php echo Core::InsertElement('text','title','','form-control','placeholder="Ingrese un T&iacute;tulo" validateEmpty="Ingrese un t&iacute;tulo." validateFromFile='.PROCESS.'///El t&iacute;tulo ya existe///action:=validate///object:=GeolocationCountry"'); ?>
              </div>
            </div><!-- inline-form -->
            <hr>
            <div class="txC">
              <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-plus"></i> Crear Pa&iacute;s</button>
              <button type="button" class="btn btn-success btnBlue" id="BtnCreateNext"><i class="fa fa-plus"></i> Crear y Agregar Otro</button>
              <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
            </div>
        </div>
      </div>
    </div>
  </div>
<?php
include('../../../project/resources/includes/inc.bottom.php');
?>
