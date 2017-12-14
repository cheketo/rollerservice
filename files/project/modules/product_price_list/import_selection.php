<?php
    include("../../../core/resources/includes/inc.core.php");
    $Head->SetTitle($Menu->GetTitle().' Gen&eacute;rico');
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/autocomplete/jquery.auto-complete.css'); // Autocomplete
    $Head->SetStyle('../../../../vendors/datatables/jquery.dataTables_themeroller.css'); // DataTable
    $Head->SetStyle('../../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    $Head->setHead();
    
    $CompanyId = $_GET['id'];
    $Company = new Company($CompanyId);
    $Data = $Company->GetData();
    $Import = ProductRelation::GetLastImport($CompanyId);
    $Relation = new ProductRelationItem();
    $Relation->SetImportID($Import['import_id']);
    
    $Brands = Core::Select(Brand::TABLE,Brand::TABLE_ID.",name","status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],'name');
    
    // $Category = new Category();
    include('../../../project/resources/includes/inc.top.php');
    
    // HIDDEN ELEMENTS
    echo Core::InsertElement("hidden","action",'relation');
    echo Core::InsertElement("hidden","id",$Import['import_id']);
?>
  <div class="box box-success animated fadeIn" id="CodeBox">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-book"></i> Generar Lista de Precios</h3>
      <!--<div class="box-tools pull-right">-->
      <!--  <a href="../../../../skin/files/price_list/template/plantilla_importacion.xlsx" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-download"></i> Descargar Plantilla de Importaci&oacute;n</a>-->
      <!--  </button>-->
      <!--</div>-->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <form id="PriceList">
        <div class="row">
          <div class="col-xs-12 col-sm-4 col-sm-offset-1 col-md-4 col-md-offset-1 col-lg-4 col-lg-offset-1">
            Empresa:
          </div>
          
          <div class="col-xs-12 col-sm-4 col-sm-offset-1 col-md-4 col-md-offset-1 col-lg-4 col-lg-offset-1">
            Moneda:
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-4 col-sm-offset-1 col-md-4 col-md-offset-1 col-lg-4 col-lg-offset-1">
            <?php
              echo Core::InsertElement('autocomplete','company_id',$CompanyId.",".$Data['name'],'txC form-control','placeholder="Seleccionar Empresa" disabled="disabled" validateEmpty="Seleccione una Empresa" placeholderauto="Empresa no encontrada" iconauto="building"','Company','SearchCompanies');
            ?>
          </div>
          
          <div class="col-xs-12 col-sm-4 col-sm-offset-1 col-md-4 col-md-offset-1 col-lg-4 col-lg-offset-1">
            <?php
              echo Core::InsertElement('select','currency','','txC form-control chosenSelect','data-placeholder="Seleccionar Moneda" validateEmpty="Seleccione una Moneda"',Currency::GetSelectCurrency(),' ','');
            ?>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-4 col-sm-offset-1 col-md-4 col-md-offset-1 col-lg-4 col-lg-offset-1">
            Marca:
          </div>
          <div class="col-xs-12 col-sm-4 col-sm-offset-1 col-md-4 col-md-offset-1 col-lg-4 col-lg-offset-1">
            Fecha del Listado:
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-4 col-sm-offset-1 col-md-4 col-md-offset-1 col-lg-4 col-lg-offset-1">
            <?php
              echo Core::InsertElement('select','brand_import',$Import['brand_id'],'txC form-control chosenSelect','disabled="disabled"',$Brands,'0','Sin Marca');
            ?>
          </div>  
          <div class="col-xs-12 col-sm-4 col-sm-offset-1 col-md-4 col-md-offset-1 col-lg-4 col-lg-offset-1">
            <?php
              echo Core::InsertElement('text','date',Core::FromDBToDate($Import['list_date']),'form-control txC datePicker','placeholder="Fecha del Listado" validateEmpty="Ingrese una fecha"');
            ?>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-4 col-sm-offset-1 col-md-4 col-md-offset-1 col-lg-4 col-lg-offset-1">
            Notas y Comentarios:
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-9 col-sm-offset-1">
            <?php
              echo Core::InsertElement('textarea','description',$Import['description'],'form-control','placeholder=""');
            ?>
          </div>
        </div>
      </form>
      <br>
      
      <?php echo $Relation->InsertSearchList(); ?>
      <!--<table class="table table-striped Hidden">-->
      <!--  <tbody class="txC">-->
      <!--    <tr>-->
            <!--<th style="width: 10px">#</th>-->
      <!--      <th class="txC">C&oacute;digo Empresa</th>-->
      <!--      <th class="txC">Marca</th>-->
      <!--      <th class="txC">Precio</th>-->
      <!--      <th class="txC">Stock</th>-->
      <!--      <th class="txC">C&oacute;digo Roller</th>-->
      <!--      <th class="txC">C&oacute;digo Gen&eacute;rico</th>-->
      <!--    </tr>-->
      <!--    <?php-->
      <!--      foreach($Import['items'] as $Item){-->
      <!--        $Item['stock'] = $Item['stock']>-1?$Item['stock']:'';-->
      <!--        $Item['price'] = $Item['price']==0?'':$Item['price'];-->
      <!--    ?>-->
      <!--    <tr>-->
      <!--      <td><?php echo $Item['code']; ?></td>-->
      <!--      <td><?php echo $Item['brand']; ?></td>-->
      <!--      <td><?php echo Core::InsertElement('text','price',$Item['price'],'txC form-controls','placeholder="Precio"'); ?></td>-->
            <!--<td><?php echo $Item['price']; ?></td>-->
      <!--      <td><?php echo Core::InsertElement('text','stock',$Item['stock'],'txC form-controls','placeholder="Stock"'); ?></td>-->
            <!--<td><?php echo $Item['stock']; ?></td>-->
      <!--      <td><?php echo Core::InsertElement('autocomplete','code_roller'.$Item['item_id'],'','txC form-control','placeholder="Seleccionar C&oacute;digo" placeholderauto="C&oacute;digo no encontrada"','Product','SearchCodes'); ?></td>-->
      <!--      <td><?php echo Core::InsertElement('autocomplete','code_roller2','','txC form-control','placeholder="Seleccionar C&oacute;digo" placeholderauto="C&oacute;digo no encontrada" iconauto="cube"','ProductAbstract','SearchCodes'); ?></td>-->
      <!--    </tr>-->
      <!--    <?php-->
      <!--      }-->
      <!--    ?>-->
      <!--  </tbody>-->
      <!--</table>-->
      
    <!--  <table id="table_import" class="display table table-bordered table-striped dataTable" width="100%" cellspacing="0">-->
    <!--    <thead>-->
    <!--        <tr>-->
    <!--          <th class="txC">Asociar</th>-->
    <!--          <th class="txC">C&oacute;digo Empresa</th>-->
    <!--          <th class="txC">Marca</th>-->
    <!--          <th class="txC">Precio</th>-->
    <!--          <th class="txC">Stock</th>-->
    <!--          <th class="txC">C&oacute;digo Roller</th>-->
    <!--          <th class="txC">C&oacute;digo Gen&eacute;rico</th>-->
    <!--        </tr>-->
    <!--    </thead>-->
    <!--    <tfoot>-->
    <!--        <tr>-->
    <!--          <th class="txC">Asociar</th>-->
    <!--          <th class="txC">C&oacute;digo Empresa</th>-->
    <!--          <th class="txC">Marca</th>-->
    <!--          <th class="txC">Precio</th>-->
    <!--          <th class="txC">Stock</th>-->
    <!--          <th class="txC">C&oacute;digo Roller</th>-->
    <!--          <th class="txC">C&oacute;digo Gen&eacute;rico</th>-->
    <!--        </tr>-->
    <!--    </tfoot>-->
    <!--    <tbody>-->
    <!--      <?php-->
    <!--        $I=0;-->
    <!--        foreach($Import['items'] as $Item){-->
    <!--          $Item['stock'] = $Item['stock']>-1?$Item['stock']:'';-->
    <!--          $Item['price'] = $Item['price']==0?'':$Item['price'];-->
    <!--      ?>-->
    <!--      <tr class="txC">-->
    <!--        <td><?php echo Core::InsertElement('checkbox','relationstatus_'.$I,1,'iCheckbox','checked="checked"'); ?></td>-->
    <!--        <td><?php echo $Item['code']; ?></td>-->
    <!--        <td><?php echo $Item['brand']; ?></td>-->
    <!--        <td><?php echo Core::InsertElement('text','price_'.$I,$Item['price'],'txC form-controls','placeholder="Sin Precio" style="max-width:100px;"'); ?></td>-->
            <!--<td><?php echo $Item['price']; ?></td>-->
    <!--        <td><?php echo Core::InsertElement('text','stock_'.$I,$Item['stock'],'txC form-controls','placeholder="Sin Stock" style="max-width:100px;"'); ?></td>-->
            <!--<td><?php echo $Item['stock']; ?></td>-->
    <!--        <td><?php echo Core::InsertElement('autocomplete','coderoller_'.$I.$Item['item_id'],'','txC form-control','placeholder="Seleccionar C&oacute;digo" placeholderauto="C&oacute;digo no encontrada"','Product','SearchCodes'); ?></td>-->
    <!--        <td><?php echo Core::InsertElement('autocomplete','codeabstract'.$I,'','txC form-control','placeholder="Seleccionar C&oacute;digo" placeholderauto="C&oacute;digo no encontrada" iconauto="cube"','ProductAbstract','SearchCodes'); ?></td>-->
    <!--      </tr>-->
    <!--      <?php-->
    <!--        }-->
    <!--      ?>-->
    <!--    </tbody>-->
    <!--</table>-->
    </div>
    <div class="box-footer txC">
        <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-arrow-left"></i> Regresar</button>
        <button class="btn btn-sm btn-success" id="BtnPriceList"><i class="fa fa-clipboard"></i> Generar Lista de Precios</button>
    </div>
  </div>
<?php
 // Bootstrap Select Input
$Foot->SetScript('../../../../vendors/autocomplete/jquery.auto-complete.min.js');
$Foot->SetScript('../../../../vendors/datepicker/bootstrap-datepicker.js');
$Foot->SetScript('../../../../vendors/jquery-mask/src/jquery.mask.js');
$Foot->SetScript('../../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
$Foot->SetScript('../../../../vendors/datatables/jquery.dataTables.min.js'); // DATATABLES
$Foot->SetScript('../../../../vendors/datatables/dataTables.bootstrap.min.js'); // DATATABLES
$Foot->SetScript('../../../core/resources/js/script.core.searchlist.js');
include('../../../project/resources/includes/inc.bottom.php');
?>