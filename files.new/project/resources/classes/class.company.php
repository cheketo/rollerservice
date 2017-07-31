<?php

class Company 
{
	use CoreSearchList,CoreCrud,CoreImage;
	
	var $Branches 			= array();
	const TABLE				= 'company';
	const TABLE_ID			= 'company_id';
	const SEARCH_TABLE		= 'view_company_list';
	const DEFAULT_IMG		= '../../../../skin/images/companies/default/default.png';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/companies/default/';
	const IMG_DIR			= '../../../../skin/images/companies/';

	public function __construct($ID=0)
	{
		$this->ID = $ID;
		if($this->ID!=0)
		{
			$Data = Core::Select(self::SEARCH_TABLE,'*',self::TABLE_ID."=".$this->ID,'branch_id');
			$this->Data = $Data[0];
			$this->Data['branches'] = CompanyBranch::GetBranches($Data);
			self::SetImg($this->Data['logo']);
		}
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
			$HTML	.= '<a class="deleteElement hint--bottom hint--bounce hint--error" aria-label="Eliminar" process="'.PROCESS.'" id="delete_'.$Object->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','delete_question_'.$Object->ID,'&iquest;Desea eliminar la empresa <b>'.$Object->Data['name'].'</b>?');
			$HTML	.= Core::InsertElement('hidden','delete_text_ok_'.$Object->ID,'La empresa <b>'.$Object->Data['name'].'</b> ha sido eliminada.');
			$HTML	.= Core::InsertElement('hidden','delete_text_error_'.$Object->ID,'Hubo un error al intentar eliminar la empresa <b>'.$Object->Data['name'].'</b>.');
		}else{
			$HTML	.= '<a class="activateElement hint--bottom hint--bounce hint--success" aria-label="Activar" process="'.PROCESS.'" id="activate_'.$Object->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','activate_question_'.$Object->ID,'&iquest;Desea activar la empresa <b>'.$Object->Data['name'].'</b>?');
			$HTML	.= Core::InsertElement('hidden','activate_text_ok_'.$Object->ID,'La empresa <b>'.$Object->Data['name'].'</b> ha sido activada.');
			$HTML	.= Core::InsertElement('hidden','activate_text_error_'.$Object->ID,'Hubo un error al intentar activar la empresa <b>'.$Object->Data['name'].'</b>.');
		}
		return $HTML;
	}
	
	protected static function MakeListHTML($Object)
	{
		$HTML = '<div class="col-lg-4 col-md-5 col-sm-5 col-xs-5">
			<div class="listRowInner">
				<img class="img-circle hideMobile990" src="'.$Object->Img.'" alt="'.$Object->Data['name'].'">
				<span class="listTextStrong">'.$Object->Data['name'].'</span>
				<span class="smallTitle hideMobile990">'.Core::FromNumberToCUIT($Object->Data['cuit']).'</span>
				<span class="smallTitle hideMobile990">('.$Object->Data['iva'].')</span>
			</div>
		</div>
		<div class="col-lg-3 col-md-2 col-sm-2 col-xs-4">
			<div class="listRowInner">
				<span class="smallTitle">Tipo</span>
				<span class="listTextStrong">
					<span class="label label-primary">'.$Object->Data['relation_text'].'</span><br>
					<span class="label label-brown">'.$Object->Data['international_text'].'</span>
				</span>
			</div>
		</div>
		<div class="col-lg-1 col-md-2 col-sm-3 col-xs-3">
			<div class="listRowInner">
				<span class="smallTitle">Balance</span>
				<span class="listTextStrong">
					'.Core::FromNumberToMoneyLabel($Object->Data['balance']).'
				</span>
			</div>
		</div>
		<div class="col-lg-1 col-md-1 col-sm-1 hideMobile990"></div>';
		return $HTML;
	}
	
	protected static function MakeItemsListHTML($Object)
	{
		foreach($Object->Data['branches'] as $Item)
		{
			$RowClass = $RowClass != 'bg-gray'? 'bg-gray':'bg-gray-active';
			$HTML .= '
						<div class="row '.$RowClass.'" style="padding:5px;">
							<div class="col-lg-4 col-md-5 col-sm-5 col-xs-10">
								<div class="listRowInner">
									<img class=" hideMobile990" src="'.$Item['branch_logo'].'" alt="'.$Item['address'].'">
									<span class="listTextStrong">'.$Item['address'].'</span>
									<span class="smallTitle hideMobile990">'.$Item['zone'].','.$Item['province_short'].'</span>
								</div>
							</div>
							<div class="col-sm-2 col-xs-12">
								<div class="listRowInner">
									<span class="smallTitle">Teléfono</span>
									<span class="emailTextResp">'.$Item['phone'].'</span>
								</div>
							</div>
							<div class="col-sm-3 col-xs-12">
								<div class="listRowInner">
									<span class="smallTitle">Email</span>
									<span class="listTextStrong"><span class="label bg-navy">'.Core::EmailLink($Item['email']).'</span></span>
								</div>
							</div>
							<div class="col-xs-12 showMobile990">
								<div class="listRowInner">
									<span class="smallTitle">Grupos</span>
									<span class="listTextStrong">'.$Object->GroupsHTML.'</span>
								</div>
							</div>
						</div>';
		}
		return $HTML;
	}
	
	protected static function MakeGridHTML($Object)
	{
		$ButtonsHTML = '<span class="roundItemActionsGroup">'.self::MakeActionButtonsHTML($Object,'grid').'</span>';
		$HTML = '<div class="flex-allCenter imgSelector">
		              <div class="imgSelectorInner">
		                <img src="'.$Object->Img.'" alt="'.$Object->Data['name'].'" class="img-responsive">
		                <div class="imgSelectorContent">
		                  <div class="roundItemBigActions">
		                    '.$ButtonsHTML.'
		                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
		                  </div>
		                </div>
		              </div>
		              <div class="roundItemText">
		                <p><b>'.$Object->Data['name'].'</b></p>
		                <p>('.$Object->Data['cuit'].')</p>
		                <p>'.$Object->Data['type'].'</p>
		              </div>
		            </div>';
		return $HTML;
	}
	
	public static function MakeNoRegsHTML()
	{
		$Entities = 'empresas';
		$Entities = strtoupper($_GET['customer'])=='Y'? 'clientes':$Entities;
		$Entities = strtoupper($_GET['provider'])=='Y'? 'proveedores':$Entities;
		return '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron '.$Entities.'.</h4><p>Puede crear una nueva empresa haciendo click <a href="new.php">aqui</a>.</p></div>';
	}
	
	protected function SetSearchFields()
	{
		$this->SearchFields['name'] = Core::InsertElement('text','name','','form-control','placeholder="Nombre"');
		$this->SearchFields['balance_from'] = Core::InsertElement('text','balance_from','','form-control','placeholder="Balance Desde"');
		$this->SearchFields['balance_to'] = Core::InsertElement('text','balance_to','','form-control','placeholder="Balance Hasta"');
		$this->SearchFields['cuit'] = Core::InsertElement('text','cuit','','form-control inputMask','placeholder="CUIT" data-inputmask="\'mask\': \'99-99999999-9\'"');
		$this->SearchFields['iibb'] = Core::InsertElement('text','iibb','','form-control','placeholder="IIBB"');
		$this->SearchFields['vat'] = Core::InsertElement('text','vat','','form-control','placeholder="VAT"');
		$this->SearchFields['address'] = Core::InsertElement('text','address','','form-control','placeholder="Dirección"');
		$this->SearchFields['international'] = Core::InsertElement('select','international','','form-control chosenSelect','',array("N"=>"Nacional","Y"=>"Internacional"),'','Cualquier Nacionalidad');
		if(!$_GET['provider'] && !$_GET['customer'])
			$this->SearchFields['relation_text'] = Core::InsertElement('select','relation_text','','form-control chosenSelect','',array("Cliente y Proveedor"=>"Cliente y Proveedor","Cliente"=>"Cliente","Proveedor"=>"Proveedor"),'','Cualquier Relaci&oacute;n');;
		
		// $this->SearchFields['profile'] = Core::InsertElement('select','profile_id','','form-control chosenSelect','data-placeholder="Perfil"',Core::Select(CoreProfile::TABLE,CoreProfile::TABLE_ID.',title',CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]." AND status='A' AND ".CoreProfile::TABLE_ID." >= ".$_SESSION[CoreProfile::TABLE_ID]),' ', 'Todos los Perfiles');
		// $this->SearchFields['group_title'] = Core::InsertElement('multiple','group_id','','form-control chosenSelect','data-placeholder="Grupo"',Core::Select(CoreGroup::TABLE,CoreGroup::TABLE_ID.',title',CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]." AND status='A' AND ".CoreGroup::TABLE_ID." IN (SELECT ".CoreGroup::TABLE_ID." FROM core_relation_group_profile WHERE ".CoreProfile::TABLE_ID." >= ".$_SESSION[CoreProfile::TABLE_ID].")","title"),' ', '');
	}
	
	protected function InsertSearchButtons()
	{
		return '<a href="new.php" class="hint--bottom hint--bounce hint--success" aria-label="Nueva Empresa"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-plus-square"></i></button></a>';
	}
	
	public function ConfigureSearchRequest()
	{
		if($_POST['cuit']) $_POST['cuit'] = Core::FromCUITToNumber($_POST['cuit']);
		if($_POST['balance_from'])
		{
			$Price=Core::FromMoneyToDB($_POST['balance_from']);
			$this->AddWhereString(" AND balance>=".$Price);
			$_POST['balance_restricted']=true;
		}
		if($_POST['balance_to'])
		{
			$Price=Core::FromMoneyToDB($_POST['balance_to']);
			$this->AddWhereString(" AND balance<=".$Price);
			$_POST['balance_restricted']=true;
		}
		
		if($_POST['view_order_field']=="balance_from" || $_POST['view_order_field']=="balance_to")
			$_POST['view_order_field'] = "balance";
			
		$this->SetSearchRequest();
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function ValidateInformation()
	{
		//VALIDATIONS
		if(!$_POST['name']) echo 'Falta Nombre';
		if(!$_POST['type']) echo 'Tipo incompleto';
		if(!$_POST['relation']) echo 'Falta relaci&oacute;n';
		if(!$_POST['international']) echo 'Nacionalidad incompleta';
		if($_POST['international']=="N")
		{
			if(!$_POST['cuit']) echo 'CUIT incompleto';
			if(!$_POST['iva']) echo 'IVA incompleto';
			if(!$_POST['gross_income_number']) echo 'IIBB incompleto';
		}else{
			if(!$_POST['vat']) echo 'VAT incompleto';
		}
	}
	public function Insert()
	{
		// Validate data from POST
		$this->ValidateInformation();
		// Basic Data
		$Type 			= $_POST['type'];
		$Name			= $_POST['name'];
		$CUIT			= Core::FromCUITToNumber($_POST['cuit']);
		$IVA			= $_POST['iva'];
		$VAT			= $_POST['vat'];
		$GrossIncome	= $_POST['gross_income_number'];
		$International	= $_POST['international'];
		switch($_POST['relation'])
		{
			case "1":
				$Customer = 'Y';
				$Provider = 'N';
			break;
			case "2":
				$Customer = 'N';
				$Provider = 'Y';
			break;
			case "3":
				$Customer = 'Y';
				$Provider = 'Y';
			break;
		}
		
		$ID			= Core::Insert(self::TABLE,'type_id,name,cuit,iva,iibb,vat,international,customer,provider,logo,creation_date,created_by,'.CoreOrganization::TABLE_ID,"'".$Type."','".$Name."',".$CUIT.",".$IVA.",".$GrossIncome.",'".$VAT."','".$International."','".$Customer."','".$Provider."','".$Image."',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganzaition::TABLE_ID]);
		echo $this->LastQuery();
		$Object 	= new Company($ID);
		$Image 		= $Object->ProcessImg($_POST['newimage']);
		Core::Update(self::TABLE,"logo='".$Image."'",self::TABLE_ID."=".$ID);
		// Tax::SetIVA($CUIT,$IVA);
		
		CompanyBranch::InsertBranchInfo($ID);
	}	
	
	public function Update()
	{
		// Validate data from POST
		$this->ValidateInformation();
		
		// Set Object
		$ID 	= $_POST['id'];
		$Object	= new Company($ID);
		
		// Basic Data
		$Type 			= $_POST['type'];
		$Name			= $_POST['name'];
		$CUIT			= Core::FromCUITToNumber($_POST['cuit']);
		$IVA			= $_POST['iva'];
		$VAT			= $_POST['vat'];
		$GrossIncome	= $_POST['gross_income_number'];
		$International	= $_POST['international'];
		switch($_POST['relation'])
		{
			case "1":
				$Customer = 'Y';
				$Provider = 'N';
			break;
			case "2":
				$Customer = 'N';
				$Provider = 'Y';
			break;
			case "3":
				$Customer = 'Y';
				$Provider = 'Y';
			break;
		}
		$Image			= $Object->ProcessImg($_POST['newimage']);
		
		$Update		= Core::Update(self::TABLE,"name='".$Name."',type_id='".$Type."',international='".$International."',cuit=".$CUIT.",iva='".$IVA."',iibb='".$GrossIncome."',vat='".$VAT."',customer='".$Customer."',provider='".$Provider."', logo='".$Image."',updated_by=".$_SESSION[CoreUser::TABLE_ID],self::TABLE_ID."=".$ID);
		//echo $this->LastQuery();
		
		// Tax::SetIVA($CUIT,$IVA);
		CompanyBranch::InsertBranchInfo($ID,true);
		// $Object->InsertBranchInfo(1);
	}
	
	public function Validate()
	{
		echo self::ValidateValue('name',$_POST['name'],$_POST['actualname']);
	}
}
?>
