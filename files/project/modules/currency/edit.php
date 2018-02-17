<?php
    include("../../../core/resources/includes/inc.core.php");
    $ID = $_GET['id'];
    $Edit = new Currency($ID);
    $Data = $Edit->GetData();
    Core::ValidateID($Data);
    $Head->SetTitle($Menu->GetTitle().$Title);
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->setHead();
    include('../../../project/resources/includes/inc.top.php');
    echo Core::InsertElement("hidden","action",'update');
    echo Core::InsertElement("hidden","id",$ID);
?>

  <div class="box animated fadeIn" style="min-width:99%">
    <div class="box-header flex-justify-center">
      <div class="innerContainer main_form" style="min-width:100%">
        <h4 class="subTitleB"><i class="fa fa-money"></i> Nombre</h4>
        <div class="row form-group inline-form-custom">
          <div class="col-xs-12">
              <?php echo Core::InsertElement('text','title',$Data['title'],'form-control','validateEmpty="Ingrese un Nombre" placeholder="Ingrese un Nombre" validateFromFile="'.PROCESS.'///El nombre de la moneda ya existe///action:=validate///actualtitle:='.$Data['title'].'///object:=Currency" autofocus'); ?>
          </div>
        </div>
        <h4 class="subTitleB"><i class="fa fa-btc"></i> S&iacute;mbolo</h4>
        <div class="row form-group inline-form-custom">
          <div class="col-xs-12">
              <?php echo Core::InsertElement('text','prefix',$Data['prefix'],'form-control','validateEmpty="Ingrese un S&iacute;mbolo" placeholder="Ingrese un S&iacute;mbolo"'); ?>
          </div>
        </div>
        <h4 class="subTitleB"><i class="fa fa-qrcode"></i> C&oacute;digo AFIP</h4>
        <div class="row form-group inline-form-custom">
          <div class="col-xs-12">
              <?php echo Core::InsertElement('text','afip_code',$Data['afip_code'],'form-control','validateEmpty="Ingrese un c&oacute;digo" validateMinLength="3///Ingrese 3 caracteres" validateMaxLength="3///Ingrese 3 caracteres" placeholder="Ingrese un c&oacute;digo"'); ?>
          </div>
        </div>
        <hr>
        <div class="row txC">
          <button type="button" class="btn btn-success btnGreen" id="BtnEdit"><i class="fa fa-pencil"></i> Editar Moneda</button>
          <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
        </div>
      </div>
    </div><!-- box -->
  </div><!-- box -->
<?php
  include('../../../project/resources/includes/inc.bottom.php');
?>


