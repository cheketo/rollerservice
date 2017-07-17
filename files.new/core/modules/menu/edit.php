<?php
    include("../../../core/resources/includes/inc.core.php");
    $ID           = $_GET['id'];
    $Edit         = new CoreMenu($ID);
    $Data         = $Edit->GetData();
    $Data['link'] = $Data['link']=='#'? '' : $Data['link_text'];
    Core::ValidateID($Data);
    
    foreach($Edit->GetGroups() as $Group)
    {
      $Groups .= $Groups? ','.$Group['group_id'] : $Group['group_id']; 
    }
    foreach($Edit->GetProfiles() as $Profile)
    {
      $Profiles .= $Profiles? ','.$Profile['profile_id'] : $Profile['profile_id']; 
    }
    $Head->SetTitle($Edit->Data['title']);
    $Head->SetSubTitle("Modificar Men&uacute;");
    $Head->SetIcon($Edit->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/bootstrap-switch/bootstrap-switch.css'); // Switch On Off
     
    $Head->setHead();
    
    include('../../../project/resources/includes/inc.top.php');
    
?>
  <?php echo Core::InsertElement("hidden","action",'update'); ?>
  <?php echo Core::InsertElement("hidden","id",$ID); ?>
  <?php echo Core::InsertElement("hidden","icon",$Data['icon']); ?>
  <div class="ProductDetails box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-lg-8 col-sm-12">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-plus-circle"></i> Modifique los campos para editar al men&uacute; <?php echo $Data['title']; ?></h4>
          
            <div class="row form-group inline-form-custom-2">
              <div class="col-xs-12 col-sm-4 inner">
                <label>T&iacute;tulo</label>
                <?php echo Core::InsertElement('text','title',$Data['title'],'form-control','placeholder="Ingrese un T&iacute;tulo" validateEmpty="Ingrese un t&iacute;tulo."'); ?>
              </div>
              <div class="col-xs-12 col-sm-4 inner">
                <label for="">Ubicaci&oacute;n</label>
                <?php //echo Core::InsertElement('select','parent',$Data['parent_id'],'form-control chosenSelect','',Core::Select('core_menu a LEFT JOIN core_menu b ON (a.parent_id=b.menu_id OR b.menu_id=0)',"a.menu_id,COALESCE(CONCAT(b.title,'/',a.title), a.title) as title","a.status<>'I' AND a.menu_id <>".$ID),'0','Men&uacute; Principal'); ?>
                <?php echo Core::InsertElement('select','parent',$Data['parent_id'],'form-control chosenSelect','',Core::Select(CoreMenu::TABLE." a LEFT JOIN ".CoreMenu::TABLE." b ON ((a.parent_id=b.".CoreMenu::TABLE_ID." OR b.menu_id=0) AND a.link<>'#')","a.".CoreMenu::TABLE_ID.",COALESCE(CONCAT(b.title,'/',a.title), a.title) AS title","a.status<>'I' AND a.".CoreMenu::TABLE_ID."<>".$ID." AND a.".CoreOrganization::TABLE_ID." IN (0,".$_SESSION[CoreOrganization::TABLE_ID].")"),'0','Men&uacute; Principal',"a.parent_id"); ?>
              </div>
              <div class="col-xs-12 col-sm-4 inner">
                <label>Link</label>
                <?php echo Core::InsertElement('text','link',$Data['link'],'form-control','placeholder="Ingrese una Ruta"'); ?>
              </div>
              <div class="col-xs-12 col-sm-4 inner">
                <label for="">Perfiles</label>
                <div class="form-group" id="groups-wrapper">
                  <?php //echo Core::InsertElement('multiple','profiles',$Profiles,'form-control chosenSelect','data-placeholder="Seleccione Perfiles"',Core::Select('core_profile','profile_id,title',"status<>'I' AND organization_id = ".$_SESSION['organization_id'])); ?>
                  <?php echo Core::InsertElement('select','profiles',$Profiles,'form-control chosenSelect','multiple="multiple" data-placeholder="Seleccione Perfiles" style="width: 100%;"',Core::Select(CoreProfile::TABLE,CoreProfile::TABLE_ID.',title',"status<>'I' AND ".CoreOrganization::TABLE_ID." = ".$_SESSION[CoreOrganization::TABLE_ID])); ?>
                </div>
              </div>
              <div class="col-xs-12 col-sm-4 inner">
                <label for="">Grupos</label>
                <div class="form-group" id="groups-wrapper">
                  <?php //echo Core::InsertElement('multiple','groups',$Groups,'form-control chosenSelect','data-placeholder="Seleccione Grupos"',Core::Select('core_group','group_id,title',"status<>'I' AND organization_id = ".$_SESSION['organization_id'])); ?>
                  <?php echo Core::InsertElement('select','groups',$Groups,'form-control chosenSelect','multiple="multiple" data-placeholder="Seleccione Grupos"',Core::Select(CoreGroup::TABLE,CoreGroup::TABLE_ID.',title',"status<>'I' AND ".CoreOrganization::TABLE_ID." = ".$_SESSION[CoreOrganization::TABLE_ID])); ?>
                </div>
              </div>
              <div class="col-xs-12 col-sm-4 inner">
                <label for="">Posici&oacute;n  </label><br>
                <?php echo Core::InsertElement('text','position',$Data['position'],'form-control','placeholder="Ingrese un n&uacute;mero" validateOnlyNumbers="Ingrese un n&uacute;mero."'); ?>
              </div>
              <div class="col-md-12 padL0">
                <div class="col-xs-12 col-sm-4 inner">
                  <label for="">Privacidad</label><br>
                  <?php $Checked = $Data['public']!='Y'? 'checked':''; ?>
                  <?php echo Core::InsertElement('checkbox','public','','','data-on-text="Privado" data-off-text="P&uacute;blico" data-size="mini" '.$Checked); ?>
                </div>
                <div class="col-xs-12 col-sm-4 inner">
                  <label for="">Visibilidad</label><br>
                  <?php $Checked = $Data['status']!='O'? 'checked':''; ?>
                  <?php echo Core::InsertElement('checkbox','status','','','data-on-text="Visible" data-off-text="Oculto" data-size="mini" '.$Checked); ?>
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
              <button type="button" class="btn btn-success btnGreen" id="BtnEdit"><i class="fa fa-check"></i> Editar Men&uacute;</button>
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
$Foot->SetScript('../../../../vendors/bootstrap-switch/script.bootstrap-switch.min.js');

include('../../../project/resources/includes/inc.bottom.php');
?>
