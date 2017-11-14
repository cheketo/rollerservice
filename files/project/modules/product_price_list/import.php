<?php
    include("../../../core/resources/includes/inc.core.php");
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/autocomplete/jquery.auto-complete.css'); // Autocomplete
    $Head->setHead();
    
    $Category = new Category();
    include('../../../project/resources/includes/inc.top.php');
    
    // HIDDEN ELEMENTS
    echo Core::InsertElement("hidden","action",'import');

?>
  <div class="box box-success animated fadeIn" id="CodeBox">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-hdd-o"></i> Importar Lista de Precios</h3>
      <div class="box-tools pull-right">
        <a href="../../../../skin/files/price_list/template/plantilla_importacion.xlsx" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-download"></i> Descargar Plantilla de Importaci&oacute;n</a>
        </button>
      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <div class="col-xs-12 col-sm-5 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
          <?php
            echo Core::InsertElement('autocomplete','id','','txC form-control','placeholder="Seleccionar Empresa" validateEmpty="Seleccione una Empresa" placeholderauto="Empresa no encontrada" iconauto="building"','Company','SearchCompanies');
          ?>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-xs-12 col-sm-5 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
          <?php
            echo Core::InsertElement('file','price_list','','form-control txC','placeholder="Seleccionar Archivo" validateEmpty="Seleccione un Archivo"','.xls,.xlsx,.csv');
          ?>
        </div>
      </div>
    </div>
    <div class="box-footer txC">
        <button class="btn btn-sm btn-success" id="BtnImport">Continuar <i class="fa fa-arrow-right"></i></button>
    </div>
  </div>

  <!-- Help Modal -->
  
  <?php //ProductRelation::ReadImportedFile(); ?>
  
<?php
 // Bootstrap Select Input
$Foot->SetScript('../../../../vendors/autocomplete/jquery.auto-complete.min.js');
$Foot->SetScript('../../../../vendors/jquery-mask/src/jquery.mask.js');
$Foot->SetScript('../../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
include('../../../project/resources/includes/inc.bottom.php');
?>