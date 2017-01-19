<?php
    include("../../includes/inc.main.php");
    //$Head->setTitle("Nuevo Usuario");
    $Head->setTitle($Menu->GetTitle());
    $Head->setStyle('../../../vendors/select2/select2.min.css'); // Select Inputs With Tags
    $Head->setHead();
    include('../../includes/inc.top.php');
?>
  <?php echo insertElement("hidden","action",'insert'); ?>
  <?php echo insertElement("hidden","country"); ?>
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
                <?php echo insertElement('select','country_select','','form-control select2 selectTags',' style="width: 100%;height:auto!important;"',$DB->fetchAssoc('admin_country','country_id,title',"status<>'I'"),'0','Seleccione un pa&iacute;s'); ?>
                <!--<select id="country" class="form-control select2 selectTags select2-hidden-accessible" ></select>-->
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
  $Foot->setScript('../../../vendors/select2/select2.min.js');
  include('../../includes/inc.bottom.php');
?>
