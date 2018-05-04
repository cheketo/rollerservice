<div class="window Hidden" id="ProductWindow">
    <div class="window-border"><h4><div class="pull-left"><i class="fa fa-cube"></i> Crear Nuevo Art&iacute;culo</div><div class="pull-right"><div id="ProductWindowClose" class="CloseWindow CloseProductWindow text-red"><i class="fa fa-times"></i></div></div></h4></div>
    <div class="window-body" style="background-color:#FFF;">
        <form id="ProductWindowForm">
            <div class="innerContainer" style="background-color:#EEE;padding-top:20px!important;">
                <div class="row form-group inline-form-custom">
                    <div class="col-xs-12 col-sm-6">
                        <?php echo Core::InsertElement('text','new_product_code','','form-control','placeholder="C&oacute;digo" validateEmpty="Ingrese un c&oacute;digo."') ?>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <?php //echo Core::InsertElement('text','price','','form-control','placeholder="Precio" data-inputmask="\'alias\': \'numeric\', \'groupSeparator\': \'\', \'autoGroup\': true, \'digits\': 2, \'digitsOptional\': false, \'prefix\': \'$\', \'placeholder\': \'0\'"') ?>
                        <?php echo Core::InsertElement('text','new_product_order_number','','form-control','placeholder="N&uacute;mero de Orden" validateOnlyNumbers="Ingrese n&uacute;meros &uacute;nicamente." validateEmpty="Ingrese un N&uacute;mero de Orden" data-inputmask="\'alias\': \'numeric\', \'groupSeparator\': \'\', \'autoGroup\': true, \'digits\': 0, \'digitsOptional\': false, \'prefix\': \'\', \'placeholder\': \'0\'"') ?>
                    </div>
                </div>
                <div class="row form-group inline-form-custom">
                    <div class="col-xs-12 col-sm-6">
                        <?php echo Core::InsertElement('select','new_product_category','','form-control chosenSelect','data-placeholder="Seleccionar L&iacute;nea" validateEmpty="Seleccione una l&iacute;nea."',Core::Select(Category::TABLE,Category::TABLE_ID.",title","status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]),' ','') ?>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <?php echo Core::InsertElement('select','new_product_brand','','form-control chosenSelect','data-placeholder="Seleccionar Marca" validateEmpty="Seleccione una marca." style="width:100%!important;"',Core::Select(Brand::TABLE,Brand::TABLE_ID.",name","status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]),' ',''); ?>
                    </div>
                </div>
            
                <?php echo Core::InsertElement("hidden","new_abstract"); ?>
            
                <!-- Abstract Product Association -->
                  <div class="form-group row" id="abstract-assoc">
                    <div class="col-xs-12">
                      Asociar con producto gen&eacute;rico:
                    </div>
                    <div class="col-xs-12">
                      <?php //echo Core::InsertElement('select','abstract','','form-control chosenSelect','data-placeholder="Seleccionar C&oacute;digo Gen&eacute;rico" style="width:100%!important;"',Core::Select(ProductAbstract::TABLE,ProductAbstract::TABLE_ID.",code","status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]),'',' '); ?>
                      <?php echo Core::InsertElement('autocomplete','abstract','','form-control','placeholder="C&oacute;digo a asociar" placeholderauto="C&oacute;digo no encontrado" iconauto="cube"','ProductAbstract','SearchAbstractCodes'); ?>
                    </div>
                    <!-- New Abstract Button -->
                    <div class="col-xs-12 text-center">
                      <br>
                      <?php //echo Core::InsertElement('button','abstract_new','<i class="fa fa-exchange"></i> Crear un nuevo art&iacute;culo gen&eacute;rico y asociarlo al art&iacute;culo','btn btn-info') ?>
                      <span id="abstract_new" name="abstract_new" class="btn btn-info"><i class="fa fa-exchange"></i> Crear un nuevo artículo genérico y asociarlo al artículo</span>
                    </div>
                  </div>
                <!--</form>-->
                
                <!--<form id="abstract_form" name="abstract_form">-->
                  <!-- New Abstract Data -->
                  <div class="form-group row Hidden" id="new-abstract">
                    <div class="col-xs-12">
                      C&oacute;digo:
                    </div>
                    <div class="col-xs-12">
                      <?php echo Core::InsertElement('text','abstract_code','','form-control','placeholder="C&oacute;digo Gen&eacute;rico"') ?>
                    </div>
                    <div class="col-xs-12 text-center">
                        <br>
                        <?php //echo Core::InsertElement('button','abstract_cancel','Cancelar','btn btn-danger','style="width:100%;"') ?>
                        <span id="abstract_cancel" name="abstract_cancel" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar</span>
                    </div>
                  </div>
                <!--</form>-->
            </div>
            
            
            
        </form>
    </div>
    <div class="window-border txC">
        <button type="button" class="btn btn-primary btnBlue" id="CreateProduct"><i class="fa fa-plus-circle"></i> Crear Art&iacute;culo</button>
        <button type="button" class="btn btn-error btnRed CloseProductWindow" id="CancelProduct"><i class="fa fa-times"></i> Cancelar</button>
    </div>
  </div>