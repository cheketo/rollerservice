<?php

class CoreGroup
{
	use CoreSearchList;
	
	var $Profiles 		= array();
	var $Menues 		= array();
	var $Users 			= array();
	var $Relations 		= array();
	var $RelationsUser	= array();
	var $Data 			= array();
	var $Groups 		= array();
	var $ImgGalDir		= "../../../../skin/images/groups/";
	var $ID;

	const DEFAULTIMG		= "../../../../skin/images/groups/default/groupgen.jpg";

	public function __construct($ID=0)
	{
		
		$this->ID = $ID;
		if($ID>0)
			$this->GetData();
	}

	public function GetDefaultImg()
	{
		return self::DEFAULTIMG;
	}
	
	public function GetImg()
	{
		if(!$this->Data['image'])
			return $this->GetDefaultImg();
		else
			return $this->Data['image'];
	}

	public function GetUserGroups($User=0)
	{
		if($User!=0)
		{
			if(empty($this->UserGroups))
			{
				$Relations	= $this->GetUserRelations($User);
				foreach($Relations as $Relation)
				{
					$this->UserGroups[]	= $Relation['group_id'];
				}
			}
		}
		return $this->UserGroups;
	}

	public function GetCheckedMenues()
	{
		if(count($this->Menues)<1)
		{
			$Relations	= $this->GetRelations();
			foreach($Relations as $Relation)
			{
				$this->Menues[]	= $Relation['menu_id'];
			}
		}
		return $this->Menues;
	}

	public function GetRelations()
	{
		if(!$this->Relations)
			$this->Relations = Core::Select('core_relation_menu_group','*',"group_id = ".$this->ID);
		return $this->Relations;
	}

	public function GetUserRelations($User)
	{
		
		if(!$this->RelationsUser)
			$this->RelationsUser = Core::Select('core_relation_user_group','*',"user_id = ".$User);
		//echo $this->LastQuery();
		return $this->RelationsUser;
	}

	public function GetUsers()
	{
		if(!$this->Users)
			$this->Users = Core::Select('core_user a INNER JOIN core_relation_user_group b ON (a.user_id=b.user_id)','a.*',"b.group_id=".$this->ID." AND a.status <> 'I'");
		return $this->Users;
	}

	public function GetGroups($ProfileID=0,$User=0)
	{
		$HTML 				= '<h4 class="subTitleB"><i class="fa fa-users"></i> Grupos</h4><select id="groups" class="form-control chosenSelect" multiple="multiple" data-placeholder="Seleccione los grupos">';
		if($ProfileID!=0)
		{
			$CheckedGroups 	= $this->GetUserGroups($User);
			$Groups			= Core::Select('core_group','*',"organization_id=".$_SESSION['organization_id']." AND status='A'  AND group_id IN (SELECT group_id FROM core_relation_group_profile WHERE profile_id = ".$ProfileID.")","title");			

			foreach($Groups as $Group)
			{
				if($CheckedGroups && in_array($Group['group_id'],$CheckedGroups))
				{
					$Selected = ' selected="selected" ';
				}else{
					$Selected = '';
				}
				$HTML		.= '<option '.$Selected.' value="'.$Group['group_id'].'">'.$Group['title'].'</option>';
			}
		}
		return $HTML.'</select>';
	}
	
	public function GetProfiles()
	{
		if(!$this->Profiles)
		{
			$Rs 	= Core::Select('core_profile','*',"status = 'A' AND profile_id IN (SELECT profile_id FROM core_relation_group_profile WHERE group_id=".$this->ID.")","title");
			$this->Profiles = $Rs;
		}
		return $this->Profiles;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// SEARCHLIST FUNCTIONS ///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function MakeRegs($Mode="List")
	{
		$Rows	= $this->GetRegs();
		//echo $this->LastQuery();
		foreach($Rows as $Row)
		{
			$Row			= new CoreGroup($Row['group_id']);
			$ID 			= $Row->ID;
			$AllProfiles	= $Row->GetProfiles();
			$Profiles		= '';
			foreach($AllProfiles as $Profile)
			{
				$Profiles .= '<span class="label label-primary">'.$Profile['title'].'</span> ';
			}
			if(!$Profiles) $Profiles = 'Ninguno';
			$Actions	= 	'<span class="roundItemActionsGroup"><a href="edit.php?id='.$ID.'"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
			if($Row->Data['status']=="A")
			{
				$Actions	.= '<a class="deleteElement" process="'.PROCESS.'" id="delete_'.$ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
			}else{
				$Actions	.= '<a class="activateElement" process="'.PROCESS.'" id="activate_'.$ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			}
			$Actions	.= '</span>';
			switch(strtolower($Mode))
			{
				case "list":
					$RowBackground = $i % 2 == 0? '':' listRow2 ';
					$Regs	.= '<div class="row listRow'.$RowBackground.'" id="row_'.$ID.'" title="'.$Row->Data['title'].'">
									<div class="col-lg-4 col-md-4 col-sm-10 col-xs-10">
										<div class="listRowInner">
											<img class="img-circle" src="'.$Row->Data['image'].'" alt="'.$Row->Data['title'].'">
											<span class="smallTitle">T&iacute;tulo</span>
											<span class="listTextStrong">'.$Row->Data['title'].'</span>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 hideMobile990">
										<div class="listRowInner">
											<span class="smallTitle">Perfiles</span>
											<span class="listTextStrong">
												'.$Profiles.'
											</span>
										</div>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2 hideMobile990"></div>
									<div class="listActions flex-justify-center Hidden">
										<div>'.$Actions.'</div>
									</div>
								</div>';
				break;
				case "grid":
				$Regs	.= '<li id="grid_'.$ID.'" class="RoundItemSelect roundItemBig '.$Restrict.'" title="'.$Row->Data['title'].'">
						            <div class="flex-allCenter imgSelector">
						              <div class="imgSelectorInner">
						                <img src="'.$Row->Data['image'].'" alt="'.$Row->Data['title'].'" class="img-responsive">
						                <div class="imgSelectorContent">
						                  <div class="roundItemBigActions">
						                    '.$Actions.'
						                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
						                  </div>
						                </div>
						              </div>
						              <div class="roundItemText">
						                <p><b>'.$Row->Data['title'].'</b></p>
						              </div>
						            </div>
						          </li>';
				break;
			}
        }
        if(!$Regs) $Regs.= '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron grupos.</h4><p>Puede crear un nuevo grupo haciendo click <a href="new.php">aqui</a>.</p></div>';
		return $Regs;
	}
	
	protected function InsertSearchField()
	{
		return '<!-- Title -->
          <div class="input-group">
            <span class="input-group-addon order-arrows sort-activated" order="title" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','title','','form-control','placeholder="T&iacute;tulo"').'
          </div>
          <!-- Group -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="profile" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('select','profile','','form-control','',Core::Select('core_profile','profile_id,title',"organization_id=".$_SESSION['organization_id']." AND status='A'","title"),'', 'Perfil').'
          </div>';
	}
	
	protected function InsertSearchButtons()
	{
		return '<!-- New User Button -->
		    	<a href="new.php"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-user-plus"></i> Nuevo Grupo</button></a>
		    	<!-- /New User Button -->';
	}
	
	public function ConfigureSearchRequest()
	{
		$this->SetTable('core_profile AS p,core_group AS g, core_relation_group_profile AS r');
		$this->SetFields('g.*,p.title AS profile_title');
		$this->SetWhere("g.organization_id = ".$_SESSION['organization_id']);
		//$this->AddWhereString(" AND a.profile_id = p.profile_id");
		$this->SetOrder('title');
		$this->SetGroupBy("g.group_id");
		
		foreach($_POST as $Key => $Value)
		{
			$_POST[$Key] = $Value;
		}
			
		if($_POST['title']) $this->SetWhereCondition("g.title","LIKE","%".$_POST['title']."%");
		if($_POST['profile'])
		{
			$this->AddWhereString(" AND g.group_id = r.group_id AND r.profile_id = p.profile_id AND p.profile_id = ".$_POST['profile']);	
		}
		if($_REQUEST['status'])
		{
			if($_GET['status']) $this->SetWhereCondition("g.status","=", $_GET['status']);
			else
				if($_POST['status']) $this->SetWhereCondition("g.status","=", $_POST['status']);	
		}else{
			$this->SetWhereCondition("g.status","=","A");
		}
		if($_POST['view_order_field'])
		{
			if(strtolower($_POST['view_order_mode'])=="desc")
				$Mode = "DESC";
			else
				$Mode = $_POST['view_order_mode'];
			
			$Order = strtolower($_POST['view_order_field']);
			switch($Order)
			{
				case "profile": 
					$this->AddWhereString(" AND g.group_id = r.group_id AND r.profile_id = p.profile_id");
					$Order = 'title';
					$Prefix = "p.";
				break;
				default:
					$Prefix = "g.";
				break;
			}
			$this->SetOrder($Prefix.$Order." ".$Mode);
		}
		if($_POST['regsperview'])
		{
			$this->SetRegsPerView($_POST['regsperview']);
		}
		if(intval($_POST['view_page'])>0)
			$this->SetPage($_POST['view_page']);
	}

	public function MakeList()
	{
		return $this->MakeRegs("List");
	}

	public function MakeGrid()
	{
		return $this->MakeRegs("Grid");
	}

	public function GetData()
	{
		if(count($this->Data)<1)
		{
			$Data 		= Core::Select("core_group","*","group_id=".$this->ID);
			$this->Data = $Data[0];
		}
		return $this->Data;
	}
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function Insert()
	{
		$Image 		= $_POST['newimage'];	
		$Title		= ucfirst($_POST['title']);
		$Profiles	= $_POST['profiles'] ? explode(",",$_POST['profiles']) : array();
		$Menues		= $_POST['menues'] ? explode(",",$_POST['menues']) : array();
		$Users		= $_POST['users'] ? explode(",",$_POST['users']) : array();
		$NewID		= Core::Insert('core_group','organization_id,title,creation_date',$_SESSION['organization_id'].",'".$Title."',NOW()");
		$New 		= new CoreGroup($NewID);
		$Dir 		= array_reverse(explode("/",$Image));
		if($Dir[1]=="group")
		{
			$Temp 	= $Image;
			if(file_exists($Image))
				unlink($Image);
			$Image 	= $New->ImgGalDir.$Dir[0];
			copy($Temp,$Image);
			if(file_exists($Temp)) unlink($Temp);
			
		}
		Core::Update('core_group',"image='".$Image."'","group_id=".$NewID);
		
		
		foreach($Profiles as $Profile)
		{
			if(intval($Profile)>0)
				$Values .= !$Values? $NewID.",".$Profile : "),(".$NewID.",".$Profile;
		}
		if($Values) Core::Insert('core_relation_group_profile','group_id,profile_id',$Values);
		$Values = "";
		foreach($Menues as $Menu)
		{
			if(intval($Menu)>0)
				$Values .= !$Values? $NewID.",".$Menu : "),(".$NewID.",".$Menu;
		}
		if($Values) Core::Insert('core_relation_menu_group','group_id,menu_id',$Values);
		$Values = "";
		foreach($Users as $User)
		{
			if(intval($User)>0)
				$Values .= !$Values? $NewID.",".$User : "),(".$NewID.",".$User;
		}
		if($Values) Core::Insert('core_relation_user_group','group_id,user_id',$Values);
	}
	
	public function Update()
	{
		$ID 	= $_POST['id'];
		$Edit	= new CoreGroup($ID);
		
		$Image 		= $_POST['newimage'];
		$Dir 		= array_reverse(explode("/",$Image));
		if($Dir[1]=="group")
		{
			$Temp 	= $Image;
			if(file_exists($Image))
				unlink($Image);
			$Image 	= $Edit->ImgGalDir.$Dir[0];
			copy($Temp,$Image);
		}
		$Title		= ucfirst($_POST['title']);
		$Profiles	= $_POST['profiles'] ? explode(",",$_POST['profiles']) : array();
		$Menues		= $_POST['menues'] ? explode(",",$_POST['menues']) : array();
		$Users		= $_POST['users'] ? explode(",",$_POST['users']) : array();
		
		$Update		= Core::Update('core_group',"title='".$Title."',image='".$Image."'","group_id=".$ID);
		//echo $this->LastQuery();
		Core::Delete('core_relation_group_profile',"group_id = ".$ID);
		Core::Delete('core_relation_menu_group',"group_id = ".$ID);
		Core::Delete('core_relation_user_group',"group_id = ".$ID);
		foreach($Profiles as $Profile)
		{
			if(intval($Profile)>0)
				$Values .= !$Values? $ID.",".$Profile : "),(".$ID.",".$Profile;
		}
		if($Values) Core::Insert('core_relation_group_profile','group_id,profile_id',$Values);
		$Values = "";
		foreach($Menues as $Menu)
		{
			if(intval($Menu)>0)
				$Values .= !$Values? $ID.",".$Menu : "),(".$ID.",".$Menu;
		}
		if($Values) Core::Insert('core_relation_menu_group','group_id,menu_id',$Values);
		$Values = "";
		foreach($Users as $User)
		{
			if(intval($User)>0)
				$Values .= !$Values? $ID.",".$User : "),(".$ID.",".$User;
		}
		if($Values) Core::Insert('core_relation_user_group','group_id,user_id',$Values);
	}
	
	public function Activate()
	{
		$ID	= $_POST['id'];
		Core::Update('core_group',"status = 'A'","group_id=".$ID);
	}
	
	public function Delete()
	{
		$ID	= $_POST['id'];
		Core::Update('core_group',"status = 'I'","group_id=".$ID);
	}
	
	public function Search()
	{
		$this->ConfigureSearchRequest();
		echo $this->InsertSearchResults();
	}
	
	public function Newimage()
	{
		if(count($_FILES['image'])>0)
		{
			if($_POST['newimage']!=$this->GetDefaultImg() && file_exists($_POST['newimage']))
				unlink($_POST['newimage']);
			$TempDir = $this->ImgGalDir;
			$Name	= "group".intval(rand()*rand()/rand());
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
		$Title 			= strtolower($_POST['title']);
		$ActualTitle 	= strtolower($_POST['actualtitle']);

	    if($ActualTitle)
	    	$TotalRegs  = Core::NumRows('core_group','*',"title = '".$Title."' AND title <> '".$ActualTitle."' AND organization_id = ".$_SESSION['organization_id']);
    	else
		    $TotalRegs  = Core::NumRows('core_group','*',"title = '".$Title."' AND organization_id = ".$_SESSION['organization_id']);
		if($TotalRegs>0) echo $TotalRegs;
	}
}


?>
