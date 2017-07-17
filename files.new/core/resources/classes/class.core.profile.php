<?php

class CoreProfile 
{
	use CoreSearchList,CoreCrud,CoreImage;
	
	var $Menues 			= array();
	var $MenuRelations 		= array();
	var $Users 				= array();
	
	const TABLE				= 'core_profile';
	const TABLE_ID			= 'profile_id';
	const SEARCH_TABLE		= 'core_view_profile_list';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/profiles/default/';
	const IMG_DIR			= '../../../../skin/images/profiles/';
	const DEFAULT_IMG		= "../../../../skin/images/profiles/default/profilegen.jpg";

	public function __construct($ID=0)
	{
		
		$this->ID = $ID;
		$this->GetData();
		self::SetImg($this->Data['image']);
	}

	public function GetMenues()
	{
		if(count($this->Menues)<1)
		{
			$Relations	= $this->GetMenuRelations();
			foreach($Relations as $Relation)
			{
				$this->Menues[]	= $Relation[CoreMenu::TABLE_ID];
			}
		}
		return $this->Menues;
	}

	public function GetMenuRelations()
	{
		if(!$this->MenuRelations)
			$this->MenuRelations = Core::Select('core_relation_menu_profile','*',self::TABLE_ID."= ".$this->ID);
		return $this->MenuRelations;
	}

	public function GetUsers()
	{
		if(!$this->Users)
			$this->Users = Core::Select(CoreUser::TABLE,'*',self::TABLE_ID."=".$this->ID." AND status <> 'I'");
		return $this->Users;
	}
	
	public function GetGroups()
	{
		if(!$this->Groups)
		{
			$Rs 	= Core::Select(CoreGroup::TABLE,'*',"status = 'A' AND ".CoreGroup::TABLE_ID." IN (SELECT ".CoreGroup::TABLE_ID." FROM core_relation_group_profile WHERE ".self::TABLE_ID."=".$this->ID.")","title");
			$this->Groups = $Rs;
		}
		return $this->Groups;
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
			$HTML	.= Core::InsertElement('hidden','delete_question_'.$Object->ID,'&iquest;Desea eliminar el perfil <b>'.$Object->Data['title'].'</b> ?');
			$HTML	.= Core::InsertElement('hidden','delete_text_ok_'.$Object->ID,'El perfil <b>'.$Object->Data['title'].'</b> ha sido eliminado.');
			$HTML	.= Core::InsertElement('hidden','delete_text_error_'.$Object->ID,'Hubo un error al intentar eliminar el perfil <b>'.$Object->Data['title'].'</b>.');
		}else{
			$HTML	.= '<a class="activateElement hint--bottom hint--bounce hint--success" aria-label="Activar" process="'.PROCESS.'" id="activate_'.$Object->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','activate_question_'.$Object->ID,'&iquest;Desea activar el perfil <b>'.$Object->Data['title'].'</b> ?');
			$HTML	.= Core::InsertElement('hidden','activate_text_ok_'.$Object->ID,'El perfil <b>'.$Object->Data['title'].'</b> ha sido activado.');
			$HTML	.= Core::InsertElement('hidden','activate_text_error_'.$Object->ID,'Hubo un error al intentar activar el perfil <b>'.$Object->Data['title'].'</b>.');
		}
		return $HTML;
	}
	
	protected static function MakeListHTML($Object)
	{
		$Groups = $Object->GetGroups();
		$Object->GroupsHTML = '';
		foreach($Groups as $Group)
		{
			$Object->GroupsHTML .= '<span class="label label-warning">'.$Group['title'].'</span> ';
		}
		if(!$Object->GroupsHTML) $Object->GroupsHTML = 'Ninguno';
		$HTML = '<div class="col-lg-4 col-md-5 col-sm-5 col-xs-10">
			<div class="listRowInner">
				<img class="img-circle" src="'.$Object->Img.'" alt="'.$Object->Data['title'].'">
				<span class="listTextStrong">'.$Object->Data['title'].'</span>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-3 hideMobile990">
			<div class="listRowInner">
				<span class="smallTitle">Grupos</span>
				<span class="listTextStrong">
					'.$Object->GroupsHTML.'
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
								<span class="smallTitle">Grupos</span>
								<span class="listTextStrong">'.$Object->GroupsHTML.'</span>
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
	
	protected function InsertSearchButtons()
	{
		return '<a href="new.php" class="hint--bottom hint--bounce hint--success" aria-label="Nuevo Perfil"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-plus-square"></i></button></a>';
	}
	
	public static function MakeNoRegsHTML()
	{
		return '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron perfiles.</h4><p>Puede crear un nuevo perfil haciendo click <a href="new.php">aqui</a>.</p></div>';	
	}
	
	protected function SetSearchFields()
	{
		$this->SearchFields['title'] = Core::InsertElement('text','title','','form-control','placeholder="Nombre"');
		$this->SearchFields['group_title'] = Core::InsertElement('multiple','group_id','','form-control chosenSelect','data-placeholder="Grupo"',Core::Select(CoreGroup::TABLE,CoreGroup::TABLE_ID.',title',CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]." AND status='A' AND ".CoreGroup::TABLE_ID." IN (SELECT ".CoreGroup::TABLE_ID." FROM core_relation_group_profile WHERE ".CoreProfile::TABLE_ID." >= ".$_SESSION[CoreProfile::TABLE_ID].")","title"),' ', '');
	}
	
	public function ConfigureSearchRequest()
	{
		if($_SESSION[self::TABLE_ID]!=333)
		{
			$_POST[self::TABLE_ID.'_condition'] = '>=';
		}
		$_POST[CoreGroup::TABLE_ID.'_condition'] = 'IN';
		$this->SetSearchRequest();
	}
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function Insert()
	{
		$Title	= ucfirst($_POST['title']);
		$ID		= Core::Insert(self::TABLE,CoreOrganization::TABLE_ID.',title,creation_date',$_SESSION[CoreOrganization::TABLE_ID].",'".$Title."',NOW()");
		$Object = new CoreProfile($ID);
		$Image 	= $Object->ProcessImg($_POST['newimage']);
		Core::Update(self::TABLE,"image='".$Image."'",self::TABLE_ID."=".$ID);
		if($_POST['groups']>0) Core::Insert('core_relation_group_profile',self::TABLE_ID.','.CoreGroup::TABLE_ID,Core::AssocArrayToID($ID,$_POST['groups']));
		if($_POST['menues']>0) Core::Insert('core_relation_menu_profile',self::TABLE_ID.','.CoreMenu::TABLE_ID,Core::AssocArrayToID($ID,$_POST['menues']));
	}
	
	public function Update()
	{
		$ID 	= $_POST['id'];
		$Object	= new CoreProfile($ID);
		$Title	= ucfirst($_POST['title']);
		$Image 	= $Object->ProcessImg($_POST['newimage']);
		$Update	= Core::Update(self::TABLE,"title='".$Title."',image='".$Image."'",self::TABLE_ID."=".$ID);
		$Object->FastDelete('core_relation_group_profile');
		$Object->FastDelete('core_relation_menu_profile');
		if($_POST['groups']>0) Core::Insert('core_relation_group_profile',self::TABLE_ID.','.CoreGroup::TABLE_ID,Core::AssocArrayToID($ID,$_POST['groups']));
		if($_POST['menues']>0) Core::Insert('core_relation_menu_profile',self::TABLE_ID.','.CoreMenu::TABLE_ID,Core::AssocArrayToID($ID,$_POST['menues']));
	}
	
	public function Validate()
	{
		return ValidateValue('title',$_POST['title'],$_POST['actualtitle']);
	}
	
	public function Fillgroups()
	{
        echo CoreGroup::GetGroups($_POST['profile'],$_POST['admin']);
	}
}
?>