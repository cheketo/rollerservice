<?php
    include("../../includes/inc.main.php");
    $ID           = $_GET['id'];
    $Edit         = new Brand($ID);
    $Data         = $Edit->GetData();
    ValidateID($Data);
    $Edit->Data = Utf8EncodeArray($Edit->Data);
    $Head->setTitle("Modificar Marca ".$Data['title']);
     
    $Head->setHead();
    
    include('../../includes/inc.top.php');
    
?>
  <?php echo insertElement("hidden","action",'update'); ?>
  <?php echo insertElement("hidden","id",$ID); ?>
  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-lg-8 col-sm-12">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-plus-circle"></i> Complete los campos para modificar la marca</h4>
            <div class="row form-group inline-form-custom-2">
              <div class="col-xs-12 col-sm-6 inner">
                <label>Nombre</label>
                <?php echo insertElement('text','name',$Data['name'],'form-control','placeholder="Ingrese un Nombre" validateEmpty="Ingrese un nombre." validateFromFile="../../library/processes/proc.common.php///El nombre ya existe///action:=validate///actualname:='.$Data['name'].'///object:=Brand"'); ?>
              </div>
              <div class="col-xs-12 col-sm-6 inner">
                <label>Origen</label>
                <?php echo insertElement('select','country',$Data['country_id'],'form-control chosenSelect','data-placeholder="Seleccione un pa&iacute;s"',$DB->fetchAssoc('admin_country','country_id,title',"status<>'I'"),' ',''); ?>
              </div>
            </div><!-- inline-form -->
            <hr>
            <div class="txC">
              <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-plus"></i> Modificar Marca</button>
              <button type="button" class="btn btn-success btnBlue" id="BtnCreateNext"><i class="fa fa-plus"></i> Modificar y Agregar Otra</button>
              <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
            </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Help Modal Trigger -->
  <?php //include ('modal.icon.php'); ?>
  <!-- //// HELP MODAL //// -->
  <!-- Help Modal -->
<?php

include('../../includes/inc.bottom.php');
?>
