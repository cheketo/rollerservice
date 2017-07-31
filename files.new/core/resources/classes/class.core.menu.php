<?php

class CoreMenu 
{
	use CoreSearchList,CoreCrud;
	
	var $IDs 				= array();
	var $MenuData 			= array();
	var $Parents 			= array();
	var $CheckedMenues 		= array();
	var $Link 				= "";
	var $Children 			= array();
	var $Groups 			= array();
	var $Profiles 			= array();
	var $OrgCondition;
	
	const PROFILE			= 333;
	const SWITCHER_URL		= '../../../core/modules/menu/switcher.php';
	const SEARCH_TABLE		= 'core_view_menu_list';
	const TABLE				= 'core_menu';
	const TABLE_ID			= 'menu_id';

	public function __construct($ID=0)
	{
		$this->ID			= $ID;
		$this->OrgCondition	= $_SESSION[CoreOrganization::TABLE_ID]? " AND (".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]." OR ".CoreOrganization::TABLE_ID."=0)":"";
		$this->GetData();
	}
	
	public function OrgCondition()
	{
		return $this->OrgCondition;
	}
	
	public function GetData()
    {
		if(!$this->Data)
		{
			if($this->ID>0)
				return $this->Data = Core::Select(self::SEARCH_TABLE,'*',self::TABLE_ID."='".$this->ID."'".$this->OrgCondition)[0];
			else
				return $this->GetLinkData();
		}else{
			return $this->Data;
		}
    }

	public function GetLinkData()
	{
		if(!$this->Data)
		{	
			$this->Data = self::ChosenMenu(Core::Select(self::SEARCH_TABLE,'*',"link LIKE '%".Core::GetLink()."%' ".$this->OrgCondition));
			$this->ID	= $this->Data[self::TABLE_ID];
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
				$I=0;
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
					if(intval($I)>intval($ChosenMenu[1]))
					{
						$ChosenMenu[0] = $Menues[$Key];
						$ChosenMenu[1] = intval($I);
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
			// $OrganizationCondition = $_SESSION[CoreOrganization::TABLE_ID]? " AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]:"";
			$Menues = Core::Select(self::TABLE,self::TABLE_ID.',parent_id,link',"link LIKE '%".Core::GetLink()."%'".$this->OrgCondition);
			$Menu	= self::ChosenMenu($Menues);
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
		$Rows	= Core::Select(self::TABLE,'*',"parent_id = 0 AND status = 'A' AND view_status = 'A' AND ".self::TABLE_ID." IN (".implode(",",$this->IDs).")".$this->OrgCondition,"position");
		
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

	protected function InsertSubMenu($Parent_id)
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
		if('../../../'.Core::GetLink() == self::SWITCHER_URL && $ID == 0)
		{
			$MenuID = !$_GET['id']? "0":$_GET['id'];

			$Menu = Core::Select(self::TABLE,'*',self::TABLE_ID."=".$MenuID);
		}else{
			if($ID==0)
			{
				$Menu = array(0=>$this->GetLinkData());
			}else{
				$Menu = Core::Select(self::TABLE,'*',self::TABLE_ID."= ".$ID);
			}
		}
		$Parent = $Menu[0]['parent_id'];
		$Link = !$Menu[0]['link'] || $Menu[0]['link']=="#"? self::SWITCHER_URL."?id=".$ID:$Menu[0]['link'];
		if($Parent!=0) $this->InsertBreadCrumbs($Parent);
		
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
				$MenuGroups		= Core::Select('core_relation_menu_group',self::TABLE_ID,CoreGroup::TABLE_ID." IN (SELECT ".CoreGroup::TABLE_ID." FROM core_relation_user_group WHERE ".CoreUser::TABLE_ID." = ".$UserID.")");

				foreach($MenuGroups as $MenuGroup)
				{
					$MGroup[] =  $MenuGroup[self::TABLE_ID];
				}
				$MenuesGroup = implode(",",$MGroup);

				$AllowedMenues 	= Core::Select(self::TABLE,'DISTINCT('.self::TABLE_ID.')',"public = 'Y' OR ".self::TABLE_ID." IN (SELECT ".self::TABLE_ID." FROM core_relation_menu_profile WHERE ".CoreProfile::TABLE_ID."= ".$PorfileID.") OR ".self::TABLE_ID." IN (SELECT ".self::TABLE_ID." FROM core_relation_user_menu WHERE ".CoreUser::TABLE_ID." = ".$UserID.") OR ".self::TABLE_ID." IN (".$MenuesGroup.")  AND status = 'A'");

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
			$Rs 	= Core::Select(CoreGroup::TABLE,'*',"status = 'A' AND ".CoreGroup::TABLE_ID." IN (SELECT ".CoreGroup::TABLE_ID." FROM core_relation_menu_group WHERE ".self::TABLE_ID."=".$this->Data[self::TABLE_ID].") AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],"title");
			$this->Groups = $Rs;
			return $this->Groups;
		}
	}
	
	public function GetProfiles()
	{
		if(!$this->Profiles)
		{
			$Rs 	= Core::Select(CoreProfile::TABLE,'*',"status = 'A' AND ".CoreProfile::TABLE_ID." IN (SELECT ".CoreProfile::TABLE_ID." FROM core_relation_menu_profile WHERE ".self::TABLE_ID."=".$this->Data[self::TABLE_ID].") AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],"title");
			$this->Profiles = $Rs;
			return $this->Profiles;
		}
	}
	
	protected static function MakeActionButtonsHTML($Object,$Mode='list')
	{
		if($Mode!='grid') $HTML .=	'<a class="hint--bottom hint--bounce" aria-label="M&aacute;s informaci&oacute;n"><button type="button" class="btn bg-navy ExpandButton" id="expand_'.$Object->ID.'"><i class="fa fa-plus"></i></button></a> ';;
		$HTML	.= 	'<a href="edit.php?id='.$Object->ID.'" class="hint--bottom hint--bounce hint--info" aria-label="Editar"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
		if($Object->Data['status']=="A")
		{
				$HTML	.= '<a class="deleteElement hint--bottom hint--bounce hint--error" aria-label="Eliminar" process="'.PROCESS.'" id="delete_'.$Object->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
				$HTML	.= Core::InsertElement('hidden','delete_question_'.$Object->ID,'&iquest;Desea eliminar el men&uacute; <b>'.$Object->Data['title'].'</b> ('.$Object->Data['link_text'].')?');
				$HTML	.= Core::InsertElement('hidden','delete_text_ok_'.$Object->ID,'El men&uacute; <b>'.$Object->Data['title'].'</b> ha sido eliminado.');
				$HTML	.= Core::InsertElement('hidden','delete_text_error_'.$Object->ID,'Hubo un error al intentar eliminar el men&uacute; <b>'.$Object->Data['title'].'</b>.');
		}else{
			$HTML	.= '<a class="activateElement hint--bottom hint--bounce hint--success" aria-label="Activar" process="'.PROCESS.'" id="activate_'.$Object->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','activate_question_'.$Object->ID,'&iquest;Desea activar el men&uacute; <b>'.$Object->Data['title'].'</b> ('.$Object->Data['link_text'].')?');
			$HTML	.= Core::InsertElement('hidden','activate_text_ok_'.$Object->ID,'El men&uacute; <b>'.$Object->Data['title'].'</b> ha sido activado.');
			$HTML	.= Core::InsertElement('hidden','activate_text_error_'.$Object->ID,'Hubo un error al intentar activar el men&uacute; <b>'.$Object->Data['title'].'</b>.');
		}
		return $HTML;
	}
	
	protected static function MakeListHTML($Object)
	{
		$HTML = '
		<div class="col-lg-3 col-md-5 col-sm-4 col-xs-12">
			<div class="listRowInner">
				<span class="smallDetails"><h4><i class="fa '.$Object->Data['icon'].'"></i></h4></span>
				<span class="listTextStrong">'.$Object->Data['title'].'</span>
			</div>
		</div>
		<div class="col-lg-5 col-md-5 col-sm-5 hideMobile990">
			<div class="listRowInner">
				<span class="smallDetails">Link</span>
				<span class="listTextStrong">'.$Object->Data['link_text'].'</span>
			</div>
		</div>
		<div class="col-lg-1 col-md-1 col-sm-2 hideMobile990">
			<div class="listRowInner">
				<span class="smallDetails">Privacidad</span>
				<span class="listTextStrong">'.$Object->Data['public_text'].'</span>
			</div>
		</div>
		<div class="col-lg-1 col-md-1 col-sm-1 hideMobile990"></div>';
		return $HTML;
	}
	
	protected static function MakeItemsListHTML($Object)
	{
		$MenuGroups = $Object->GetGroups();
		$Groups = '';
		foreach($MenuGroups as $Group)
		{
			
			$Groups .= '<span class="label label-warning">'.$Group['title'].'</span> ';
		}
		if(!$Groups) $Groups = 'Ninguno';
		$MenuProfiles = $Object->GetProfiles();
		$Profiles = '';
		foreach($MenuProfiles as $Profile)
		{
			
			$Profiles .= '<span class="label label-primary">'.$Profile['title'].'</span> ';
		}
		if(!$Profiles) $Profiles = 'Ninguno';
		
		$RowClass = $RowClass != 'bg-gray'? 'bg-gray':'bg-gray-active';
		$HTML = '
				<div class="row '.$RowClass.'" style="padding:5px;">
					<div class="col-xs-12 showMobile990">
						<div class="listRowInner">
							<span class="smallDetails">Link</span>
							<span class="listTextStrong">'.$Object->Data['link_text'].'</span>
						</div>
					</div>
					<div class="col-xs-12 showMobile990">
						<div class="listRowInner">
							<span class="smallDetails">Privacidad</span>
							<span class="listTextStrong">'.$Object->Data['public_text'].'</span>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12">
						<div class="listRowInner">
							<span class="smallDetails">Pefiles</span>
							<span class="listTextStrong">'.$Profiles.'</span>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12">
						<div class="listRowInner">
							<span class="smallDetails">Grupos</span>
							<span class="listTextStrong">'.$Groups.'</span>
						</div>
					</div>
				</div>';
		return $HTML;
	}
	
	protected static function MakeGridHTML($Object)
	{
		$ButtonsHTML = '<span class="roundItemActionsGroup">'.self::MakeActionButtonsHTML($Object,'grid').'</span>';
		$HTML = '<div class="flex-allCenter imgSelector">
		              <div class="imgSelectorInner">
		                <img src="'.Core::DEFAULT_GRID_IMG.'" class="img-responsive">
		                <div class="imgSelectorContent">
		                  <div class="roundItemBigActions">
		                    '.$ButtonsHTML.'
		                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
		                  </div>
		                </div>
		              </div>
		              <div class="roundItemText">
		                <p><b>'.$Object->Data['title'].'</b></p>
		                <p>('.$Object->Data['link_text'].')</p>
		              </div>
		            </div>';
		return $HTML;
	}
	
	public static function MakeNoRegsHTML()
	{
		return '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron menues.</h4><p>Puede crear un nuevo men&uacute; haciendo click <a href="new.php">aqui</a>.</p></div>';	
	}
	
	protected function SetSearchFields()
	{
		$Parents = $this->GetParents();
		$Parents = Core::Select(self::TABLE,self::TABLE_ID.',title',"status<>'I' AND ".self::TABLE_ID." IN (".implode(",",$Parents).")");
		$Parents[] = array(self::TABLE_ID=>"0","title"=>"Men&uacute; Principal");
		
		$this->SearchFields['title'] = Core::InsertElement('text','title','','form-control','placeholder="T&iacute;tulo"');
		$this->SearchFields['link'] = Core::InsertElement('text','link','','form-control','placeholder="Link"');
		$this->SearchFields['parent_id'] = Core::InsertElement('select','parent','','form-control chosenSelect','',$Parents,' ', 'Ubicaci&oacute;n');
		$this->SearchFields['public_text'] = Core::InsertElement('select','public','','form-control chosenSelect','',array("N"=>"Privado","Y"=>"P&uacute;blico"),' ', 'Privacidad');
		$this->SearchFields['view_status_text'] = Core::InsertElement('select','view_status','','form-control chosenSelect','',array("A"=>"Visible","O"=>"Oculto"),' ', 'Visibilidad');
		$this->SearchFields['profile'] = Core::InsertElement('multiple',CoreProfile::TABLE_ID,'','form-control chosenSelect','data-placeholder="Perfil"',Core::Select(CoreProfile::TABLE,CoreProfile::TABLE_ID.',title',CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]." AND status='A'"),' ', '');
		$this->SearchFields['group_title'] = Core::InsertElement('multiple',CoreGroup::TABLE_ID,'','form-control chosenSelect','data-placeholder="Grupo"',Core::Select(CoreGroup::TABLE,CoreGroup::TABLE_ID.',title',CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]." AND status='A'","title"),' ', '');
	}
	
	protected function InsertSearchButtons()
	{
		return '<!-- New User Button -->
		    	<a href="new.php" class="hint--bottom hint--bounce hint--success" aria-label="Nuevo Men&uacute;"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-plus-square"></i></button></a>
		    	<!-- /New User Button -->';
	}
	
	public function ConfigureSearchRequest()
	{
		$_POST[CoreProfile::TABLE_ID.'_condition'] = 'IN';
		if($_SESSION[CoreProfile::TABLE_ID]==333 && $_SESSION[CoreOrganization::TABLE_ID]==1)
		{
			$this->AddWhereString(" AND (".CoreOrganization::TABLE_ID."=0 OR ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID].")");
			$_POST[CoreOrganization::TABLE_ID.'_restrict']=true;
		}
		$this->SetSearchRequest();
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function Insert()
	{
		$Title		= $_POST['title'];
		$Position	= $_POST['position']? intval($_POST['position']) : 0;
		$Parent		= $_POST['parent'];
		$Icon		= $_POST['icon'];
		$Status		= $_POST['status']=='on'? 'A':'O';
		$Public		= $_POST['public']=='on'? 'N':'Y';
		$Link		= !$_POST['link']? "#":'../../../'.$_POST['link'];
		$ID 		= Core::Insert(self::TABLE,'title,link,position,icon,parent_id,view_status,public,'.CoreOrganization::TABLE_ID,"'".$Title."','".$Link."',".$Position.",'".$Icon."',".$Parent.",'".$Status."','".$Public."',".$_SESSION[CoreOrganization::TABLE_ID]);
		if($_POST['groups']>0) Core::Insert('core_relation_menu_group',self::TABLE_ID.','.CoreGroup::TABLE_ID,Core::AssocArrayToID($ID,$_POST['groups']));
		if($_POST['profiles']>0) Core::Insert('core_relation_menu_profile',self::TABLE_ID.','.CoreProfile::TABLE_ID,Core::AssocArrayToID($ID,$_POST['profiles']));
	}
	
	public function Update()
	{
		$ID	= $_POST['id'];
		$Edit = new CoreMenu($ID);
		$Title		= $_POST['title'];
		$Position	= $_POST['position']? intval($_POST['position']) : 0;
		$ParentID	= $_POST['parent'];
		$Icon		= $_POST['icon'];
		$Status		= $_POST['status']? 'A':'O';
		$Public		= $_POST['public']? 'N':'Y';
		$Link		= !$_POST['link']? "#":'../../../'.$_POST['link'];
		Core::Update(self::TABLE,"title='".$Title."',link='".$Link."',position='".$Position."',icon='".$Icon."',view_status='".$Status."',parent_id=".$ParentID.",public='".$Public."'",self::TABLE_ID."=".$ID);
		$Edit->FastDelete('core_relation_menu_group');
		$Edit->FastDelete('core_relation_menu_profile');
		if($_POST['groups']>0) Core::Insert('core_relation_menu_group',self::TABLE_ID.','.CoreGroup::TABLE_ID,Core::AssocArrayToID($ID,$_POST['groups']));
		if($_POST['profiles']>0) Core::Insert('core_relation_menu_profile',self::TABLE_ID.','.CoreProfile::TABLE_ID,Core::AssocArrayToID($ID,$_POST['profiles']));
	}
}
?>