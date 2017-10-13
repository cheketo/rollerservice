<?php
    include("../../../core/resources/includes/inc.core.php");
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/bootstrap-switch/bootstrap-switch.css'); // Switch On Off
    $Head->setHead();
    
    if($_GET['id']>0)
    {
      $Relation = Core::Select(ProductRelation::TABLE,Product::TABLE_ID.",code",ProductRelation::TABLE_ID."=".$_GET['id'])[0];
    }
    
    include('../../../project/resources/includes/inc.top.php');
    
    // HIDDEN ELEMENTS
    echo Core::InsertElement("hidden","action",'relation');
    echo Core::InsertElement("hidden","updated",'');
    echo Core::InsertElement("hidden","relation",'');
?>
  <!-- ////////// SECOND SCREEN ////////////////// -->
  <div class="ProductDetails box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-md-6 ">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-exchange"></i> Detalles de la Relaci&oacute;n</h4>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-12">
                Art&iacute;culo:
                <?php echo Core::InsertElement('select',Product::TABLE_ID,$Relation[Product::TABLE_ID],'form-control chosenSelect','data-placeholder="Seleccione un Art&iacute;culo" validateEmpty="Seleccione un Art&iacute;culo"',Product::GetFullCodes(),' ') ?>
              </div>
            </div>
            <!--<div class="form-group Hidden" id="product_detail">-->
            <!--  L&iacute;nea: <b>xxxx</b><br>-->
            <!--  Precio: <b>xxxx</b>-->
            <!--</div>-->
            <!--<div class="form-group">-->
            <!--  <?php //echo Core::InsertElement('text','title','','form-control','placeholder="Nombre del Art&iacute;culo"') ?>-->
            <!--</div>-->
            <div id="product_relation" class="">
              <!--<div class="row form-group inline-form-custom">-->
              <!--  <div class="col-xs-12 col-sm-12">-->
              <!--    Empresa:-->
              <!--    <?php //echo Core::InsertElement('select',Brand::TABLE_ID,$_GET[Brand::TABLE_ID],'form-control chosenSelect','data-placeholder="Seleccione una Marca"',Core::Select(Brand::TABLE,Brand::TABLE_ID.',name',"status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],"name"),'','Sin Marca') ?>-->
              <!--  </div>-->
              <!--</div>-->
              <div class="row form-group inline-form-custom">
                <div class="col-xs-12 col-sm-12">
                  C&oacute;digo de Relaci&oacute;n:
                  <?php echo Core::InsertElement('text','code',$Relation['code'],'form-control','placeholder="C&oacute;digo" validateEmpty="Ingrese un c&oacute;digo."') ?>
                </div>
              </div>
            </div>
            <div class="txC">
              <button type="button" class="btn btn-success btnGreen" id="RelationBtn"><i class="fa fa-check"></i> Guardar</button>
              <button type="button" class="btn btn-success btnBlue" id="RelationNext"><i class="fa fa-plus"></i> Guardar y Crear Otra</button>
              <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-arrow-left"></i> Regresar</button>
            </div>
        </div>
        <!-- Description (Character Counter) -->
      </div>
    </div><!-- box -->
  </div><!-- box -->

  <!-- //////////////// END SECOND SCREEN /////////////// -->

  <!-- Help Modal -->
<?php
 // Bootstrap Select Input
$Foot->SetScript('../../../../vendors/bootstrap-switch/script.bootstrap-switch.min.js');
$Foot->SetScript('../../../../vendors/jquery-mask/src/jquery.mask.js');
$Foot->SetScript('../../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
include('../../../project/resources/includes/inc.bottom.php');
?>