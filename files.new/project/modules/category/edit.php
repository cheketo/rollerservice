<?php
    include("../../../core/resources/includes/inc.core.php");
    $ID           = $_GET['id'];
    $Edit         = new Category($ID);
    $Data         = $Edit->GetData();
    Core::ValidateID($Data);
    $Edit->Data = Utf8EncodeArray($Edit->Data);
    $Head->SetTitle("Modificar L&iacute;nea ".$Data['title']);
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->setHead();
    
    include('../../../project/resources/includes/inc.top.php');
    
?>
  <?php echo Core::InsertElement("hidden","action",'update'); ?>
  <?php echo Core::InsertElement("hidden","id",$ID); ?>
  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-lg-8 col-sm-12">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-plus-circle"></i> Complete los campos para modificar la l&iacute;nea</h4>
            
            <div class="row form-group inline-form-custom-2">
              <div class="col-xs-12 inner">
                <label>Nombre</label>
                <?php echo Core::InsertElement('text','title',$Data['title'],'form-control','placeholder="Ingrese un Nombre" validateEmpty="Ingrese un nombre." validateFromFile='.PROCESS.'///El nombre ya existe///action:=validate///parent///actualtitle:='.$Data['title'].'///object:=Category"'); ?>
              </div>
            </div><!-- inline-form -->
            <div class="row form-group inline-form-custom-2">
              <div class="col-xs-12 col-sm-6 inner">
                <label>Nombre Corto</label>
                <?php echo Core::InsertElement('text','short_title',$Data['short_title'],'form-control','placeholder="Ingrese un Nombre Corto" validateEmpty="Ingrese un nombre."'); ?>
              </div>
              <div class="col-xs-12 col-sm-6 inner">
                <label>Ubicaci&oacute;n</label>
                <?php echo Core::InsertElement('select','parent',$Data['parent_id'],'form-control chosenSelect','',Core::Select("product_category","category_id,title","status='A' AND organization_id=".$_SESSION['organization_id']),'0','L&iacute;nea Principal'); ?>
              </div>
            </div><!-- inline-form -->
            <hr>
            <div class="txC">
              <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-plus"></i> Modificar L&iacute;nea</button>
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
