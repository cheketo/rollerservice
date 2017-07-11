<?php
    include("../../../core/resources/includes/inc.core.php");
    $ID           = $_GET['id'];
    $Edit         = new CoreProfile($ID);
    $Data         = $Edit->GetData();
    Core::ValidateID($Data);
    
    foreach($Edit->GetGroups() as $Group)
    {
      $Groups .= $Groups? ','.$Group['group_id'] : $Group['group_id']; 
    }
    $Menues = Core::Select("relation_menu_profile","DISTINCT(menu_id)","profile_id=".$ID);
    foreach($Menues as $MenuData)
    {
      $MenuArray[] = $MenuData['menu_id'];
    }
    if(is_array($MenuArray))
      $Menues = implode(",",$MenuArray);
    else
      $Menues = 0;
    
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/bootstrap-switch/bootstrap-switch.css'); // Switch On Off
    $Head->setHead();
    include('../../../project/resources/includes/inc.top.php');
?>
  <?php echo Core::InsertElement("hidden","action",'update'); ?>
  <?php echo Core::InsertElement("hidden","menues",$Menues); ?>
  <?php echo Core::InsertElement("hidden","id",$ID); ?>
  <?php echo Core::InsertElement("hidden","newimage",$Edit->GetImg()); ?>
  <div class="box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-lg-8 col-sm-12">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-plus-circle"></i> Modifique los campos para editar al perfil <?php echo $Data['title']; ?></h4>
            <div class="row form-group inline-form-custom-2">
              <div class="col-xs-12 col-sm-6 inner">
                <label>T&iacute;tulo</label>
                <?php echo Core::InsertElement('text','title',$Data['title'],'form-control','placeholder="Ingrese un T&iacute;tulo" validateEmpty="Ingrese un t&iacute;tulo." validateFromFile='.PROCESS.'///El perfil ya existe///action:=validate///actualtitle:='.$Data['title'].'///object:=CoreProfile"'); ?>
              </div>
              <div class="col-xs-12 col-sm-6 inner">
                <label for="">Grupos</label>
                <div class="form-group" id="groups-wrapper">
                  <?php echo Core::InsertElement('multiple','groups',$Groups,'form-control chosenSelect','data-placeholder="Seleccione Grupos"',Core::Select('admin_group','group_id,title',"status<>'I' AND organization_id = ".$_SESSION['organization_id'])); ?>
                </div>
              </div>
              <!--<div class="col-xs-12 col-sm-12 inner">-->
              <!--  <label for="">Usuarios</label>-->
              <!--  <div class="form-group" id="groups-wrapper">-->
              <!--    <?php //echo Core::InsertElement('multiple','users','','form-control chosenSelect','data-placeholder="Seleccione Usuarios"',Core::Select('core_user','user_id,user',"status='A' AND organization_id = ".$_SESSION['organization_id'])); ?>-->
              <!--  </div>-->
              <!--</div>-->
              <div class="col-xs-12 col-sm-6 inner">
                <label for="">Im&aacute;gen</label>
                <div class="lineContainer txC">

                  <div class="flex-allCenter imgSelector">
                    <div class="imgSelectorInner">
                      <img src="<?php echo $Edit->GetImg(); ?>" class="img-responsive MainImg animated">
                      <?php echo Core::InsertElement('file','image','','Hidden'); ?>
                      <input readonly="readonly" id="Fileimage" name="FileField" class="Hidden" type="text">
                      <div style="height: 0px; width: 0px; overflow: hidden;">
                        <input id="image" name="image" class="Hidden" type="file">
                      </div>
                      <div class="imgSelectorContent">
                        <div id="SelectImg">
                          <i class="fa fa-upload"></i><br>
                           <p>Cargar Nueva Im&aacute;gen</p>
                        </div>
                      </div>
                      <div id="FileimageErrorDiv" class="ErrorText Red"></div>
                      <div id="imageErrorDiv" class="ErrorText Red"></div>
                    </div>
                  </div>
                  <div class="text-bottom">
                    <p><i class="fa fa-upload" aria-hidden="true"></i>
                    Haga Click sobre la im&aacute;gen </br> para cargar una desde su dispositivo</p>
                  </div>

                </div>
              </div>
              <div class="col-xs-12 col-sm-6 inner">
                <div id="treeview-checkbox" class="treeCheckBoxDiv">
                  <label for="">Permisos</label>
                  <?php echo $Menu->MakeTree(); ?>
                </div><!-- treeview-checkbox -->
              </div>
            </div><!-- inline-form -->
            <hr>
            <div class="txC">
              <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-check"></i> Editar Perfil</button>
              <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
            </div>
        </div>
      </div>
    </div>
  </div>

<?php
  $Foot->SetScript('../../../../vendors/bootstrap-switch/script.bootstrap-switch.min.js');
  
  $Foot->SetScript('../../../../vendors/treemultiselect/logger.min.js');
  $Foot->SetScript('../../../../vendors/treemultiselect/treeview.min.js');
  include('../../../project/resources/includes/inc.bottom.php');
?>
