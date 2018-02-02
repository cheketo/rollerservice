<div class="window Hidden" id="EmailWindow">
    <div class="window-border"><h4><div class="pull-left"><i class="fa fa-envelope"></i> Enviar Cotizaci&oacute;n por Email a <span id="CompanyName" class="font-weight-bold"></span></div><div class="pull-right"><div id="EmailWindowClose" class="CloseEmailWindow text-red"><i class="fa fa-times"></i></div></div></h4></div>
    <div class="window-body">
        <form id="EmailWindowForm">
            <h4 class="subTitleB"><i class="fa fa-user-circle"></i> Destinatario</h4>
            <div class="row form-group inline-form-custom">
                <div class="col-xs-12">
                    <?php echo Core::InsertElement('text','receiver','','form-control','validateEmpty="Ingrese un Email" placeholder="Ingrese un Email"'); ?>
                </div>
            </div>
            <h4 class="subTitleB"><i class="fa fa-trademark"></i> Mostrar Marcas</h4>
            <div class="row form-group inline-form-custom">
                <div class="col-xs-12">
                    <?php echo Core::InsertElement('select','show_brands','','form-control chosenSelect','validateEmpty="Seleccione una Opci&oacute;n"',array('Y'=>'Si','N'=>'No')); ?>
                </div>
            </div>
            <h4 class="subTitleB"><i class="fa fa-info"></i> Mostrar Informaci&oacute;n Extra</h4>
            <div class="row form-group inline-form-custom">
                <div class="col-xs-12">
                    <?php echo Core::InsertElement('select','show_extra','','form-control chosenSelect','validateEmpty="Seleccione una Opci&oacute;n"',array('Y'=>'Si','N'=>'No')); ?>
                </div>
            </div>
        </form>
      
    </div>
    <div class="window-border txC">
        <button type="button" class="btn btn-primary btnBlue" id="SaveAndSend"><i class="fa fa-send"></i> Enviar</button>
        <button type="button" class="btn btn-error btnRed CloseEmailWindow" id="CancelEmail"><i class="fa fa-times"></i> Cancelar</button>
    </div>
  </div>