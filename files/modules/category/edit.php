<?php
    include("../../includes/inc.main.php");
    $ID           = $_GET['id'];
    $Edit         = new Category($ID);
    $Data         = $Edit->GetData();
    ValidateID($Data);
    $Edit->Data = Utf8EncodeArray($Edit->Data);
    $Head->setTitle("Modificar L&iacute;nea ".$Data['title']);
    $Head->setStyle('../../../vendors/select2/select2.min.css'); // Select Inputs With Tags
    $Head->setHead();
    
    include('../../includes/inc.top.php');
    
?>
  <?php echo insertElement("hidden","action",'update'); ?>
  <?php echo insertElement("hidden","parent",$Data['parent_id']); ?>
  <?php echo insertElement("hidden","id",$ID); ?>
  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-lg-8 col-sm-12">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-plus-circle"></i> Complete los campos para modificar la l&iacute;nea</h4>
            
            <div class="row form-group inline-form-custom-2">
              <div class="col-xs-12 inner">
                <label>Nombre</label>
                <?php echo insertElement('text','title',$Data['title'],'form-control','placeholder="Ingrese un Nombre" validateEmpty="Ingrese un nombre." validateFromFile="../../library/processes/proc.common.php///El nombre ya existe///action:=validate///actualtitle:='.utf8_encode($Data['title']).'///object:=Category"'); ?>
              </div>
            </div><!-- inline-form -->
            <div class="row form-group inline-form-custom-2">
              <div class="col-xs-12 col-sm-6 inner">
                <label>Nombre Corto</label>
                <?php echo insertElement('text','short_title',$Data['short_title'],'form-control','placeholder="Ingrese un Nombre Corto" validateEmpty="Ingrese un nombre."'); ?>
              </div>
              <div class="col-xs-12 col-sm-6 inner">
                <label>Ubicaci&oacute;n</label>
                <?php echo insertElement('select','parent_select',$Data['parent_id'],'form-control select2 selectTags',' style="width: 100%;height:auto!important;"',Utf8EncodeArray($DB->fetchAssoc("product_category","category_id,title","status='A' AND company_id=".$_SESSION['company_id'])),'0','L&iacute;nea Principal'); ?>
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
$Foot->setScript('../../../vendors/select2/select2.min.js');
include('../../includes/inc.bottom.php');
?>
