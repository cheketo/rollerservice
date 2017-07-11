<?php
    include("../../includes/inc.main.php");
    //$Head->setTitle("Nuevo Usuario");
    $Head->setTitle($Menu->GetTitle());
    $Head->setIcon($Menu->GetHTMLicon());
    $Head->setHead();
    include('../../includes/inc.top.php');
?>
  <?php echo insertElement("hidden","action",'insert'); ?>
  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-lg-8 col-sm-12">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-plus-circle"></i> Complete los campos para agregar una nueva marca</h4>
            <div class="row form-group inline-form-custom-2">
              <div class="col-xs-12 col-sm-6 inner">
                <label>Nombre</label>
                <?php echo insertElement('text','name','','form-control','placeholder="Ingrese un Nombre" validateEmpty="Ingrese un nombre." validateFromFile="../../library/processes/proc.common.php///El nombre ya existe///action:=validate///object:=Brand" autofocus'); ?>
              </div>
              <div class="col-xs-12 col-sm-6 inner">
                <label>Origen</label>
                <?php echo insertElement('select','country','','form-control chosenSelect','data-placeholder="Seleccione un pa&iacute;s"',$DB->fetchAssoc('admin_country','country_id,title',"status<>'I'"),' ',''); ?>
              </div>
            </div><!-- inline-form -->
            <hr>
            <div class="txC">
              <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-plus"></i> Crear Marca</button>
              <button type="button" class="btn btn-success btnBlue" id="BtnCreateNext"><i class="fa fa-plus"></i> Crear y Agregar Otra</button>
              <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
            </div>
        </div>
      </div>
    </div>
  </div>
<?php
  
  include('../../includes/inc.bottom.php');
?>
