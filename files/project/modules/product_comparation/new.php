<?php
    include("../../../core/resources/includes/inc.core.php");
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/autocomplete/jquery.auto-complete.css'); // Autocomplete
    // $Head->SetStyle('../../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    $Head->setHead();
    
    // $Brands = Core::Select(Brand::TABLE,Brand::TABLE_ID.",name","status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],'name');
    $Brands = Core::Select(ProductRelation::SEARCH_TABLE,"brand_id,brand","abstract_id>0","brand","brand_id");
    
    $Companies = Core::Select(ProductRelation::SEARCH_TABLE,"company_id,company","abstract_id>0","company","company_id");
    
    include('../../../project/resources/includes/inc.top.php');
    
    // HIDDEN ELEMENTS
    echo Core::InsertElement("hidden","action",'compare');
    // echo Core::InsertElement("hidden","updated",'');
    echo Core::InsertElement("hidden","relation",$ID);
?>
  <!-- ////////// SECOND SCREEN ////////////////// -->
  <div class="ProductDetails box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-md-6 ">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-braille"></i> Detalles de la Comparaci&oacute;n</h4>
            
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-12">
                Proveedores con lista de precio:
                <?php //echo Core::InsertElement('autocomplete','company_id',$SelectedCompany,'txC form-control','placeholder="Seleccionar Empresa" validateEmpty="Seleccione una Empresa" placeholderauto="Empresa no encontrada" iconauto="building"','Company','SearchCompanies'); ?>
                <?php echo Core::InsertElement('multiple','companies','','txC form-control chosenSelect','data-placeholder="Todos los proveedores con lista de precio"',$Companies); ?>
              </div>
            </div>
            
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-12">
                Marcas:
                
                <?php echo Core::InsertElement('multiple','brands','','txC form-control chosenSelect','data-placeholder="Todas las Marcas en listas de precio"',$Brands); ?>
              </div>
            </div>
            
              <!--<div class="row form-group inline-form-custom">-->
              <!--  <div class="col-xs-12 col-sm-12">-->
              <!--    Fecha de los listados:-->
              <!--    <?php //echo Core::InsertElement('text','list_date','','txC form-control datePicker','placeholder="Sin fecha espec&iacute;fica. Tomar &uacute;ltimo listado subido de cada proveedor."') ?>-->
              <!--  </div>-->
              <!--</div>-->
              
              <!--<div class="row form-group inline-form-custom">-->
              <!--  <div class="col-xs-12 col-sm-12">-->
              <!--    Moneda:-->
              <!--    <?php //echo Core::InsertElement('select','currency_id',$Relation['currency_id'],'txC form-control chosenSelect','data-placeholder="Seleccionar Moneda" validateEmpty="Seleccione una Moneda"',Currency::GetSelectCurrency(),' ',''); ?>-->
              <!--  </div>-->
              <!--</div>-->
              
              <div class="row form-group inline-form-custom">
                <div class="col-xs-12 col-sm-12">
                  
                    <div class="checkbox icheck">
                        <label>
                            
                            <input type="checkbox" class="iCheckbox" name="stockmin" id="stockmin" value="1" > <span>Comparar &uacute;nicamente los productos que esten por debajo del stock m&iacute;nimo.</span>
                        </label>
                    </div>
                </div>
              </div>
            
            <div class="txC">
              <button type="button" class="btn btn-primary btnBlue" id="BtnCompare"><i class="fa fa-copy"></i> Comparar</button>
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
// $Foot->SetScript('../../../../vendors/datepicker/bootstrap-datepicker.js');
include('../../../project/resources/includes/inc.bottom.php');
?>