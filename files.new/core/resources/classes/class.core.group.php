<?php

class CoreGroup
{
	use CoreSearchList,CoreCrud,CoreImage;
	
	var $Profiles 		= array();
	var $Menues 		= array();
	var $Users 			= array();
	var $Relations 		= array();
	var $Groups 		= array();
	
	const TABLE				= 'core_group';
	const TABLE_ID			= 'group_id';
	const SEARCH_TABLE		= 'core_view_group_list';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/group/default/';
	const IMG_DIR			= '../../../../skin/images/groups/';
	const DEFAULT_IMG		= "../../../../skin/images/groups/default/groupgen.jpg";

	public function __construct($ID=0)
	{
		$this->ID = $ID;
		$this->GetData();
		self::SetImg($this->Data['image']);
	}

	public function GetDefaultImg()
	{
		return self::DEFAULT_IMG;
	}

	public static function GetUserGroups($User=0)
	{
		if($User!=0)
		{
			$Relations	= self::GetUserRelations($User);
			foreach($Relations as $Relation)
			{
				$UserGroups[]	= $Relation[self::TABLE_ID];
			}
		}
		return $UserGroups;
	}

	public function GetCheckedMenues()
	{
		if(count($this->Menues)<1)
		{
			$Relations	= $this->GetRelations();
			foreach($Relations as $Relation)
			{
				$this->Menues[]	= $Relation[CoreMenu::TABLE_ID];
			}
		}
		return $this->Menues;
	}

	public function GetRelations()
	{
		if(!$this->Relations)
			$this->Relations = Core::Select('core_relation_menu_group','*',self::TABLE_ID." = ".$this->ID);
		return $this->Relations;
	}

	public static function GetUserRelations($User)
	{
		return Core::Select('core_relation_user_group','*',CoreUser::TABLE_ID."=".$User);
	}

	public function GetUsers()
	{
		if(!$this->Users)
			$this->Users = Core::Select(CoreUser::TABLE.' a INNER JOIN core_relation_user_group b ON (a.'.CoreUser::TABLE_ID.'=b.'.CoreUser::TABLE_ID.')','a.*',"b.".self::TABLE_ID."=".$this->ID." AND a.status <> 'I'");
		return $this->Users;
	}

	public static function GetGroups($ProfileID=0,$User=0)
	{
		$HTML 				= '<h4 class="subTitleB"><i class="fa fa-users"></i> Grupos</h4><select id="groups" class="form-control chosenSelect" multiple="multiple" data-placeholder="Seleccione los grupos">';
		if($ProfileID!=0)
		{
			$CheckedGroups 	= self::GetUserGroups($User);
			$Groups			= Core::Select('core_group','*',CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]." AND status='A'  AND ".self::TABLE_ID." IN (SELECT ".self::TABLE_ID." FROM core_relation_group_profile WHERE ".CoreProfile::TABLE_ID." = ".$ProfileID.")","title");			
			foreach($Groups as $Group)
			{
				if($CheckedGroups && in_array($Group[self::TABLE_ID],$CheckedGroups))
				{
					$Selected = ' selected="selected" ';
				}else{
					$Selected = '';
				}
				$HTML		.= '<option '.$Selected.' value="'.$Group[self::TABLE_ID].'">'.$Group['title'].'</option>';
			}
		}
		return $HTML.'</select>';
	}
	
	public function GetProfiles()
	{
		if(!$this->Profiles)
		{
			$Rs 	= Core::Select(CoreProfile::TABLE,'*',"status = 'A' AND ".CoreProfile::TABLE_ID." IN (SELECT ".CoreProfile::TABLE_ID." FROM core_relation_group_profile WHERE ".self::TABLE_ID."=".$this->ID.")","title");
			$this->Profiles = $Rs;
		}
		return $this->Profiles;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// SEARCHLIST FUNCTIONS ///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	protected static function MakeActionButtonsHTML($Object,$Mode='list')
	{
		if($Mode!='grid') $HTML .=	'<a class="hint--bottom hint--bounce" aria-label="M&aacute;s informaci&oacute;n"><button type="button" class="btn bg-navy ExpandButton showMobile990" id="expand_'.$Object->ID.'"><i class="fa fa-plus"></i></button></a> ';;
		$HTML	.= 	'<a href="edit.php?id='.$Object->ID.'" class="hint--bottom hint--bounce hint--info" aria-label="Editar"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
		if($Object->Data['status']=="A")
		{	
			$HTML	.= '<a class="deleteElement hint--bottom hint--bounce hint--error" aria-label="Eliminar" process="'.PROCESS.'" id="delete_'.$Object->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','delete_question_'.$Object->ID,'&iquest;Desea eliminar el grupo <b>'.$Object->Data['title'].'</b> ?');
			$HTML	.= Core::InsertElement('hidden','delete_text_ok_'.$Object->ID,'El grupo <b>'.$Object->Data['title'].'</b> ha sido eliminado.');
			$HTML	.= Core::InsertElement('hidden','delete_text_error_'.$Object->ID,'Hubo un error al intentar eliminar el grupo <b>'.$Object->Data['title'].'</b>.');
		}else{
			$HTML	.= '<a class="activateElement hint--bottom hint--bounce hint--success" aria-label="Activar" process="'.PROCESS.'" id="activate_'.$Object->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','activate_question_'.$Object->ID,'&iquest;Desea activar el grupo <b>'.$Object->Data['title'].'</b> ?');
			$HTML	.= Core::InsertElement('hidden','activate_text_ok_'.$Object->ID,'El grupo <b>'.$Object->Data['title'].'</b> ha sido activado.');
			$HTML	.= Core::InsertElement('hidden','activate_text_error_'.$Object->ID,'Hubo un error al intentar activar el grupo <b>'.$Object->Data['title'].'</b>.');
		}
		return $HTML;
	}
	
	protected static function MakeListHTML($Object)
	{
		$Profiles = $Object->GetProfiles();
		$Object->ProfilesHTML = '';
		foreach($Profiles as $Profile)
		{
			$Object->ProfilesHTML .= '<span class="label label-primary">'.$Profile['title'].'</span> ';
		}
		if(!$Object->ProfilesHTML) $Object->ProfilesHTML = 'Ninguno';
		$HTML = '<div class="col-lg-4 col-md-5 col-sm-5 col-xs-10">
			<div class="listRowInner">
				<img class="img-circle" src="'.$Object->Img.'" alt="'.$Object->Data['title'].'">
				<span class="listTextStrong">'.$Object->Data['title'].'</span>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-3 hideMobile990">
			<div class="listRowInner">
				<span class="smallTitle">Perfiles</span>
				<span class="listTextStrong">
					'.$Object->ProfilesHTML.'
				</span>
			</div>
		</div>
		';
		return $HTML;
	}
	
	protected static function MakeItemsListHTML($Object)
	{
		$HTML .= '
					<div class="row bg-gray" style="padding:5px;">
						<div class="col-xs-12">
							<div class="listRowInner">
								<span class="smallTitle">Perfiles</span>
								<span class="listTextStrong">'.$Object->ProfilesHTML.'</span>
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
		                <img src="'.$Object->Img.'" alt="'.$Object->Data['title'].'" class="img-responsive">
		                <div class="imgSelectorContent">
		                  <div class="roundItemBigActions">
		                    '.$ButtonsHTML.'
		                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
		                  </div>
		                </div>
		              </div>
		              <div class="roundItemText">
		                <p><b>'.$Object->Data['title'].'</b></p>
		              </div>
		            </div>';
		return $HTML;
	}
	
	public static function MakeNoRegsHTML()
	{
		return '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron grupos.</h4><p>Puede crear un nuevo grupo haciendo click <a href="new.php">aqui</a>.</p></div>';	
	}
	
	protected function SetSearchFields()
	{
		$this->SearchFields['title'] = Core::InsertElement('text','title','','form-control','placeholder="Nombre"');
		$this->SearchFields['profile'] = Core::InsertElement('select',CoreProfile::TABLE_ID,'','form-control chosenSelect','data-placeholder="Perfil"',Core::Select('core_profile',CoreProfile::TABLE_ID.',title',CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]." AND status='A' AND ".CoreProfile::TABLE_ID." >= ".$_SESSION[CoreProfile::TABLE_ID]),' ', 'Todos los Perfiles');	
	}
	
	protected function InsertSearchButtons()
	{
		return '<a href="new.php" class="hint--bottom hint--bounce hint--success" aria-label="Nuevo Grupo"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-plus-square"></i></button></a>';
	}
	
	// public function ConfigureSearchRequest()
	// {
	// 	$this->SetSearchRequest();
	// }
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function Insert()
	{
		$Title	= ucfirst($_POST['title']);
		$ID		= Core::Insert(self::TABLE,CoreOrganization::TABLE_ID.',title,creation_date',$_SESSION[CoreOrganization::TABLE_ID].",'".$Title."',NOW()");
		$Object = new CoreGroup($ID);
		$Image 	= $Object->ProcessImg($_POST['newimage']);
		Core::Update(self::TABLE,"image='".$Image."'",self::TABLE_ID."=".$ID);
		if($_POST['profiles']>0) Core::Insert('core_relation_group_profile',self::TABLE_ID.','.CoreProfile::TABLE_ID,Core::AssocArrayToID($ID,$_POST['profiles']));
		if($_POST['menues']>0) Core::Insert('core_relation_menu_group',self::TABLE_ID.','.CoreMenu::TABLE_ID,Core::AssocArrayToID($ID,$_POST['menues']));
		if($_POST['users']>0) Core::Insert('core_relation_user_group',self::TABLE_ID.','.CoreUser::TABLE_ID,Core::AssocArrayToID($ID,$_POST['users']));
	}
	
	public function Update()
	{
		$ID 	= $_POST['id'];
		$Object	= new CoreGroup($ID);
		$Title	= ucfirst($_POST['title']);
		$Image 	= $Object->ProcessImg($_POST['newimage']);
		$Update	= Core::Update(self::TABLE,"title='".$Title."',image='".$Image."'",self::TABLE_ID."=".$ID);
		$Object->FastDelete('core_relation_group_profile');
		$Object->FastDelete('core_relation_menu_group');
		$Object->FastDelete('core_relation_user_group');
		if($_POST['profiles']>0) Core::Insert('core_relation_group_profile',self::TABLE_ID.','.CoreProfile::TABLE_ID,Core::AssocArrayToID($ID,$_POST['profiles']));
		if($_POST['menues']>0) Core::Insert('core_relation_menu_group',self::TABLE_ID.','.CoreMenu::TABLE_ID,Core::AssocArrayToID($ID,$_POST['menues']));
		if($_POST['users']>0) Core::Insert('core_relation_user_group',self::TABLE_ID.','.CoreUser::TABLE_ID,Core::AssocArrayToID($ID,$_POST['users']));
	}
	
	public function Validate()
	{
		echo self::ValidateValue('title',$_POST['title'],$_POST['actualtitle']);
	}
}
?>
