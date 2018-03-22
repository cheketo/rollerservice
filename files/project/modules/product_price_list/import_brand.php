<?php
    include("../../../core/resources/includes/inc.core.php");
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/autocomplete/jquery.auto-complete.css'); // Autocomplete
    $Head->setHead();
    
    $CompanyId = $_GET['id'];
    $ProviderImports= Core::Select('product_relation_import',"*",Company::TABLE_ID."=".$CompanyId." AND status<>'A'","creation_date DESC");
    if($_GET['import_id'])
    {
      $ImportID = $_GET['import_id'];
    }else{
      $ImportID = Core::Select('product_relation_import',"*",Company::TABLE_ID."=".$CompanyId." AND status='A'","creation_date DESC")[0]['import_id'];
    }
    
    
    $Brands = Core::Select(Brand::TABLE,Brand::TABLE_ID.",name","status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],'name');
    $ProviderBrands = Core::Select('product_relation_brand',"*",Company::TABLE_ID."=".$CompanyId." AND status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],'brand_id');
    
    include('../../../project/resources/includes/inc.top.php');
    
    // HIDDEN ELEMENTS
    echo Core::InsertElement("hidden","action",'importbrand');
    echo Core::InsertElement("hidden","id",$CompanyId);
    echo Core::InsertElement("hidden","importid",$ImportID);

?>
  <div class="box box-success animated fadeIn" id="BrandBox">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-trademark"></i> Marcas Relacionadas con el Proveedor</h3>
    </div>
    <div class="box-body">
      <?php foreach ($ProviderBrands as $ProviderBrand){ ?>
      <div class="row">
        <div class="col-xs-5 col-sm-3 text-right">
        </div>
        <div class="col-xs-5 col-sm-2 text-right">
          <?php
            echo $ProviderBrand['name']
            // echo Core::InsertElement('text','provider_brand'.$ProviderBrand[Brand::TABLE_ID],$ProviderBrand['name'],'txC form-control','placeholder="Marca del Proveedor" validateEmpty="Ingrese el nombre de al Marca del proveedor"');
          ?>
          <i class="fa fa-arrow-right"></i>
        </div>
        <div class="col-xs-5 col-sm-4 text-left">
          <?php
            echo Core::InsertElement('select','roller_brand'.$ProviderBrand[Brand::TABLE_ID],$ProviderBrand['product_brand_id'],'txC form-control chosenSelect','data-placeholder="Seleccionar Marca Roller"',$Brands,' ','');
          ?>
        </div>
      </div>
      <hr>
      <?php } ?>
    </div>
    <div class="box-footer txC">
      <button type="button" class="btn btn-error btnRed" id="BtnCancel" name="BtnCancel"><i class="fa fa-arrow-left"></i> Regresar</button>
        <button class="btn btn-success" id="BtnImportBrand">Continuar <i class="fa fa-arrow-right"></i></button>
    </div>
  </div>
  <hr>
  <div class="box box-default animated fadeIn" id="ImportBox">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-file-o"></i> Importaciones Anteriores del Proveedor</h3>
    </div>
    <div class="box-body">
      <?php foreach ($ProviderImports as $Import){ ?>
      <div class="row">
        <div class="col-xs-12 col-sm-3">
          <?php
            echo $Import['creation_date'];
          ?>
        </div>
        <div class="col-xs-12 col-sm-3">
          <?php
            echo $Import['list_date'];
          ?>
        </div>
        <div class="col-xs-12 col-sm-3">
          <?php
            echo $Import['currency_id'];
          ?>
        </div>
      </div>  
      <?php } ?>
    </div>
  </div>
  
  
  
  
<?php
 // Bootstrap Select Input
$Foot->SetScript('../../../../vendors/autocomplete/jquery.auto-complete.min.js');
$Foot->SetScript('../../../../vendors/jquery-mask/src/jquery.mask.js');
$Foot->SetScript('../../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
$Foot->SetScript('../../../../vendors/datepicker/bootstrap-datepicker.js');
include('../../../project/resources/includes/inc.bottom.php');
?>