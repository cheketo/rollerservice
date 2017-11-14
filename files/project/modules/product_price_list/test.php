<?php
    include("../../../core/resources/includes/inc.core.php");
    $Head->SetTitle($Menu->GetTitle().' Gen&eacute;rico');
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/autocomplete/jquery.auto-complete.css'); // Autocomplete
    $Head->SetStyle('../../../../vendors/datatables/jquery.dataTables_themeroller.css'); // DataTable
    //$Head->SetStyle('../../../../vendors/datatables/dataTables.bootstrap.css'); // DataTable
    $Head->setHead();
    
    $CompanyId = $_GET['id'];
    $Company = new Company($CompanyId);
    $Data = $Company->GetData();
    $Import = ProductRelation::GetLastImport($CompanyId);
    
    // $Category = new Category();
    include('../../../project/resources/includes/inc.top.php');
    
    // HIDDEN ELEMENTS
    echo Core::InsertElement("hidden","action",'relation');
    echo Core::InsertElement("hidden","id",$Import['import_id']);
?>
<div class="box box-info animated fadeIn" id="CodeBox">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-exchange"></i> C&oacute;digos Asociados</h3>
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <table id="table_import" class="display table table-bordered table-striped dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
              <th class="txC">Asociar</th>
              <th class="txC">C&oacute;digo Empresa</th>
              <th class="txC">Marca</th>
              <th class="txC">Precio</th>
              <th class="txC">Stock</th>
              <th class="txC">C&oacute;digo Roller</th>
              <th class="txC">C&oacute;digo Gen&eacute;rico</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
              <th class="txC">Asociar</th>
              <th class="txC">C&oacute;digo Empresa</th>
              <th class="txC">Marca</th>
              <th class="txC">Precio</th>
              <th class="txC">Stock</th>
              <th class="txC">C&oacute;digo Roller</th>
              <th class="txC">C&oacute;digo Gen&eacute;rico</th>
            </tr>
        </tfoot>
        <tbody>
          <?php
            $I=0;
            foreach($Import['items'] as $Item){
              $Item['stock'] = $Item['stock']>-1?$Item['stock']:'';
              $Item['price'] = $Item['price']==0?'':$Item['price'];
          ?>
          <tr class="txC">
            <td><?php echo Core::InsertElement('checkbox','relationstatus_'.$I,1,'iCheckbox','checked="checked"'); ?></td>
            <td><?php echo $Item['code']; ?></td>
            <td><?php echo $Item['brand']; ?></td>
            <td><?php echo Core::InsertElement('text','price_'.$I,$Item['price'],'txC form-controls','placeholder="Precio" style="max-width:100px;"'); ?></td>
            <!--<td><?php echo $Item['price']; ?></td>-->
            <td><?php echo Core::InsertElement('text','stock_'.$I,$Item['stock'],'txC form-controls','placeholder="Stock" style="max-width:100px;"'); ?></td>
            <!--<td><?php echo $Item['stock']; ?></td>-->
            <td><?php echo Core::InsertElement('autocomplete','coderoller_'.$I.$Item['item_id'],'','txC form-control','placeholder="Seleccionar C&oacute;digo" placeholderauto="C&oacute;digo no encontrada"','Product','SearchCodes'); ?></td>
            <td><?php echo Core::InsertElement('autocomplete','codeabstract'.$I,'','txC form-control','placeholder="Seleccionar C&oacute;digo" placeholderauto="C&oacute;digo no encontrada" iconauto="cube"','ProductAbstract','SearchCodes'); ?></td>
          </tr>
          <?php
            }
          ?>
        </tbody>
    </table>
  </div>
  <div class="box-footer">
    <button type="button" class="btn btn-error" id="BtnPrueba"><i class="fa fa-click"></i> Probar</button>
    <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-arrow-left"></i> Regresar</button>
    <button class="btn btn-sm btn-success" id="BtnPriceList"><i class="fa fa-clipboard"></i> Generar Lista de Precios</button>
  </div>

<?php
 // Bootstrap Select Input
$Foot->SetScript('../../../../vendors/autocomplete/jquery.auto-complete.min.js');
$Foot->SetScript('../../../../vendors/datatables/jquery.dataTables.min.js'); // DATATABLES
$Foot->SetScript('../../../../vendors/datatables/dataTables.bootstrap.min.js'); // DATATABLES
//$Foot->SetScript('../../../../vendors/jquery-mask/src/jquery.mask.js');
//$Foot->SetScript('../../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
include('../../../project/resources/includes/inc.bottom.php');
?>