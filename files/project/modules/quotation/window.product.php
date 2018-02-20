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
            </div>
        </form>
    </div>
    <div class="window-border txC">
        <button type="button" class="btn btn-primary btnBlue" id="CreateProduct"><i class="fa fa-plus-circle"></i> Crear Art&iacute;culo</button>
        <button type="button" class="btn btn-error btnRed CloseProductWindow" id="CancelProduct"><i class="fa fa-times"></i> Cancelar</button>
    </div>
  </div>