<?php

class CoreMenu 
{
	use CoreSearchList;
	
	var $IDs 				= array();
	var $MenuData 			= array();
	var $Parents 			= array();
	var $CheckedMenues 		= array();
	var $Link 				= "";
	var $Children 			= array();
	var $Groups 			= array();
	var $Profiles 			= array();
	const PROFILE			= 333;
	const SWITCHER_URL		= 'core/modules/menu/switcher.php';
	const TABLE				= 'core_menu';
	const TABLE_ID			= 'menu_id';

	public function __construct($ID=0)
	{
		if($ID>0)
		{
			$this->Data	= Core::Select(self::TABLE,'*',self::TABLE_ID." = ".$ID)[0];
		}else{
			$this->Data	= $this->GetLinkData();
				
		}
	}

	public function GetLinkData()
	{
		if(count($this->Data)<1)
		{
			$Menues 		= Core::Select(self::TABLE,'*',"link LIKE '%".Core::GetLink()."%'");
			$this->Data = $this->ChosenMenu($Menues);

		}
		return $this->Data;
	}

	public function GetTitle()
	{
		return $this->Data['title'];
	}
	
	public function GetHTMLicon()
	{
		return '<i class="icon fa '.$this->Data['icon'].'"></i>';
	}

	public function HasChild($MenuID)
	{
		return count(Core::Select(self::TABLE,self::TABLE_ID,"parent_id = ".$MenuID." AND status = 'A' AND view_status = 'A' AND ".self::TABLE_ID." IN (".implode(",",$this->IDs).")"))>0;
	}
	
	public function ChosenMenu($Menues)
	{
		if(count($Menues)>1)
		{
			$ChosenMenu[1] = 0;
			foreach($Menues as $Key => $Menu)
			{
				$I=-1;
				$Link = $Menu['link'];
				$Link = explode("?",$Link);
				$Args = $Link[1];
				if($Args)
				{
					$Args = explode('&',$Args);
					foreach($Args as $Arg)
					{
						$Arg = explode('=',$Arg);
						if($_GET[$Arg[0]]==$Arg[1])
							$I++;
					}
					if($I>=$ChosenMenu[1])
					{
						$ChosenMenu[0] = $Menues[$Key];
						$ChosenMenu[1] = $I;
					}
				}else{
					if($ChosenMenu[1]==0)
						$ChosenMenu[0] = $Menues[$Key];
				}
			}
			return $ChosenMenu[0];
		}else{
			return $Menues[0];
		}
	}
	
	public function GetActiveMenus($ID=0)
	{
		if($ID==0)
		{
			$Menues = Core::Select(self::TABLE,self::TABLE_ID.',parent_id,link',"link LIKE '%".Core::GetLink()."%'");
			$Menu	= $this->ChosenMenu($Menues);
		}else{
			$Menues = Core::Select(self::TABLE,self::TABLE_ID.',parent_id',self::TABLE_ID."=".$ID);
			$Menu	= $Menues[0];
		}
		$MenuID 	= $Menu[self::TABLE_ID];
		$ParentID	= $Menu['parent_id'];
		if($ParentID==0)
		{
			return $MenuID;
		}else{
			return $MenuID.','.$this->GetActiveMenus($ParentID);
		}
	}

	public function InsertMenu($PorfileID=0,$AdminID=0)
	{
		$this->GetMenues($PorfileID,$AdminID);
		$Rows	= Core::Select(self::TABLE,'*',"parent_id = 0 AND status = 'A' AND view_status = 'A' AND ".self::TABLE_ID." IN (".implode(",",$this->IDs).")","position");
		
		//ACTIVE MENUS FOR NAVBAR
		$ActiveMenus = explode(',',$this->GetActiveMenus());
		$this->ActiveMenus = $ActiveMenus;
		foreach($Rows as $Row)
		{
			
			if($this->HasChild($Row[self::TABLE_ID]))
			{
					$DropDown 		= '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
					$ParentClass 	= ' treeview ';
					$Link 				= '';
			}else{
					$DropDown 		= '';
					$ParentClass 	= '';
					$Link 				= $Row['link'];
			}
			if(in_array($Row[self::TABLE_ID],$this->ActiveMenus))
			{
				$Active 		= ' active ';
			}else{
				$Active 		= '';
			}
			echo '<li class="'.$ParentClass.$Active.'"><a href="'.$Link.'" data-target="#ddmenu'.$Row[self::TABLE_ID].'" class="faa-parent animated-hover"><i class="fa '.$Row['icon'].' faa-tada"></i> <span>'.$Row['title'].'</span>'.$DropDown.'</a>';
				$this->InsertSubMenu($Row[self::TABLE_ID]);
			echo '</li>';
		}
	}

	public function InsertSubMenu($Parent_id)
	{
		$Rows		= Core::Select(self::TABLE,'*',"parent_id = ".$Parent_id." AND status='A' AND view_status = 'A' AND ".self::TABLE_ID." IN (".implode(",",$this->IDs).")","position");
		$NumRows	= count($Rows);
		if($NumRows>0)
		{
			echo '<ul class="treeview-menu">';
			foreach($Rows as $Row)
			{
				
				if($this->HasChild($Row[self::TABLE_ID]))
				{
						$DropDown 		= '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
						$Link 				= '';
				}else{
						$DropDown 		= '';
						$Link 				= $Row['link'];
				}
				if(in_array($Row[self::TABLE_ID],$this->ActiveMenus))
				{
					$Active 		= ' active ';
				}else{
					$Active 		= '';
				}
				echo '<li class="'.$Active.'"><a href="'.$Link.'" data-target="#ddmenu'.$Row[self::TABLE_ID].'" class="faa-parent animated-hover"><i class="fa '.$Row['icon'].' faa-tada"></i> '.$Row['title'].$DropDown.'</a>';
					$this->InsertSubMenu($Row[self::TABLE_ID]);
				echo '</li>';
			}
			echo '</ul>';
		}
	}

	public function InsertBreadCrumbs($ID=0)
	{
		if(Core::GetLink() == self::SWITCHER_URL && $ID == 0)
		{
			$MenuID = !$_GET['id']? "0":$_GET['id'];

			$Menu = Core::Select(self::TABLE,'*',self::TABLE_ID."=".$MenuID);
		}else{
			if($ID==0)
			{
				// $Menues = Core::Select('admin_menu','*',"link LIKE '../".Core::GetLink()."%'");
				// $Menu = $this->ChosenMenu($Menues);
				$Menu = array(0=>$this->GetLinkData());
			}else{
				$Menu = Core::Select(self::TABLE,'*',self::TABLE_ID."= ".$ID);
			}
		}


		$Parent = $Menu[0]['parent_id'];

		$Link = !$Menu[0]['link'] || $Menu[0]['link']=="#"? $_SERVER['DOCUMENT_ROOT'].self::SWITCHER_URL."?id=".$ID:$Menu[0]['link'];



		if($Parent!=0) $this->InsertBreadCrumbs($Parent);
		//
		// echo ' <i class="fa fa-angle-right"></i>';
		
		if($ID==0)
		{
			$Title = '<i class="fa '.$Menu[0]['icon'].'"></i> '.$Menu[0]['title'];
		}else{
			$Title = '<a href="'.$Link.'"><i class="fa '.$Menu[0]['icon'].'"></i> '.$Menu[0]['title'].'</a>';
		}
		echo '<li>'.$Title.'</li>';
	}

	public function GetChildren()
	{
		if(count($this->Children)<1)
		{
			$Children = Core::Select(self::TABLE,'*'," status = 'A' AND view_status='A' AND parent_id = ".$this->Data[self::TABLE_ID]);
			foreach ($Children as $Child)
			{
				if(!$Child['link'] || $Child['link']=="#")
				{
					$Child['link'] = self::SWITCHER_URL."?id=".$Child[self::TABLE_ID];
				}
				$this->Children[] = $Child;
			}

		}
		return $this->Children;
	}

	public function GetMenues($PorfileID=0,$UserID=0)
	{
		$QueryMenues 	= array(0 => 0);
		$Menues 		= array(0 => 0);
		
		if($PorfileID==self::PROFILE)
		{
			$AllowedMenues 	= Core::Select(self::TABLE,self::TABLE_ID,"status = 'A'");
		}else{
			if($PorfileID>0)
			{
				$MGroup 		= array();
				$MGroup[] 		= 0;
				$MenuGroups		= Core::Select('core_relation_menu_group',self::TABLE_ID,"group_id IN (SELECT group_id FROM core_relation_user_group WHERE user_id = ".$UserID.")");

				foreach($MenuGroups as $MenuGroup)
				{
					$MGroup[] =  $MenuGroup[self::TABLE_ID];
				}
				$MenuesGroup = implode(",",$MGroup);

				$AllowedMenues 	= Core::Select(self::TABLE,'DISTINCT('.self::TABLE_ID.')',"public = 'Y' OR ".self::TABLE_ID." IN (SELECT ".self::TABLE_ID." FROM core_relation_menu_profile WHERE profile_id= ".$PorfileID.") OR ".self::TABLE_ID." IN (SELECT ".self::TABLE_ID." FROM core_relation_user_menu WHERE user_id = ".$UserID.") OR ".self::TABLE_ID." IN (".$MenuesGroup.")  AND status = 'A'");

			}else{
				$AllowedMenues 	= Core::Select(self::TABLE,self::TABLE_ID,"public = 'Y' AND status = 'A'");
			}
		}

		foreach($AllowedMenues as $Menu)
		{
			$Menues[]			= $Menu[self::TABLE_ID];
		}

		$this->IDs		= $Menues;
	}

	public function GetParent($Menu_id)
	{
		$Parent = Core::Select(self::TABLE,'title',self::TABLE_ID.'='.$Menu_id);
		return $Parent[0]['title'];
	}

	public function SetCheckedMenues($CheckedMenues)
	{
		$this->CheckedMenues = $CheckedMenues;
	}

	public function GetCheckedMenues()
	{
		return $this->CheckedMenues;
	}

	public function MakeTree($Parent=0)
	{
		$HTML		= '<ul>';
		$Menues 	= Core::Select(self::TABLE,'*',"parent_id = ".$Parent." AND status <> 'I'","position");
		$Parents	= $this->GetParents();
		
		foreach($Menues as $Menu)
		{
			
			$HTML .= '<li data-value="'.$Menu[self::TABLE_ID].'"> <i class="fa '.$Menu['icon'].'"></i> '.$Menu['title'];
			if(in_array($Menu[self::TABLE_ID],$Parents))
			{
				$HTML .= $this->MakeTree($Menu[self::TABLE_ID]);
			}
			$HTML .= '</li>';
		}
		$HTML .= '</ul>';
		return $HTML;
	}

	public function GetParents()
	{
		if(count($this->Parents)<1)
		{
			$Parents	= Core::Select(self::TABLE,'DISTINCT(parent_id)',"parent_id <> 0 AND status <> 'I'");
			foreach($Parents as $Parent)
			{
				$this->Parents[] = $Parent['parent_id'];
			}
		}
		return $this->Parents;
	}
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// SEARCHLIST FUNCTIONS ///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function GetGroups()
	{
		if(!$this->Groups)
		{
			$Rs 	= Core::Select('core_group','*',"status = 'A' AND group_id IN (SELECT group_id FROM core_relation_menu_group WHERE ".self::TABLE_ID."=".$this->Data[self::TABLE_ID].") AND organization_id = ".$_SESSION['organization_id'],"title");
			$this->Groups = $Rs;
			return $this->Groups;
		}
	}
	
	public function GetProfiles()
	{
		if(!$this->Profiles)
		{
			$Rs 	= Core::Select('core_profile','*',"status = 'A' AND profile_id IN (SELECT profile_id FROM core_relation_menu_profile WHERE ".self::TABLE_ID."=".$this->Data[self::TABLE_ID].") AND organization_id = ".$_SESSION['organization_id'],"title");
			$this->Profiles = $Rs;
			return $this->Profiles;
		}
	}

	public function MakeRegs($Mode="List")
	{
		$Rows	= $this->GetRegs();
		//echo $this->LastQuery();
		for($i=0;$i<count($Rows);$i++)
		{
			$Row	=	new CoreMenu($Rows[$i][self::TABLE_ID]);
			$MenuGroups = $Row->GetGroups();
			$Groups = '';
			foreach($MenuGroups as $Group)
			{
				
				$Groups .= '<span class="label label-warning">'.$Group['title'].'</span> ';
			}
			if(!$Groups) $Groups = 'Ninguno';
			$MenuProfiles = $Row->GetProfiles();
			$Profiles = '';
			foreach($MenuProfiles as $Profile)
			{
				
				$Profiles .= '<span class="label label-primary">'.$Profile['title'].'</span> ';
			}
			if(!$Profiles) $Profiles = 'Ninguno';
			
			if($Row->Data['link']=="#")
				$Row->Data['link'] = "";
				
			if($Row->Data['public']=='Y')
				$Row->Data['public'] = 'P&uacute;blico';
			else
				$Row->Data['public'] = 'Privado';
			
			
			$Actions	= 	'<span class="roundItemActionsGroup"><a href="edit.php?id='.$Row->Data[self::TABLE_ID].'"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
			if($Row->Data['status']=="A")
			{
				$Actions	.= '<a class="deleteElement" process="'.$GOLBALS['PROCESS'].'" id="delete_'.$Row->Data[self::TABLE_ID].'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
			}else{
				$Actions	.= '<a class="activateElement" process="'.$GOLBALS['PROCESS'].'" id="activate_'.$Row->Data[self::TABLE_ID].'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			}
			$Actions	.= '</span>';
			switch(strtolower($Mode))
			{
				case "list":
					$RowBackground = $i % 2 == 0? '':' listRow2 ';
					$Regs	.= '<div class="row listRow'.$RowBackground.'" id="row_'.$Row->Data[self::TABLE_ID].'" title="'.$Row->Data['title'].'">
									<div class="col-lg-2 col-md-2 col-sm-10 col-xs-10">
										<div class="listRowInner">
											<span class="smallDetails">Icono</span>
											<span class="itemRowtitle"><i class="fa '.$Row->Data['icon'].'" alt="'.$Row->Data['title'].'"></i></span>
										</div>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="itemRowtitle">'.$Row->Data['title'].'</span>
											<span class="smallDetails">'.$Row->Data['link'].'</span>
										</div>
									</div>
									<div class="col-lg-3 col-md-2 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="smallDetails">Privacidad</span>
											<span class="itemRowtitle">'.$Row->Data['public'].'</span>
										</div>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="smallDetails">Perfiles</span>
											<span class="itemRowtitle">
											'.$Profiles.'
											</span>
										</div>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="smallTitle">Grupos</span>
											<span class="listTextStrong">
												'.$Groups.'
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
					if($Row->Data['link']) $Row->Data['link'] = '<p>('.$Row->Data['link'].')</p>';
					$Regs	.= '<li id="grid_'.$Row->Data[self::TABLE_ID].'" class="RoundItemSelect roundItemBig" title="'.$Row->Data['title'].'">
						            <div class="flex-allCenter imgSelector">
						              <div class="imgSelectorInner">
						                <img src="'.$GOLBALS['ROOT'].'skin/images/body/pictures/img-back-gen.jpg" alt="'.$Row->Data['title'].'" class="img-responsive">
						                <div class="imgSelectorContent">
						                  <div class="roundItemBigActions">
						                    '.$Actions.'
						                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
						                  </div>
						                </div>
						              </div>
						              <div class="roundItemText">
						                <p><b>'.$Row->Data['title'].'</b></p>
							            '.$Row->Data['link'].'
						              </div>
						            </div>
						          </li>';
				break;
			}
        }
        if(!$Regs) $Regs.= '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron menues.</h4><p>Puede crear un nuevo usuario haciendo click <a href="new.php">aqui</a>.</p></div>';
		return $Regs;
	}
	
	protected function InsertSearchField()
	{
		$Parents = $this->GetParents();
		$Parents = Core::Select(self::TABLE,self::TABLE_ID.',title',"status<>'I' AND ".self::TABLE_ID." IN (".implode(",",$Parents).")");
		$Parents[] = array(self::TABLE_ID=>"0","title"=>"Men&uacute; Principal");
		
		return '<!-- Title -->
          <div class="input-group">
            <span class="input-group-addon order-arrows sort-activated" order="title" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','title','','form-control','placeholder="T&iacute;tulo"').'
          </div>
          <!-- Link -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="link" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','link','','form-control','placeholder="Link"').'
          </div>
          <!-- Parent -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="parent" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('select','parent','','form-control','',$Parents,'', 'Ubicaci&oacute;n').'
          </div>
          <!-- Public -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="public" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('select','public','','form-control','',array("N"=>"Privado","Y"=>"P&uacute;blico"),'',"Privacidad").'
          </div>
          <!-- Type -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="status" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('select','view_status','','form-control','',array("A"=>"Visible","O"=>"Oculto"),'',"Visibilidad").'
          </div>
          <!-- Profile -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="profile" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('select','profile','','form-control','',Core::Select('core_profile','profile_id,title',"organization_id=".$_SESSION['organization_id']." AND status='A'"),'', 'Perfil').'
          </div>
          <!-- Group -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="group" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('select','group','','form-control','',Core::Select('core_group','group_id,title',"organization_id=".$_SESSION['organization_id']." AND status='A'","title"),'', 'Grupo').'
          </div>';
	}
	
	protected function InsertSearchButtons()
	{
		return '<!-- New User Button -->
		    	<a href="new.php" class="hint--bottom hint--bounce hint--success" aria-label="Nuevo Men&uacute;"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-user-plus"></i></button></a>
		    	<!-- /New User Button -->';
	}
	
	public function ConfigureSearchRequest()
	{
		$this->SetTable('core_menu AS m, core_group AS g, core_relation_menu_group AS rg, core_profile AS p, core_relation_menu_profile AS rp');
		$this->SetFields('m.*,p.title as profile, g.title as group_title');
		$this->SetWhere("1=1");
		//$this->AddWhereString(" AND a.profile_id = p.profile_id");
		$this->SetOrder('title');
		$this->SetGroupBy("m.".self::TABLE_ID);
		// if($this->ProfileID!=333)
		// {
		// 	$this->SetWhereCondition("a.profile_id",">",$this->ProfileID);
		// }
		
		foreach($_POST as $Key => $Value)
		{
			$_POST[$Key] = $Value;
		}
			
		if($_POST['title']) $this->SetWhereCondition("m.title","LIKE","%".$_POST['title']."%");
		if($_POST['link']) $this->SetWhereCondition("m.link","LIKE","%".$_POST['link']."%");
		if($_POST['parent'] || $_POST['parent']=="0") $this->SetWhereCondition("m.parent_id","=",$_POST['parent']);
		if($_POST['public']) $this->SetWhereCondition("m.public","=",$_POST['public']);
		if($_POST['view_status']) $this->SetWhereCondition("m.view_status","=", $_POST['view_status']);
		if($_POST['group'])
		{
			$this->AddWhereString(" AND m.".self::TABLE_ID." = rg.".self::TABLE_ID." AND rg.group_id = g.group_id AND g.group_id = ".$_POST['group']);	
		}
		if($_POST['profile'])
		{
			$this->AddWhereString(" AND m.".self::TABLE_ID." = rp.".self::TABLE_ID." AND rp.profile_id = p.profile_id AND p.profile_id = ".$_POST['profile']);	
		}
		if($_REQUEST['status'])
		{
			if($_POST['status']) $this->SetWhereCondition("m.status","=", $_POST['status']);
			if($_GET['status']) $this->SetWhereCondition("m.status","=", $_GET['status']);	
		}else{
			$this->SetWhereCondition("m.status","<>","I");
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
				case "group": 
					$this->AddWhereString(" AND m.".self::TABLE_ID." = rg.".self::TABLE_ID." AND rg.group_id = g.group_id");
					$Order = 'title';
					$Prefix = "g.";
				break;
				case "profile": 
					$this->AddWhereString(" AND m.".self::TABLE_ID." = rp.".self::TABLE_ID." AND rp.profile_id = p.profile_id");
					$Order = 'title';
					$Prefix = "p.";
				break;
				default:
					$Prefix = "m.";
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
		return $this->Data;
	}
	
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function Insert()
	{
		$Title		= $_POST['title'];
		$Link		= $_POST['link'];
		$Position	= $_POST['position']? intval($_POST['position']) : 0;
		$Parent		= $_POST['parent'];
		$Icon		= $_POST['icon'];
		$Groups 	= $_POST['groups'] ? explode(",",$_POST['groups']) : array();
		$Profiles 	= $_POST['profiles'] ? explode(",",$_POST['profiles']) : array();
		$Status		= $_POST['status']=='on'? 'A':'O';
		$Public		= $_POST['public']=='on'? 'N':'Y';
		if(!$Link) $Link="#";
		$ID 		= Core::Insert($this->Table,'title,link,position,icon,parent_id,status,public',"'".$Title."','".$Link."',".$Position.",'".$Icon."',".$Parent.",'".$Status."','".$Public."'");
		foreach($Groups as $Group)
		{
			if(intval($Group)>0)
				$Values .= !$Values? $ID.",".$Group : "),(".$ID.",".$Group;
		}
		Core::Insert('core_relation_menu_group',self::TABLE_ID.',group_id',$Values);
		$Values="";
		foreach($Profiles as $Profile)
		{
			if(intval($Profile)>0)
				$Values .= !$Values? $ID.",".$Profile : "),(".$ID.",".$Profile;
		}
		Core::Insert('core_relation_menu_profile',self::TABLE_ID.',profile_id',$Values);
	}
	
	public function Update()
	{
		$ID	= $_POST['id'];
		
		$Title		= $_POST['title'];
		$Link		= $_POST['link']==""? "#" : $_POST['link'];
		$Position	= $_POST['position']? intval($_POST['position']) : 0;
		$ParentID	= $_POST['parent'];
		$Status		= $_POST['status'];
		$Icon		= $_POST['icon'];
		$Groups 	= $_POST['groups'] ? explode(",",$_POST['groups']) : array();
		$Profiles 	= $_POST['profiles'] ? explode(",",$_POST['profiles']) : array();
		$Status		= $_POST['status']? 'A':'O';
		$Public		= $_POST['public']? 'N':'Y';
		if(!$Link) $Link="#";
		Core::Update($this->Table,"title='".$Title."',link='".$Link."',position='".$Position."',icon='".$Icon."',status='".$Status."',parent_id=".$ParentID.",public='".$Public."'",$this->TableID."=".$ID);
		Core::Delete('core_relation_menu_group',self::TABLE_ID."= ".$ID);
		Core::Delete('core_relation_menu_profile',self::TABLE_ID."= ".$ID);
		foreach($Groups as $Group)
		{
			if(intval($Group)>0)
				$Values .= !$Values? $ID.",".$Group : "),(".$ID.",".$Group;
		}
		Core::Insert('core_relation_menu_group',self::TABLE_ID.',group_id',$Values);
		$Values="";
		foreach($Profiles as $Profile)
		{
			if(intval($Profile)>0)
				$Values .= !$Values? $ID.",".$Profile : "),(".$ID.",".$Profile;
		}
		Core::Insert('core_relation_menu_profile',self::TABLE_ID.',profile_id',$Values);
	}
	
	public function Activate()
	{
		$ID	= $_POST['id'];
		Core::Update($this->Table,"status = 'A'",$this->TableID."=".$ID);
	}
	
	public function Delete()
	{
		$ID	= $_POST['id'];
		Core::Update($this->Table,"status = 'I'",$this->TableID."=".$ID);
	}
	
	public function Search()
	{
		$this->ConfigureSearchRequest();
		echo $this->InsertSearchResults();
	}
}
?>
