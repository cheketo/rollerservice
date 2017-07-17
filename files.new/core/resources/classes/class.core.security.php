<?php

class CoreSecurity
{
	var $Profile_id;
	var $User;
	var $Password;
	var $Link;
	var $File;
	var $IsLogged		= false;
	var $MenuData 		= array();
	// var $CoreProfile 	= array();
	var $Destination	= '../../../core/modules/main/main.php';
	
	const PROFILE		= 333;
	const LOGIN			= 'core/modules/login/login.php';
	

	function __construct($User='',$Password='')
	{
		
		$this->User		= isset($_COOKIE['user'])? 			$_COOKIE['user'] 		: $User;
		$this->Password	= isset($_COOKIE['password'])? 		$_COOKIE['password'] 	: $Password;
		$this->File		= basename($_SERVER['PHP_SELF']);
		$this->Link		= Core::GetLink();
		$MenuData		= Core::Select('core_menu',"menu_id,public","link LIKE '%".$this->Link."%'");
		$this->MenuData	= $MenuData[0];
	}

	public function CheckProfile()
	{
		if($_SESSION[CoreUser::TABLE_ID] && $this->User!='' && $this->Password!='')
		{
			// $CoreProfile		= Core::Select("core_user","profile_id","user = '".$this->User."' AND password='".$this->Password."' AND user_id='".$_SESSION['user_id']."'");
			// $this->CoreProfile	= $CoreProfile[0];

			if($this->CheckException() && $_SESSION[CoreProfile::TABLE_ID]!=self::PROFILE)
			{
				$Groups    		= Core::Select("core_relation_user_group","group_id",CoreUser::TABLE_ID."=".$_SESSION[CoreUser::TABLE_ID]);
				$UserGroups 	= array();
				$UserGroups[]	= 0;
				foreach($Groups as $Group)
				{
					$UserGroups[]	= $Group['group_id'];
				}
				$MenuGroups 	= implode(",",$UserGroups);
				$Rows			= Core::NumRows("core_relation_menu_profile","*","menu_id = ".$this->MenuData['menu_id']." AND profile_id = ".$_SESSION[CoreProfile::TABLE_ID]);
				$Exceptions 	= Core::NumRows("core_relation_user_menu","*","menu_id = ".$this->MenuData['menu_id']." AND user_id = ".$_SESSION[CoreUser::TABLE_ID]);
				$GroupsAllowed 	= Core::NumRows("core_relation_menu_group","*","menu_id = ".$this->MenuData['menu_id']." AND group_id IN (".$MenuGroups.")");

				if($Rows<1 && $Exceptions<1 && $GroupsAllowed<1){
					header("Location: ".$_SERVER['HTTP_REFERER']); echo '<script>window.history.go(-1)</script>';
				}
			}elseif($this->Link==self::LOGIN){
				header("Location: ".$this->Destination);
			}
			$this->IsLogged		= true;
			return true;
		}else{
			if($this->CheckException() && $this->File!=self::LOGIN)
			{
				header("Location: ../../../".self::LOGIN."?error=login");
			}
			return false;
		}
	}

	public function CheckException()
	{
		if(isset($this->MenuData['menu_id']))
		{
			return 	$this->MenuData['public']!='Y';
		}
		else
		{
			return false;
		}
	}
}

?>
