<?php
    include("../../includes/inc.main.php");
    $ID           = $_GET['id'];
    $Menu         = new AdminMenu($ID);
    $Data         = $Menu->GetData();
    $Data['link'] = $Data['link']=='#'? '' : $Data['link'];
    ValidateID($Data);
    
    foreach($Menu->GetGroups() as $Group)
    {
      $Groups .= $Groups? ','.$Group['group_id'] : $Group['group_id']; 
    }
    foreach($Menu->GetProfiles() as $Profile)
    {
      $Profiles .= $Profiles? ','.$Profile['profile_id'] : $Profile['profile_id']; 
    }
    
    $Head->setTitle($Data['title']);
    $Head->setSubTitle("Modificar Men&uacute;");
    $Head->setIcon($Menu->GetHTMLicon());
    $Head->setStyle('../../../vendors/bootstrap-switch/bootstrap-switch.css'); // Switch On Off
     
    $Head->setHead();
    
    include('../../includes/inc.top.php');
    
?>
  <?php echo insertElement("hidden","action",'update'); ?>
  <?php echo insertElement("hidden","id",$ID); ?>
  <?php echo insertElement("hidden","icon",$Data['icon']); ?>
  <div class="ProductDetails box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-lg-8 col-sm-12">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-plus-circle"></i> Modifique los campos para editar al men&uacute; <?php echo $Data['title']; ?></h4>
          
            <div class="row form-group inline-form-custom-2">
              <div class="col-xs-12 col-sm-4 inner">
                <label>T&iacute;tulo</label>
                <?php echo insertElement('text','title',$Data['title'],'form-control','placeholder="Ingrese un T&iacute;tulo" validateEmpty="Ingrese un t&iacute;tulo."'); ?>
              </div>
              <div class="col-xs-12 col-sm-4 inner">
                <label for="">Ubicaci&oacute;n</label>
                <?php echo insertElement('select','parent',$Data['parent_id'],'form-control chosenSelect','',$DB->fetchAssoc('admin_menu a LEFT JOIN admin_menu b ON (a.parent_id=b.menu_id OR b.menu_id=0)',"a.menu_id,COALESCE(CONCAT(b.title,'/',a.title), a.title) as title","status<>'I' AND menu_id <>".$ID),'0','Men&uacute; Principal'); ?>
              </div>
              <div class="col-xs-12 col-sm-4 inner">
                <label>Link</label>
                <?php echo insertElement('text','link',$Data['link'],'form-control','placeholder="Ingrese una Ruta"'); ?>
              </div>
              <div class="col-xs-12 col-sm-4 inner">
                <label for="">Perfiles</label>
                <div class="form-group" id="groups-wrapper">
                  <?php echo insertElement('multiple','profiles',$Profiles,'form-control chosenSelect','data-placeholder="Seleccione Perfiles"',$DB->fetchAssoc('admin_profile','profile_id,title',"status<>'I' AND company_id = ".$_SESSION['company_id'])); ?>
                </div>
              </div>
              <div class="col-xs-12 col-sm-4 inner">
                <label for="">Grupos</label>
                <div class="form-group" id="groups-wrapper">
                  <?php echo insertElement('multiple','groups',$Groups,'form-control chosenSelect','data-placeholder="Seleccione Grupos"',$DB->fetchAssoc('admin_group','group_id,title',"status<>'I' AND company_id = ".$_SESSION['company_id'])); ?>
                </div>
              </div>
              <div class="col-xs-12 col-sm-4 inner">
                <label for="">Posici&oacute;n  </label><br>
                <?php echo insertElement('text','position',$Data['position'],'form-control','placeholder="Ingrese un n&uacute;mero" validateOnlyNumbers="Ingrese un n&uacute;mero."'); ?>
              </div>
              <div class="col-md-12 padL0">
                <div class="col-xs-12 col-sm-4 inner">
                  <label for="">Privacidad</label><br>
                  <?php $Checked = $Data['public']!='Y'? 'checked':''; ?>
                  <?php echo insertElement('checkbox','public','','','data-on-text="Privado" data-off-text="P&uacute;blico" data-size="mini" '.$Checked); ?>
                </div>
                <div class="col-xs-12 col-sm-4 inner">
                  <label for="">Visibilidad</label><br>
                  <?php $Checked = $Data['status']!='O'? 'checked':''; ?>
                  <?php echo insertElement('checkbox','status','','','data-on-text="Visible" data-off-text="Oculto" data-size="mini" '.$Checked); ?>
                </div>
                <div class="col-xs-12 col-sm-4 inner">
                  <label for="">Icono</label>
                  <div class="input-group">
                    <span class="input-group-addon cursor-pointer IconInput"><i class="fa <?php echo $Data['icon'] ?>"></i></span>
                    <!--<input class="IconInput form-control cursor-pointer" placeholder="Seleccione un &iacute;cono" type="text">-->
                  </div>
                </div>
              </div>
            </div><!-- inline-form -->
            <hr>
            <div class="txC">
              <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-check"></i> Editar Men&uacute;</button>
              <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
            </div>
          
        </div>
      </div>
    </div>
  </div>

  <!-- Help Modal Trigger -->
  <?php include ('modal.icon.php'); ?>
  <!-- //// HELP MODAL //// -->
  <!-- Help Modal -->
<?php
$Foot->setScript('../../../vendors/bootstrap-switch/script.bootstrap-switch.min.js');

include('../../includes/inc.bottom.php');
?>
