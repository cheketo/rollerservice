<?php

class CoreUser 
{
	use CoreSearchList,CoreCrud,CoreImage;
	
	var $Groups 			= array();
	var $Parent 			= array();
	var $Menues 			= array();
	
	const TABLE				= 'core_user';
	const TABLE_ID			= 'user_id';
	const SEARCH_TABLE		= 'core_view_user_list';
	const DEFAULT_IMG		= '../../../../skin/images/users/default/default.jpg';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/users/default/';
	const IMG_DIR			= '../../../../skin/images/users/';

	public function __construct($ID='')
	{
		$this->ID = $ID;
		$this->GetData();
		self::SetImg($this->Data['img']);
	}
	
	public function IsOwner()
	{
		return $this->Data[CoreGroup::TABLE_ID] == 360;
	}
	
	public function GetOrganization()
	{
		if(!$this->Data['organization'])
		{
			$this->Data['organization'] = Core::Select(CoreOrganization::TABLE,'*',CoreOrganization::TABLE_ID."=".$this->Data[CoreOrganization::TABLE_ID])[0];
		}
		return $this->Data['organization'];
	}

	public function GetGroups()
	{
		if(!$this->Groups)
		{
			$Rs 	= Core::Select(CoreGroup::TABLE,'*',"status = 'A' AND ".CoreGroup::TABLE_ID." IN (SELECT ".CoreGroup::TABLE_ID." FROM core_relation_user_group WHERE ".self::TABLE_ID."=".$this->ID.")","title");
			$this->Groups = $Rs;
		}
		return $this->Groups;
	}
	
	public static function GetUserGroups($ID)
	{
		return Core::Select(CoreGroup::TABLE.' a INNER JOIN core_relation_user_group b ON (a.'.CoreGroup::TABLE_ID.'=b.'.CoreGroup::TABLE_ID.')','a.*',self::TABLE_ID."=".$ID);
	}

	public function GetProfileID()
	{
		return $this->Data[CoreProfile::TABLE_ID];
	}
	
	public function GetCheckedMenues()
	{
		if(count($this->Menues)<1)
		{
			$Relations	= Core::Select('core_relation_user_menu','*',self::TABLE_ID." = ".$this->ID);
			foreach($Relations as $Relation)
			{
				$this->Menues[]	= $Relation[CoreMenu::TABLE_ID];
			}
		}
		return $this->Menues;

	}

	public function IsDisabled($ParentID)
	{
		return in_array($ParentID,$this->Menues) ? '' : ' disabled="disabled" ';
	}
	
	public static function DateTimeFormat($DateTime)
	{
		return $DateTime == "0000-00-00 00:00:00"? "Nunca se ha conectado":Core::DateTimeFormat($DateTime,'complete');
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// SEARCHLIST FUNCTIONS ///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	protected static function MakeActionButtonsHTML($Object,$Mode='list')
	{
		if($Mode!='grid') $HTML .=	'<a class="hint--bottom hint--bounce" aria-label="M&aacute;s informaci&oacute;n"><button type="button" class="btn bg-navy ExpandButton" id="expand_'.$Object->ID.'"><i class="fa fa-plus"></i></button></a> ';;
		$HTML	.= 	'<a href="edit.php?id='.$Object->ID.'" class="hint--bottom hint--bounce hint--info" aria-label="Editar"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
		if($Object->Data['status']=="A")
		{
			if($Object->ID!=$_SESSION[self::TABLE_ID])
			{
				$HTML	.= '<a class="deleteElement hint--bottom hint--bounce hint--error" aria-label="Eliminar" process="'.PROCESS.'" id="delete_'.$Object->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
				$HTML	.= Core::InsertElement('hidden','delete_question_'.$Object->ID,'&iquest;Desea eliminar el usuario <b>'.$Object->Data['full_user_name'].'</b>?');
				$HTML	.= Core::InsertElement('hidden','delete_text_ok_'.$Object->ID,'El usuario <b>'.$Object->Data['full_user_name'].'</b> ha sido eliminado.');
				$HTML	.= Core::InsertElement('hidden','delete_text_error_'.$Object->ID,'Hubo un error al intentar eliminar el usuario <b>'.$Object->Data['full_user_name'].'</b>.');
			}
		}else{
			$HTML	.= '<a class="activateElement hint--bottom hint--bounce hint--success" aria-label="Activar" process="'.PROCESS.'" id="activate_'.$Object->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','activate_question_'.$Object->ID,'&iquest;Desea activar al usuario <b>'.$Object->Data['full_user_name'].'</b> ?');
			$HTML	.= Core::InsertElement('hidden','activate_text_ok_'.$Object->ID,'El usuario <b>'.$Object->Data['full_user_name'].'</b> ha sido activado.');
			$HTML	.= Core::InsertElement('hidden','activate_text_error_'.$Object->ID,'Hubo un error al intentar activar el usuario <b>'.$Object->Data['full_user_name'].'</b>.');
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
				<img class="img-circle" src="'.$Object->Img.'" alt="'.$Object->Data['full_name'].'">
				<span class="listTextStrong">'.$Object->Data['full_name'].' ('.$Object->Data['user'].')</span>
				<span class="smallTitle hideMobile990">'.$Object->Data['email'].'</span>
			</div>
		</div>
		<div class="col-lg-3 col-md-2 col-sm-2 hideMobile990">
			<div class="listRowInner">
				<span class="smallTitle">Perfil</span>
				<span class="listTextStrong"><span class="label label-primary">'.ucfirst($Object->Data['profile']).'</span></span>
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
		<div class="col-lg-1 col-md-1 col-sm-1 hideMobile990"></div>';
		return $HTML;
	}
	
	protected static function MakeItemsListHTML($Object)
	{
		// $Object->Data['items'] = $Object->GetItems();
		// foreach($Object->Data['items'] as $Item)
		// {
			$RowClass = $RowClass != 'bg-gray'? 'bg-gray':'bg-gray-active';
			$HTML .= '
						<div class="row '.$RowClass.'" style="padding:5px;">
							<div class="col-xs-12">
								<div class="listRowInner">
									<span class="itemRowtitle"><span class="smallTitle">&Uacute;ltimo Acceso:</span> '.self::DateTimeFormat($Object->Data['last_access']).'</span>
								</div>
							</div>
							<div class="col-xs-12 showMobile990">
								<div class="listRowInner">
									<span class="smallTitle">Email</span>
									<span class="emailTextResp">'.$Object->Data['email'].'</span>
								</div>
							</div>
							<div class="col-xs-12 showMobile990">
								<div class="listRowInner">
									<span class="smallTitle">Perfil</span>
									<span class="listTextStrong"><span class="label label-primary">'.ucfirst($Object->Data['profile']).'</span></span>
								</div>
							</div>
							<div class="col-xs-12 showMobile990">
								<div class="listRowInner">
									<span class="smallTitle">Grupos</span>
									<span class="listTextStrong">'.$Object->GroupsHTML.'</span>
								</div>
							</div>
						</div>';
		// }
		return $HTML;
	}
	
	protected static function MakeGridHTML($Object)
	{
		$ButtonsHTML = '<span class="roundItemActionsGroup">'.self::MakeActionButtonsHTML($Object,'grid').'</span>';
		$HTML = '<div class="flex-allCenter imgSelector">
		              <div class="imgSelectorInner">
		                <img src="'.$Object->Img.'" alt="'.$Object->Data['full_name'].'" class="img-responsive">
		                <div class="imgSelectorContent">
		                  <div class="roundItemBigActions">
		                    '.$ButtonsHTML.'
		                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
		                  </div>
		                </div>
		              </div>
		              <div class="roundItemText">
		                <p><b>'.$Object->Data['full_name'].'</b></p>
		                <p>('.$Object->Data['user'].')</p>
		                <p>'.ucfirst($Object->Data['profile']).'</p>
		              </div>
		            </div>';
		return $HTML;
	}
	
	public static function MakeNoRegsHTML()
	{
		return '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron usuarios.</h4><p>Puede crear un nuevo usuario haciendo click <a href="new.php">aqui</a>.</p></div>';	
	}
	
	protected function SetSearchFields()
	{
		$this->SearchFields['first_name'] = Core::InsertElement('text','first_name','','form-control','placeholder="Nombre"');
		$this->SearchFields['last_name'] = Core::InsertElement('text','last_name','','form-control','placeholder="Apellido"');
		$this->SearchFields['user'] = Core::InsertElement('text','user','','form-control','placeholder="Usuario"');
		$this->SearchFields['email'] = Core::InsertElement('text','email','','form-control','placeholder="Email"');
		$this->SearchFields['profile'] = Core::InsertElement('select','profile_id','','form-control chosenSelect','data-placeholder="Perfil"',Core::Select(CoreProfile::TABLE,CoreProfile::TABLE_ID.',title',CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]." AND status='A' AND ".CoreProfile::TABLE_ID." >= ".$_SESSION[CoreProfile::TABLE_ID]),' ', 'Todos los Perfiles');
		$this->SearchFields['group_title'] = Core::InsertElement('multiple','group_id','','form-control chosenSelect','data-placeholder="Grupo"',Core::Select(CoreGroup::TABLE,CoreGroup::TABLE_ID.',title',CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]." AND status='A' AND ".CoreGroup::TABLE_ID." IN (SELECT ".CoreGroup::TABLE_ID." FROM core_relation_group_profile WHERE ".CoreProfile::TABLE_ID." >= ".$_SESSION[CoreProfile::TABLE_ID].")","title"),' ', '');
	}
	
	protected function InsertSearchButtons()
	{
		return '<!-- New User Button -->
		    	<a href="new.php" class="hint--bottom hint--bounce hint--success" aria-label="Nuevo Usuario"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-user-plus"></i></button></a>
		    	<!-- /New User Button -->';
	}
	
	public function ConfigureSearchRequest()
	{
		if($_SESSION[CoreProfile::TABLE_ID]!=333)
		{
			$_POST[CoreProfile::TABLE_ID.'_condition'] = '>=';
		}
		$_POST[CoreGroup::TABLE_ID.'_condition'] = 'IN';
		$this->SetSearchRequest();
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function Insert()
	{
		$User		= strtolower($_POST['user']);
		$Password	= sha1($_POST['password']);
		$FirstName	= ucfirst($_POST['first_name']);
		$LastName	= ucfirst($_POST['last_name']);
		$Email 		= strtolower($_POST['email']);
		$ProfileID	= $_POST['profile'];
		$ID			= Core::Insert(self::TABLE,'user,password,first_name,last_name,email,'.CoreProfile::TABLE_ID.',img,creation_date,creator_id,'.CoreOrganization::TABLE_ID,"'".$User."','".$Password."','".$FirstName."','".$LastName."','".$Email."','".$ProfileID."','".$Image."',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID]);
		$Object 	= new CoreUser($ID);
		$Image 		= $Object->ProcessImg($_POST['newimage']);
		Core::Update(self::TABLE,"img='".$Image."'",self::TABLE_ID."=".$ID);
		if($_POST['groups']>0) Core::Insert('core_relation_user_group',self::TABLE_ID.','.CoreGroup::TABLE_ID,Core::AssocArrayToID($ID,$_POST['groups']));
		if($_POST['menues']>0) Core::Insert('core_relation_user_menu',self::TABLE_ID.','.CoreMenu::TABLE_ID,Core::AssocArrayToID($ID,$_POST['menues']));
	}
	
	public function Update()
	{
		$ID 	= $_POST['id'];
		$Object	= new CoreUser($ID);
		if($_POST['password'])
		{
			$Password	= sha1($_POST['password']);
			$PasswordFilter	= ",password='".$Password."'";
		}
		$User		= strtolower($_POST['user']);
		$FirstName	= $_POST['first_name'];
		$LastName	= $_POST['last_name'];
		$Email 		= $_POST['email'];
		$ProfileID	= $_POST['profile'];
		// if($ID!=$this->ID) $Image = $this->ProcessImg($_POST['newimage']);
		$Image = $Object->ProcessImg($_POST['newimage']);
		$Update		= Core::Update(self::TABLE,"user='".$User."'".$PasswordFilter.",first_name='".$FirstName."',last_name='".$LastName."',email='".$Email."',".CoreProfile::TABLE_ID."='".$ProfileID."',img='".$Image."'",self::TABLE_ID."=".$ID);
		$Object->FastDelete('core_relation_user_group');
		$Object->FastDelete('core_relation_user_menu');
		if($_POST['groups']>0) Core::Insert('core_relation_user_group',self::TABLE_ID.','.CoreGroup::TABLE_ID,Core::AssocArrayToID($ID,$_POST['groups']));
		if($_POST['menues']>0) Core::Insert('core_relation_user_menu',self::TABLE_ID.','.CoreMenu::TABLE_ID,Core::AssocArrayToID($ID,$_POST['menues']));
	}
	
	public function Validate()
	{
		echo self::ValidateValue('user',$_POST['user'],$_POST['actualuser']);
	}
	
	public function Validate_email()
	{
		echo self::ValidateValue('email',$_POST['email'],$_POST['actualemail']);
	}
	
	public function Fillgroups()
	{
        echo CoreGroup::GetGroups($_POST['profile'],$_POST['id']);
	}
}
?>