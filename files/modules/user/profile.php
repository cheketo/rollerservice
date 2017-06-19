<?php
    include("../../includes/inc.main.php");
    if(!$_SESSION['admin_id'])
    {
      header("Location: ../login/login.php?error=login"); die();
    }
    
    $Head->setTitle($Menu->GetTitle());
    $Head->setIcon($Menu->GetHTMLicon());
    $Head->setStyle('../../../vendors/chosen-js/bootstrap-chosen.css'); // Select Inputs With Tags
    $Head->setHead();
    include('../../includes/inc.top.php');
?>
  <div class="box"> <!--box-success-->
    <div class="box-body">
      <div class="row main-wrapper profile-section">
        <div class="col-md-5 profile-user-info">
          <div class="">
            <img class="profile-img img-responsive" src="<?php echo $Admin->Img ?>" alt="User profile picture">
            <h3 class="profile-username text-center"><?php echo $Admin->FullName ?></h3>
            <p class="text-muted text-center"><?php echo $Admin->User ?></p>
            <p class="text-muted text-center"><?php echo $Admin->Email ?></p>
            <p class="text-muted text-center"><?php echo $Admin->LastAccess ?></p>
          </div>
        </div>
        <div class="col-md-7 profile-user-misc">
          <div class="box-body">
            <span class="profile-titles"><strong><i class="fa fa-lock"></i> Perfil</strong></span>
            <p>
              <span class="label label-primary"><?php echo $Admin->ProfileName ?></span>
            </p>
            <hr>
            <span class="profile-titles"><strong><i class="fa fa-users"></i> Grupos</strong></span>
            <p>
              
              <?php
                foreach($Admin->GetGroups() as $Group)
                {
                  echo '<span class="label label-warning">'.$Group['title'].'</span> ';
                }
                if(count($Admin->GetGroups())<1) echo "Ninguno";
              ?>
            </p>
            <hr>
            <span class="profile-titles"><strong><i class="fa fa-key"></i> Permisos Especiales</strong></span>
            <p>
              <?php
                $Menues = $DB->fetchAssoc('admin_menu','*',"status = 'A' AND menu_id IN (SELECT menu_id FROM relation_admin_menu WHERE admin_id = ".$_SESSION['admin_id'].")");
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
              <?php $Customer = $Admin->GetCustomer(); ?>
              <img src="<?php echo $Customer['logo']; ?>" alt="" />
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.box-body -->
  </div><!-- /.box -->

<?php
// Select Inputs With Tags
$Foot->setScript('../../../vendors/chosen-js/chosen.jquery.js');
// ----
// Tree With Checkbox
// DOCUMENTATION >  http://www.jquery-az.com/jquery-treeview-with-checkboxes-2-examples-with-bootstrap
$Foot->setScript('../../../vendors/treemultiselect/logger.min.js');
$Foot->setScript('../../../vendors/treemultiselect/treeview.min.js');

include('../../includes/inc.bottom.php');
?>
