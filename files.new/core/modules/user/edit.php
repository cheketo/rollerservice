<?php
    include("../../../core/resources/includes/inc.core.php");
    $ID   = $_GET['id'];
    $Edit = new CoreUser($ID);
    $Data = $Edit->GetData();
    Core::ValidateID($Data);
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->setHead();
    
    $Menues = implode(",",$Edit->GetCheckedMenues());
    $RelatedGroups = CoreUser::GetUserGroups($ID);
    foreach($RelatedGroups as $Group)
    {
      $Groups[] = $Group['group_id'];
    }
    $Groups = count($Groups)>0? implode(',',$Groups):'';
    include('../../../project/resources/includes/inc.top.php');
    echo Core::InsertElement("hidden","action",'update');
    echo Core::InsertElement("hidden","id",$ID);
    echo Core::InsertElement("hidden","menues",$Menues);
    echo Core::InsertElement("hidden","newimage",$Edit->Img);
?>
   <div class="box"> <!--box-success-->
    <div class="box-header with-border">
      <h3 class="box-title">Complete el formulario</h3>
    </div><!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <!-- User Data -->
        <div class="col-md-6">
          <div class="flex-allCenter innerContainer">
            <div class="mw100">
              <h4 class="subTitleB"><i class="fa fa-pencil"></i> Datos Principales</h4>
              <div class="form-group">
                <?php echo Core::InsertElement('text','user',$Data['user'],'form-control','placeholder="Usuario" tabindex="1" validateEmpty="El usuario es obligatorio." validateMinLength="3///El usuario debe contener 3 caracteres como m&iacute;nimo." validateFromFile='.PROCESS.'///El usuario ya existe///actualuser:='.$Data['user'].'///action:=validate///object:=CoreUser"'); ?>
              </div>
              <div class="form-group">
                <?php echo Core::InsertElement('password','password','','form-control','placeholder="Contrase&ntilde;a" validateMinLength="4///La contrase&ntilde;a debe contener 4 caracteres como m&iacute;nimo." tabindex="2"'); ?>
              </div>
              <div class="form-group">
                <?php echo Core::InsertElement('password','password_confirm','','form-control','placeholder="Confirmar Contrase&ntilde;a" validateEqualToField="password///Las contrase&ntilde;as deben coincidir." tabindex="3"'); ?>
              </div>
              <div class="form-group">
                <?php echo Core::InsertElement('text','email',$Data['email'],'form-control','placeholder="Email" validateEmail="Ingrese un email v&aacute;lido." validateMinLength="4///El email debe contener 4 caracteres como m&iacute;nimo." tabindex="4" validateFromFile='.PROCESS.'///El email ya existe///actualemail:='.$Data['email'].'///action:=validate_email///object:=CoreUser"'); ?>
              </div>
              <div class="form-group">
                <?php echo Core::InsertElement('text','first_name',$Data['first_name'],'form-control','placeholder="Nombre" validateEmpty="El nombre es obligatorio." validateMinLength="2///El nombre debe contener 2 caracteres como m&iacute;nimo." tabindex="5"'); ?>
              </div>
              <div class="form-group">
                <?php echo Core::InsertElement('text','last_name',$Data['last_name'],'form-control','placeholder="Apellido" validateEmpty="El apellido es obligatorio." validateMinLength="2///El apellido debe contener 2 caracteres como m&iacute;nimo." tabindex="6"'); ?>
              </div>
            </div>
          </div>
        </div><!-- User Data -->
        <div class="col-md-6">
          <div class="innerContainer">
            <div class="row">
              <!-- Profile -->
              <div class="col-lg-6 col-md-12">
                <div class="form-group">
                  <h4 class="subTitleB"><i class="fa fa-eye"></i> Perfiles</h4>
                  <?php echo Core::InsertElement('select','profile',$Data['profile_id'],'form-control chosenSelect','validateEmpty="El perfil es obligatorio." data-placeholder="Seleccione un Perfil" tabindex="7"',Core::Select('core_profile','profile_id,title',"status='A' AND organization_id=".$_SESSION['organization_id']),' ',""); ?>
                </div>
              </div>
              <!-- Groups -->
              <div class="col-lg-6 col-md-12">
                <div class="form-group" id="groups-wrapper">
                  <h4 class="subTitleB"><i class="fa fa-users"></i> Grupos</h4>
                  <?php echo Core::InsertElement('multiple','groups',$Groups,'form-control chosenSelect','data-placeholder="Seleccione los Grupos"'); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- User Data -->
        <!-- Tree Chekbox -->
        <div class="col-md-6">
          <div id="treeview-checkbox" class="treeCheckBoxDiv">
            <h4 class="subTitleB"><i class="fa fa-key"></i> Permisos</h4>
            <?php echo $Menu->MakeTree(); ?>
          </div><!-- treeview-checkbox -->
        </div><!-- User Data -->
      </div><!-- row -->
      <!-- IMAGES -->
      <!-- Actual Image -->
      <div class="row imagesMain">
        <div class="col-lg-3 col-md-12 col-sm-6 col-xs-12">
          <div class="imagesContainer">
            <h4 class="subTitleB"><i class="fa fa-picture-o"></i> Im&aacute;gen Actual</h4>
            <div class="flex-allCenter imgSelector">
              <div class="imgSelectorInner">
                <img src="<?php echo $Edit->Img ?>" class="img-responsive MainImg animated">
                <?php echo Core::InsertElement('file','image','','Hidden'); ?>
                <div class="imgSelectorContent">
                  <div id="SelectImg">
                    <i class="fa fa-upload"></i><br>
                   <p>Cargar Nueva Im&aacute;gen</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="text-bottom">
              <p><i class="fa fa-upload" aria-hidden="true"></i>
              Haga Click sobre la im&aacute;gen </br> para cargar una desde su dispositivo</p>
            </div>
          </div>
        </div><!-- /Actual Image -->
        <!-- Generic Images -->
        <div class="col-lg-5 col-md-12 col-sm-6 col-xs-12">
          <div class="imagesContainer">
            <h4 class="subTitleB"><i class="fa fa-picture-o"></i> Im&aacute;genes Gen&eacute;ricas</h4>
            <div class="smallThumbsList flex-justify-center">
              <ul>
                <?php
                  foreach($Edit->DefaultImages() as $Image)
                  {
                    echo '<li><img src="'.$Image.'" class="ImgSelecteable"></li>';
                  }
                ?>
              </ul>
            </div>
             <div class="text-bottom">
               <p><i class="fa fa-check" aria-hidden="true"></i>
               Seleccione una im&aacute;gen para utilizarla</p>
            </div>
          </div>
        </div><!-- /Generic Images -->
        <!-- Recent Images -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
          <div class="imagesContainer">
            <h4 class="subTitleB"><i class="fa fa-picture-o"></i> Im&aacute;genes usadas anteriormente</h4>
            <div class="smallThumbsList flex-justify-center">
              <ul id="UserImages">
                <?php
                  foreach($CoreUser->GetImages() as $Image)
                  {
                    echo '<li><img src="'.$Image.'" class="ImgSelecteable"></li>';
                  }
                ?>
              </ul>
            </div>
             <div class="text-bottom">
               <p><i class="fa fa-check" aria-hidden="true"></i>
              Seleccione una im&aacute;gen para utilizarla</p>
            </div>
          </div>
        </div><!-- /Recent Images -->
      </div><!-- IMAGES -->
    </div><!-- /.box-body -->
    <div class="box-footer btnRightMobCent">
      <button type="button" class="btn btn-success btnGreen" id="BtnEdit"><i class="fa fa-pencil"></i> Editar Usuario</button>
      <button type="button" class="btn btn-danger btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
    </div><!-- box-footer -->
  </div><!-- /.box -->
  
<?php include_once('modal.help.php');
// Tree With Checkbox
// DOCUMENTATION >  http://www.jquery-az.com/jquery-treeview-with-checkboxes-2-examples-with-bootstrap
$Foot->SetScript('../../../../vendors/treemultiselect/logger.min.js');
$Foot->SetScript('../../../../vendors/treemultiselect/treeview.min.js');
include('../../../project/resources/includes/inc.bottom.php');
?>