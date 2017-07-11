<?php
    include("../../../core/resources/includes/inc.core.php");
    $ID           = $_GET['id'];
    $Edit         = new Brand($ID);
    $Data         = $Edit->GetData();
    Core::ValidateID($Data);
    $Head->SetTitle("Modificar Marca ".$Data['title']);
     
    $Head->setHead();
    
    include('../../../project/resources/includes/inc.top.php');
    
?>
  <?php echo Core::InsertElement("hidden","action",'update'); ?>
  <?php echo Core::InsertElement("hidden","id",$ID); ?>
  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-lg-8 col-sm-12">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-plus-circle"></i> Complete los campos para modificar la marca</h4>
            <div class="row form-group inline-form-custom-2">
              <div class="col-xs-12 col-sm-6 inner">
                <label>Nombre</label>
                <?php echo Core::InsertElement('text','name',$Data['name'],'form-control','placeholder="Ingrese un Nombre" validateEmpty="Ingrese un nombre." validateFromFile='.PROCESS.'///El nombre ya existe///action:=validate///actualname:='.$Data['name'].'///object:=Brand"'); ?>
              </div>
              <div class="col-xs-12 col-sm-6 inner">
                <label>Origen</label>
                <?php echo Core::InsertElement('select','country',$Data['country_id'],'form-control chosenSelect','data-placeholder="Seleccione un pa&iacute;s"',Core::Select('admin_country','country_id,title',"status<>'I'"),' ',''); ?>
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

include('../../../project/resources/includes/inc.bottom.php');
?>
