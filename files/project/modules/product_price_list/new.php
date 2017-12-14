<?php
    include("../../../core/resources/includes/inc.core.php");
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/autocomplete/jquery.auto-complete.css'); // Autocomplete
    $Head->setHead();
    
    $Brands = Core::Select(Brand::TABLE,Brand::TABLE_ID.",name","status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],'name');
    
    if($_GET['id']>0)
    {
      $Relation = Core::Select(ProductRelation::TABLE,Product::TABLE_ID.",code",ProductRelation::TABLE_ID."=".$_GET['id'])[0];
      $ID = $_GET['id'];
      $Edit = new ProductRelation($ID);
      $Relation = $Edit->Data;
      
      $Company = new Company($Relation['company_id']);
      $Data = $Company->GetData();
      $SelectedCompany = $Relation['company_id'].",".$Relation['company'];
      
      $Abstract = $Relation['abstract_id'].','.$Relation['abstract_code'];
      
      $Product = $Relation['product_id'].','.$Relation['product_code'];
    }
    
    include('../../../project/resources/includes/inc.top.php');
    
    // HIDDEN ELEMENTS
    echo Core::InsertElement("hidden","action",'relation');
    // echo Core::InsertElement("hidden","updated",'');
    echo Core::InsertElement("hidden","relation",$ID);
?>
  <!-- ////////// SECOND SCREEN ////////////////// -->
  <div class="ProductDetails box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-md-6 ">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-exchange"></i> Detalles de la Relaci&oacute;n</h4>
            
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-12">
                Empresa:
                <?php echo Core::InsertElement('autocomplete','company_id',$SelectedCompany,'txC form-control','placeholder="Seleccionar Empresa" validateEmpty="Seleccione una Empresa" placeholderauto="Empresa no encontrada" iconauto="building"','Company','SearchCompanies'); ?>
              </div>
            </div>
            
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-12">
                C&oacute;digo:
                <?php echo Core::InsertElement('text','code',$Relation['code'],'form-control txC','validateEmpty="Ingrese un c&oacute;digo"') ?>
              </div>
            </div>
            
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-12">
                Marca:
                <?php echo Core::InsertElement('select','brand_id',$Relation[Brand::TABLE_ID],'txC form-control chosenSelect','data-placeholder="Seleccionar Marca" validateEmpty="Seleccione una marca"',$Brands,' ',''); ?>
              </div>
            </div>
            
              <div class="row form-group inline-form-custom">
                <div class="col-xs-12 col-sm-12">
                  Precio:
                  <?php echo Core::InsertElement('text','price',$Relation['price'],'txC form-control inputMask','placeholder="Sin Precio" data-inputmask="\'alias\': \'numeric\', \'groupSeparator\': \'\', \'autoGroup\': true, \'digits\': 2, \'digitsOptional\': true, \'prefix\': \'\', \'placeholder\': \'0\'"') ?>
                </div>
              </div>
              
              <div class="row form-group inline-form-custom">
                <div class="col-xs-12 col-sm-12">
                  Moneda:
                  <?php echo Core::InsertElement('select','currency_id',$Relation['currency_id'],'txC form-control chosenSelect','data-placeholder="Seleccionar Moneda" validateEmpty="Seleccione una Moneda"',Currency::GetSelectCurrency(),' ',''); ?>
                </div>
              </div>
              
              <div class="row form-group inline-form-custom">
                <div class="col-xs-12 col-sm-12">
                  Stock:
                  <?php echo Core::InsertElement('text','stock',$Relation['stock'],'txC form-control','placeholder="Sin Stock"') ?>
                </div>
              </div>
              
              <div class="row form-group inline-form-custom">
                <div class="col-xs-12 col-sm-12">
                  C&oacute;digo Gen&eacute;rico:
                  <?php echo Core::InsertElement('autocomplete','abstract_id',$Abstract,'txC form-control','placeholder="Seleccionar C&oacute;digo" placeholderauto="C&oacute;digo no encontrado"','ProductAbstract','SearchAbstractCodes') ?>
                </div>
              </div>
              
              <div class="row form-group inline-form-custom">
                <div class="col-xs-12 col-sm-12">
                  C&oacute;digo Roller:
                  <?php echo Core::InsertElement('autocomplete','product_id',$Product,'txC form-control','placeholder="Seleccionar C&oacute;digo" placeholderauto="C&oacute;digo no encontrado"','Product','SearchCodes') ?>
                </div>
              </div>
            
            <div class="txC">
              <button type="button" class="btn btn-success btnGreen" id="BtnRelation"><i class="fa fa-check"></i> Guardar</button>
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
$Foot->SetScript('../../../../vendors/autocomplete/jquery.auto-complete.min.js');
$Foot->SetScript('../../../../vendors/jquery-mask/src/jquery.mask.js');
$Foot->SetScript('../../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
include('../../../project/resources/includes/inc.bottom.php');
?>