<?php

class CoreUser 
{
	use CoreSearchList,CoreCrud;
	
	var	$ID;
	var	$FirstName;
	var	$LastName;
	var	$FullName;
	var $FullUserName;
	var	$ProfileID;
	var	$ProfileName;
	var	$User;
	var	$Email;
	var	$Img;
	var $Data;
	var $DefaultImg	= '../../../../skin/images/users/default/default.jpg';
	var $DefaultImgDir = '../../../../skin/images/users/default';
	var $ImgGalDir = '../../../../skin/images/users/';
	var $LastAccess;
	var $Organization 	= array();
	var $Groups 		= array();
	var $Parent 		= array();
	var $Menues 		= array();
	var $DefaultImages 	= array();
	var $UserImages 	= array();
	const TABLE			= 'core_user';
	const TABLE_ID		= 'user_id';
	const SEARCH_TABLE	= 'core_view_user_list';

	public function __construct($ID='')
	{
		
		$this->ID 			= $ID==''? $_SESSION[self::TABLE_ID] : $ID;
		$this->Data			= Core::Select(self::TABLE,'*',self::TABLE_ID."= '".$this->ID."'")[0];
		$this->FirstName	= $this->Data['first_name'];
		$this->LastName		= $this->Data['last_name'];
		$this->User			= $this->Data['user'];
		$this->Email		= $this->Data['email'];
		$this->ProfileID	= $this->Data['profile_id'];
		$this->Img			= file_exists($this->Data['img'])? $this->Data['img'] : $this->DefaultImg;
		$this->FullName		= $this->FirstName." ".$this->LastName;
		$this->FullUserName	= $this->FirstName." ".$this->LastName." (".$this->User.")";
		$this->LastAccess	= $this->Data['last_access']=="0000-00-00 00:00:00"? "Nunca se ha conectado":"&Uacute;ltima conexi&oacute;n: ".Core::DateTimeFormat($this->Data['last_access']);
		$Profile			= Core::Select('core_profile','*'," profile_id = ".$this->ProfileID);
		$this->ProfileName	= $Profile[0]['title'];
	}
	
	public function IsOwner()
	{
		return $this->Data['group_id'] == 360;
	}
	
	public function GetOrganization()
	{
		if(!$this->Organization)
		{
			$Rs 	= Core::Select("core_organization",'*',"organization_id =".$this->Data['organization_id']);
			$this->Organization = $Rs[0];
		}
		return $this->Organization;
	}

	public function GetGroups()
	{
		if(!$this->Groups)
		{
			$Rs 	= Core::Select('core_group','*',"status = 'A' AND group_id IN (SELECT group_id FROM core_relation_user_group WHERE ".self::TABLE_ID."=".$this->ID.")","title");
			$this->Groups = $Rs;
		}
		return $this->Groups;

	}

	public function GetImg()
	{
		return $this->Img;
	}

	public function GetProfileID()
	{
		return $this->ProfileID;
	}

	

	public function GetCheckedMenues()
	{
		if(count($this->Menues)<1)
		{
			$Relations	= Core::Select('core_relation_user_menu','*',self::TABLE_ID." = ".$this->ID);
			foreach($Relations as $Relation)
			{
				$this->Menues[]	= $Relation['menu_id'];
			}
		}
		return $this->Menues;

	}

	public function GetParents()
	{
		$Parents	= Core::Select('core_menu','DISTINCT(parent_id)',"parent_id <> 0 AND status <> 'I'");

		foreach($Parents as $Parent){
			$this->Parents[] = $Parent['parent_id'];
		}
	}

	public function IsDisabled($ParentID)
	{
		return in_array($ParentID,$this->Menues) ? '' : ' disabled="disabled" ';
	}

	public function DefaultImages($Dir='')
	{
		if(!$Dir) $Dir = $this->DefaultImgDir;

		if(count($this->DefaultImages)<1)
		{
			$Elements = scandir($Dir);
			foreach($Elements as $Image)
			{
				if(strlen($Image)>4 && $Image[0]!=".")
				{
					$this->DefaultImages[] = $Dir."/".$Image;
				}
			}
		}

		return $this->DefaultImages;
	}

	public function UserImages($Dir='')
	{
		if(!$Dir) $Dir = $this->ImgGalDir();

		if(count($this->UserImages)<1)
		{
			$Elements = scandir($Dir);
			foreach($Elements as $Image)
			{
				if(strlen($Image)>4 && $Image[0]!=".")
				{
					$this->UserImages[] = $Dir."/".$Image;
				}
			}
		}

		return $this->UserImages;
	}

	public function ImgGalDir()
	{
		$TempDir = $this->ImgGalDir.$this->ID."/";
		if(!file_exists($TempDir)) mkdir($TempDir);
		return $TempDir;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// SEARCHLIST FUNCTIONS ///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function MakeRegs($Mode="List")
	{
		$Rows	= $this->GetRegs();
		// echo Core::LastQuery();
		for($i=0;$i<count($Rows);$i++)
		{
			$Row	=	new CoreUser($Rows[$i][self::TABLE_ID]);
			$UserGroups = $Row->GetGroups();
			$Groups='';
			foreach($UserGroups as $Group)
			{
				$Groups .= '<span class="label label-warning">'.$Group['title'].'</span> ';
			}
			if(!$Groups) $Groups = 'Ninguno';
			$Actions	= 	'<span class="roundItemActionsGroup"><a href="edit.php?id='.$Row->ID.'"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
			if($Row->Data['status']=="A")
			{
				if($Row->ID!=$_SESSION[self::TABLE_ID])
				{
					$Actions	.= '<a class="deleteElement" process="'.PROCESS.'" id="delete_'.$Row->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
					$Restrict	= '';
				}else{
					$Restrict	= ' undeleteable ';
				}
			}else{
				$Actions	.= '<a class="activateElement" process="'.PROCESS.'" id="activate_'.$Row->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			}
			$Actions	.= '</span>';
			switch(strtolower($Mode))
			{
				case "list":
					
					$RowBackground = $i % 2 == 0? '':' listRow2 ';
					$Regs	.= '<div class="row listRow'.$RowBackground.$Restrict.'" id="row_'.$Row->ID.'" title="'.$Row->FullName.'">
									<div class="col-lg-3 col-md-3 col-sm-10 col-xs-10">
										<div class="listRowInner">
											<img class="img-circle" src="'.$Row->Img.'" alt="'.$Row->FullName.'">
											<span class="listTextStrong">'.$Row->FullName.' ('.$Row->User.')</span>
											<span class="smallDetails">'.$Row->LastAccess.'<!--22/25/24 | 22:00Hs.--></span>
										</div>
									</div>
									<div class="col-lg-2 col-md-3 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="smallTitle">Email</span>
											<span class="emailTextResp">'.$Row->Email.'</span>
										</div>
									</div>
									<div class="col-lg-3 col-md-2 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="smallTitle">Perfil</span>
											<span class="listTextStrong"><span class="label label-primary">'.ucfirst($Row->ProfileName).'</span></span>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 hideMobile990">
										<div class="listRowInner">
											<span class="smallTitle">Grupos</span>
											<span class="listTextStrong">
												'.$Groups.'
											</span>
										</div>
									</div>
									<div class="col-lg-1 col-md-1 col-sm-1 hideMobile990"></div>
									<div class="listActions flex-justify-center Hidden">
										<div>'.$Actions.'</div>
									</div>
								</div>';
				break;
				case "grid":
				$Regs	.= '<li id="grid_'.$Row->ID.'" class="RoundItemSelect roundItemBig'.$Restrict.'" title="'.$Row->FullName.'">
						            <div class="flex-allCenter imgSelector">
						              <div class="imgSelectorInner">
						                <img src="'.$Row->Img.'" alt="'.$Row->FullName.'" class="img-responsive">
						                <div class="imgSelectorContent">
						                  <div class="roundItemBigActions">
						                    '.$Actions.'
						                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
						                  </div>
						                </div>
						              </div>
						              <div class="roundItemText">
						                <p><b>'.$Row->FullName.'</b></p>
						                <p>('.$Row->User.')</p>
						                <p>'.ucfirst($Row->ProfileName).'</p>
						              </div>
						            </div>
						          </li>';
				break;
			}
        }
        if(!$Regs) $Regs.= '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron usuarios.</h4><p>Puede crear un nuevo usuario haciendo click <a href="new.php">aqui</a>.</p></div>';
		return $Regs;
	}
	
	protected function InsertSearchField()
	{
		return '<!-- First Name -->
		<div class="row">
          <div class="input-group col-xs-3">
            <span class="input-group-addon order-arrows sort-activated" order="first_name" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','first_name','','form-control','placeholder="Nombre"').'
          </div>
          <!-- Last Name -->
          <div class="input-group col-xs-3">
            <span class="input-group-addon order-arrows" order="last_name" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','last_name','','form-control','placeholder="Apellido"').'
          </div>
          <!-- User -->
          <div class="input-group col-xs-3">
            <span class="input-group-addon order-arrows" order="user" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','user','','form-control','placeholder="Usuario"').'
          </div>
          <!-- Email -->
          <div class="input-group col-xs-3">
            <span class="input-group-addon order-arrows" order="email" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','email','','form-control','placeholder="Email"').'
          </div>
          <!-- Profile -->
          <div class="input-group col-xs-3">
            <span class="input-group-addon order-arrows btnFormAddon" order="profile" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('select','user_profile','','form-control chosenSelect','data-placeholder="Perfil"',Core::Select('core_profile','profile_id,title',"organization_id=".$_SESSION['organization_id']." AND status='A' AND profile_id >= ".$_SESSION['profile_id']),' ', 'Todos los Perfiles').'
          </div>
          <!-- Group -->
          <div class="input-group col-xs-3">
            <span class="input-group-addon order-arrows btnFormAddon" order="group" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('multiple','user_groups','','form-control chosenSelect','data-placeholder="Grupo"',Core::Select('core_group','group_id,title',"organization_id=".$_SESSION['organization_id']." AND status='A' AND group_id IN (SELECT group_id FROM core_relation_group_profile WHERE profile_id >= ".$_SESSION['profile_id'].")","title"),' ', '').'
          </div>
          </div>';
	}
	
	protected function InsertSearchButtons()
	{
		return '<!-- New User Button -->
		    	<a href="new.php" class="hint--bottom hint--bounce hint--success" aria-label="Nuevo Usuario"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-user-plus"></i></button></a>
		    	<!-- /New User Button -->';
	}
	
	public function ConfigureSearchRequest()
	{
		if($_GET['status'])
		{
			$_POST['status'] = $_GET['status'];
		}else{
			$_POST['status'] = 'A';
		}
		if($_SESSION['profile_id']!=333)
		{
			$Fields['profile_id'] = array('value'=>$this->ProfileID,'condition'=>'>=');
		}
		$Fields['organization_id'] = array('value'=>$_SESSION['organization_id'],'condition'=>'=');
		if($_POST['first_name']) $Fields['first_name'] = array('value'=>$_POST['first_name']);
		if($_POST['first_name']) $Fields['last_name'] = array('value'=>$_POST['last_name']);
		if($_POST['email']) $Fields['email'] = array('value'=>$_POST['email']);
		if($_POST['user']) $Fields['user'] = array('value'=>$_POST['user']);
		if($_POST['user_profile']) $Fields['profile_id'] = array('value'=>$_POST['user_profile'],'condition'=>'=');
		if($_POST['user_groups']) $Fields['group_id'] = array('value'=>$_POST['user_groups'],'condition'=>'IN');
		if($_POST['status']) $Fields['status'] = array('value'=>$_POST['status'],'condition'=>'=');
		$this->SetSearchRequest($Fields,$_POST['view_order_field'],$_POST['view_order_mode'],$_POST['regsperview'],$_POST['view_page']);
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function Insert()
	{
		$Image 		= $_POST['newimage'];
		$User		= strtolower($_POST['user']);
		$Password	= sha1($_POST['password']);
		$FirstName	= ucfirst($_POST['first_name']);
		$LastName	= ucfirst($_POST['last_name']);
		$Email 		= strtolower($_POST['email']);
		$ProfileID	= $_POST['profile'];
		$Groups		= $_POST['groups'] ? explode(",",$_POST['groups']) : array();
		$Menues		= $_POST['menues'] ? explode(",",$_POST['menues']) : array();
		$NewID		= Core::Insert(self::TABLE,'user,password,first_name,last_name,email,profile_id,img,creation_date,creator_id,organization_id',"'".$User."','".$Password."','".$FirstName."','".$LastName."','".$Email."','".$ProfileID."','".$Image."',NOW(),".$_SESSION[self::TABLE_ID].",".$_SESSION['organization_id']);
		$New 		= new CoreUser($NewID);
		$Dir 		= array_reverse(explode("/",$Image));
		if($Dir[1]!="default")
		{
			$Temp 	= $Image;
			$Image 	= $New->ImgGalDir().$Dir[0];
			copy($Temp,$Image);
		}
		Core::Update(self::TABLE,"img='".$Image."'",self::TABLE_ID."=".$NewID);
		for($i=0;$i<count($Groups);$i++)
		{
			if(intval($Groups[$i])>0)
				$Values .= !$Values? $NewID.",".$Groups[$i] : "),(".$NewID.",".$Groups[$i];
		}
		if(!empty($Groups)) Core::Insert('core_relation_user_group',self::TABLE_ID.',group_id',$Values);
		$Values = "";
		for($i=0;$i<count($Menues);$i++)
		{
			if(intval($Menues[$i])>0)
				$Values .= !$Values? $NewID.",".$Menues[$i] : "),(".$NewID.",".$Menues[$i];
		}
		if(!empty($Menues)) Core::Insert('core_relation_user_menu',self::TABLE_ID.',menu_id',$Values);
	}
	
	public function Update()
	{
		$ID 	= $_POST['id'];
		$Edit	= new CoreUser($ID);
		if($_POST['password'])
		{
			$Password	= sha1($_POST['password']);
			$PasswordFilter	= ",password='".$Password."'";
		}
		$Image 		= $_POST['newimage'];
		$User		= strtolower($_POST['user']);
		$FirstName	= $_POST['first_name'];
		$LastName	= $_POST['last_name'];
		$Email 		= $_POST['email'];
		$ProfileID	= $_POST['profile'];
		$Groups		= $_POST['groups'] ? explode(",",$_POST['groups']) : array();
		$Menues		= $_POST['menues'] ? explode(",",$_POST['menues']) : array();
		$Dir 		= array_reverse(explode("/",$Image));
		
		if($Dir[1]!="default" && $ID!=$this->ID)
		{
			$Temp 	= $Image;
			$Image 	= $Edit->ImgGalDir().$Dir[0];
			copy($Temp,$Image);
		}
		$Update		= Core::Update(self::TABLE,"user='".$User."'".$PasswordFilter.",first_name='".$FirstName."',last_name='".$LastName."',email='".$Email."',profile_id='".$ProfileID."',img='".$Image."'",self::TABLE_ID."=".$ID);
		//echo $this->LastQuery();
		Core::Delete('core_relation_user_group',self::TABLE_ID." = ".$ID);
		Core::Delete('core_relation_user_menu',self::TABLE_ID." = ".$ID);
		foreach($Groups as $Group)
		{
			if(intval($Group)>0)
				$Values .= !$Values? $ID.",".$Group : "),(".$ID.",".$Group;
		}
		if($Values) Core::Insert('core_relation_user_group',self::TABLE_ID.',group_id',$Values);
		//echo $this->LastQuery();
		$Values = "";
		foreach($Menues as $Menu)
		{
			if(intval($Menu)>0)
				$Values .= !$Values? $ID.",".$Menu : "),(".$ID.",".$Menu;
		}
		if($Values) Core::Insert('core_relation_user_menu',self::TABLE_ID.',menu_id',$Values);
		// echo Core::LastQuery();
	}
	
	public function Activate()
	{
		$ID	= $_POST['id'];
		Core::Update(self::TABLE,"status = 'A'",self::TABLE_ID."=".$ID);
	}
	
	public function Delete()
	{
		$ID	= $_POST['id'];
		Core::Update(self::TABLE,"status = 'I'",self::TABLE_ID."=".$ID);
	}
	
	public function Newimage()
	{
		if(count($_FILES['image'])>0)
		{
			// $Images = self::UserImages(); // Para cuando se requiera limitar la cantidad de imágenes.
			$TempDir = $this->ImgGalDir();
			$Name	= "user".intval(rand()*rand()/rand())."__".$this->ID;
			$Img	= new CoreFileData($_FILES['image'],$TempDir,$Name);
			echo $Img	-> BuildImage(200,200);
		}
	}
	
	public function Deleteimage()
	{
		$SRC	= $_POST['src'];
		unlink($SRC);
	}
	
	public function Validate()
	{
		$User 			= strtolower($_POST['user']);
		$ActualUser 	= strtolower($_POST['actualuser']);

	    if($ActualUser)
	    	$TotalRegs  = Core::NumRows(self::TABLE,'*',"user = '".$User."' AND user<> '".$ActualUser."'");
    	else
		    $TotalRegs  = Core::NumRows(self::TABLE,'*',"user = '".$User."'");
		if($TotalRegs>0) echo $TotalRegs;
	}
	
	public function Validate_email()
	{
		$Email 			= strtolower($_POST['email']);
		$ActualEmail 	= strtolower($_POST['actualemail']);

	    if($ActualEmail)
	    	$TotalRegs  = Core::NumRows(self::TABLE,'*',"email = '".$Email."' AND email<> '".$ActualEmail."'");
    	else
		    $TotalRegs  = Core::NumRows(self::TABLE,'*',"email = '".$Email."'");
		if($TotalRegs>0) echo $TotalRegs;
	}
	
	public function Fillgroups()
	{
		$Profile 	= $_POST['profile'];
		$User 		= $_POST['id'];
        $Groups 	= new CoreGroup();
        echo $Groups->GetGroups($Profile,$User);
	}
}
?>
