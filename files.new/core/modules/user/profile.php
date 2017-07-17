<?php
    include("../../../core/resources/includes/inc.core.php");
    if(!$_SESSION['user_id'])
    {
      header("Location: ../../../core/modules/login/login.php?error=login"); die();
    }
    
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
     
    $Head->setHead();
    include('../../../project/resources/includes/inc.top.php');
?>
  <div class="box"> <!--box-success-->
    <div class="box-body">
      <div class="row main-wrapper profile-section">
        <div class="col-md-5 profile-user-info">
          <div class="">
            <img class="profile-img img-responsive" src="<?php echo $CoreUser->Img ?>" alt="User profile picture">
            <h3 class="profile-username text-center"><?php echo $CoreUser->Data['full_name'] ?></h3>
            <p class="text-muted text-center"><b><?php echo $CoreUser->Data['user'] ?></b></p>
            <p class="text-muted text-center"><?php echo $CoreUser->Data['email'] ?></p>
            <p class="text-muted text-center"><?php echo CoreUser::DateTimeFormat($CoreUser->Data['last_access']) ?></p>
          </div>
        </div>
        <div class="col-md-7 profile-user-misc">
          <div class="box-body">
            <span class="profile-titles"><strong><i class="fa fa-lock"></i> Perfil</strong></span>
            <p>
              <span class="label label-primary"><?php echo $CoreUser->Data['profile'] ?></span>
            </p>
            <hr>
            <span class="profile-titles"><strong><i class="fa fa-users"></i> Grupos</strong></span>
            <p>
              
              <?php
                foreach($CoreUser->GetGroups() as $Group)
                {
                  echo '<span class="label label-warning">'.$Group['title'].'</span> ';
                }
                if(count($CoreUser->GetGroups())<1) echo "Ninguno";
              ?>
            </p>
            <hr>
            <span class="profile-titles"><strong><i class="fa fa-key"></i> Permisos Especiales</strong></span>
            <p>
              <?php
                $Menues = Core::Select('core_menu','*',"status = 'A' AND menu_id IN (SELECT menu_id FROM core_relation_user_menu WHERE user_id = ".$_SESSION['user_id'].")");
                foreach($Menues as $Menu)
                {
                  echo '<span class="label label-success"><i class="fa '.$Menu['icon'].'"></i> '.$Menu['title'].'</span> ';
                }
                if(count($Menues)<1) echo "Ninguno";
              ?>
            </p>
            <hr>
            <span class="profile-titles"><strong><i class="fa fa-building"></i> Empresa</strong></span>
            <div class="profile-bussines-logo">
              <img src="<?php echo $CoreUser->Data['organization']['logo']; ?>" alt="" />
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.box-body -->
  </div><!-- /.box -->

<?php


// ----
// Tree With Checkbox
// DOCUMENTATION >  http://www.jquery-az.com/jquery-treeview-with-checkboxes-2-examples-with-bootstrap
$Foot->SetScript('../../../../vendors/treemultiselect/logger.min.js');
$Foot->SetScript('../../../../vendors/treemultiselect/treeview.min.js');

include('../../../project/resources/includes/inc.bottom.php');
?>
