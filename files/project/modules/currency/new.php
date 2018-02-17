<?php
    include("../../../core/resources/includes/inc.core.php");
    
    $New = new Currency();
    
    $Head->SetTitle($Menu->GetTitle().$Title);
    $Head->SetIcon($Menu->GetHTMLicon());
    // $Head->SetStyle('../../../../vendors/datepicker/datepicker3.css'); // Date Picker Calendar
    // $Head->SetStyle('../../../../vendors/dropzone/dropzone.min.css'); // Dropzone
    // $Head->SetStyle('../../../../vendors/autocomplete/jquery.auto-complete.css'); // Autocomplete
    $Head->setHead();
    include('../../../project/resources/includes/inc.top.php');
    echo Core::InsertElement("hidden","action",'insert');
?>

  <div class="box animated fadeIn" style="min-width:99%">
    <div class="box-header flex-justify-center">
      <div class="innerContainer main_form" style="min-width:100%">
        <h4 class="subTitleB"><i class="fa fa-money"></i> Nombre</h4>
        <div class="row form-group inline-form-custom">
          <div class="col-xs-12">
              <?php echo Core::InsertElement('text','title','','form-control','validateEmpty="Ingrese un Nombre" placeholder="Ingrese un Nombre" validateFromFile="'.PROCESS.'///El nombre de la moneda ya existe///action:=validate///object:=Currency" autofocus'); ?>
          </div>
        </div>
        <h4 class="subTitleB"><i class="fa fa-btc"></i> S&iacute;mbolo</h4>
        <div class="row form-group inline-form-custom">
          <div class="col-xs-12">
              <?php echo Core::InsertElement('text','prefix','','form-control','validateEmpty="Ingrese un S&iacute;mbolo" placeholder="Ingrese un S&iacute;mbolo"'); ?>
          </div>
        </div>
        <h4 class="subTitleB"><i class="fa fa-qrcode"></i> C&oacute;digo AFIP</h4>
        <div class="row form-group inline-form-custom">
          <div class="col-xs-12">
              <?php echo Core::InsertElement('text','afip_code','','form-control','validateEmpty="Ingrese un c&oacute;digo" validateMinLength="3///Ingrese 3 caracteres" validateMaxLength="3///Ingrese 3 caracteres" placeholder="Ingrese un c&oacute;digo"'); ?>
          </div>
        </div>
        <hr>
        <div class="row txC">
          <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-plus"></i> Crear Moneda</button>
          <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
        </div>
      </div>
    </div><!-- box -->
  </div><!-- box -->
<?php
$Foot->SetScript('../../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
// $Foot->SetScript('../../../../vendors/autocomplete/jquery.auto-complete.min.js');
// $Foot->SetScript('../../../../vendors/datepicker/bootstrap-datepicker.js');
// $Foot->SetScript('../../../../vendors/dropzone/dropzone.min.js');
// $Foot->SetScript('script.dropzone.js');
// $Foot->SetScript('script.traceability.js');
// $Foot->SetScript('script.email.js');
// $Foot->SetScript('script.product.js');
include('../../../project/resources/includes/inc.bottom.php');
?>