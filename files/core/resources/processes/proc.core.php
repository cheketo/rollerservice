<?php
    include('../../../core/resources/includes/inc.core.php');
    
    if(($_SESSION[CoreUser::TABLE_ID] && $_SESSION[CoreOrganization::TABLE_ID]) || $_SESSION['batch_id'] || ($_POST['loginaction']=='login' && $_POST['pagetarget']=='login'))
    {
        if($_GET['action'])
        {
            $Action = $_GET['action'];
        }elseif($_POST['action']){
            $Action = $_POST['action'];
        }
        $Action = ucfirst($Action);
        if($_REQUEST['object'])
        {
            if(strtolower($_REQUEST['object'])!='coreuser')
            {
                $Class  = $_REQUEST['object'];
                $Object = new $Class();
            }else{
            	$Object = $CoreUser;
            }
            $Object->$Action();
        }elseif($_POST['loginaction']=='login' && $_POST['pagetarget']=='login'){
            $Object = new CoreLogin();
            $Object->StartLogin();
        }
    }
?>