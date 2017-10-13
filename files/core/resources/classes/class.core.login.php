<?php

class CoreLogin 
{
	var 	$User;
	var 	$Password;
	var 	$PasswordHash;
	var 	$RememberUser;
	var 	$Tries;
	var		$CoreUser = array();
	var 	$Target;
	var		$Return;

	const 	HOURS 		= 2;
	const 	MAX_TRIES	= 13;

	public function __construct()
	{
		
		
	}
	
	public function SetData($User,$Password='',$Remember=0,$PasswordHash='')
	{
		
		$this->User			= $User;
		$this->Password		= $Password;
		$this->PasswordHash	= $PasswordHash? $PasswordHash : sha1($Password);
		$this->CoreUser 	= Core::Select('core_user','*'," (user = '".$this->User."' OR email='".$this->User."' ) AND status = 'A'");
		// $this->Tries		= $this->CoreUser[0]['tries']+1;
		$this->RememberUser = $Remember==1;
	}
	
	protected function SetUser()
	{
		$this->CoreUser 	= Core::Select('core_user','*'," (user = '".$this->User."' OR email='".$this->User."' ) AND password='".$this->PasswordHash."' AND status = 'A'");
		$this->Tries		= $this->CoreUser[0]['tries']+1;
	}
	
	protected function RememberUser()
	{
		return 	$this->RememberUser;
	}
	
	protected function UserExists()
	{
		return count($this->CoreUser)>0;
	}
	
	protected function IsMaxTries()
	{
		return $this->PasswordHash? false : $this->Tries>self::MAX_TRIES;
	}
	
	protected function PassMatch()
	{
		return $this->CoreUser[0]['password']==$this->PasswordHash;
	}
	
	protected function GetIP()
	{
		return getenv("REMOTE_ADDR");
	}

	public function SetSessionVars()
	{
		$_SESSION['user'] 						= $this->CoreUser[0]['user'];
		$_SESSION[CoreUser::TABLE_ID]			= $this->CoreUser[0][CoreUser::TABLE_ID];
		$_SESSION[CoreOrganization::TABLE_ID]	= $this->CoreUser[0][CoreOrganization::TABLE_ID];
		$_SESSION['first_name'] 				= $this->CoreUser[0]['first_name'];
		$_SESSION['last_name'] 					= $this->CoreUser[0]['last_name'];
		$_SESSION[CoreProfile::TABLE_ID] 		= $this->CoreUser[0][CoreProfile::TABLE_ID];
	}

	public function SetCookies()
	{
		$Time = time()+(3600*self::HOURS);
		$Year = time() + 31536000;
		setcookie("user",$this->CoreUser[0]['user'],$Time,"/");
		setcookie("password",$this->CoreUser[0]['password'],$Time,"/");
		if($this->RememberUser())
		{
			setcookie('rememberuser',$this->CoreUser[0]['user'],$Year,"/");
			setcookie('rememberpassword',$this->Password,$Year,"/");
		}elseif($_POST['rememberuser'] && $_POST['rememberuser']!=1){
			$Past = time()-100;
			setcookie('rememberuser','gone',$Past,"/");
			setcookie('rememberpassword','gone',$Past,"/");
		}
	}

	protected function QueryMaxTries()
	{
		$Success = Core::Insert('core_log_login','user,password,ip,tries,event',"'".$this->User."','".$this->Password."','".$this->GetIP()."','".$this->Tries."','Inhabilitado por Revocaci&oacute;n'");
		$SuccessInhabilitation 	= Core::Update('core_user',"tries = 0, status = 'I'","user = '".$this->User."'");
		return ($Success && $SuccessInhabilitation);
	}

	protected function QueryLogin()
	{
		$SuccessReset 			= Core::Update('core_user',"tries = 0, last_access = NOW()","user = '".$this->User."'");
		$SuccessLogin 			= Core::Insert('core_log_login','user,ip,event',"'".$this->User."','".$this->GetIP()."','OK'");
		return $SuccessLogin && $SuccessReset;
	}

	protected function QueryPasswordFail()
	{
		$SuccessIncreaseTries 	= Core::Update('core_user',"tries = '".$this->Tries."'","user = '".$this->User."'");
		$SuccessWrongPassword 	= Core::Insert('core_log_login',"user,password,ip,tries,event","'".$this->User."','".$this->Password."','".$this->GetIP()."','".$this->Tries."','Clave Incorrecta'");

		if($SuccessIncreaseTries && $SuccessWrongPassword){
			return true;
		}else{
			return false;
		}
	}

	protected function QueryWrongUser()
	{
		$SuccessWrongUser	= Core::Insert('core_log_login',"user,password,ip,event","'".$this->User."','".$this->Password."','".$this->GetIP()."','Usuario invalido'");
		if($SuccessWrongUser){
			return true;
		}else{
			return false;
		}
	}
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// public function StartLogin()
	// {
	// 	$this->SetData($_POST['user'],$_POST['password'],$_POST['rememberuser']);

	// 	/* PROCESS */
	// 	if($this->UserExists())/* User Existence */
	// 	{
	// 		$this->SetUser();
	// 		if($this->IsMaxTries())/* Attempts to Login */
	// 		{
	// 			$this->QueryMaxTries(); /* Max Tries Reached */
	// 			echo "1";
	// 		}else{
	// 			if($this->PassMatch()) /* Password Match*/
	// 			{
	// 				//if($this->checkCustomer())
	// 				if($this->User && $this->Password)
	// 				{
	// 					$this->SetSessionVars();
	// 					$this->SetCookies();
	// 					$this->QueryLogin();
	// 				}else{
	// 				 	echo "4";
	// 				}
	// 			}else{
	// 				$this->QueryPasswordFail(); /* Password does not Match*/
	// 				echo "2";
	// 			}
	// 		}
	// 	}else{
	// 		$this->QueryWrongUser(); /* Nonexistent User */
	// 		echo "3";
	// 	}
	// }
	
	public function StartLogin()
	{
		$this->SetData($_POST['user'],$_POST['password'],$_POST['rememberuser']);

		/* PROCESS */
		if($this->UserExists())/* User Existence */
		{
			$this->SetUser();
			if($this->PassMatch()) /* Password Match*/
			{
				if($this->IsMaxTries())/* Attempts to Login */
				{
					$this->QueryMaxTries(); /* Max Tries Reached */
					echo "1";
				}else{
					if($this->User && $this->Password)
					{
						$this->SetSessionVars();
						$this->SetCookies();
						$this->QueryLogin();
					}else{
					 	echo "4";
					}
				}
			}else{
				$this->QueryPasswordFail(); /* Password does not Match*/
				echo "2";
			}
		}else{
			$this->QueryWrongUser(); /* Nonexistent User */
			echo "3";
		}
	}
	
	public function Logout()
	{
		session_destroy();
		//Unset Cookies
		setcookie("rollerservice", "", 0 ,"/");
		setcookie(CoreUser::TABLE_ID, "", 0 ,"/");
		setcookie("profile_id", "", 0 ,"/");
		setcookie("first_name", "", 0 ,"/");
		setcookie("last_name", "", 0 ,"/");
		setcookie("user", "", 0 ,"/");
		setcookie("password", "", 0 ,"/");
	}
}


?>
