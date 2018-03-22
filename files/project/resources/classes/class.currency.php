<?php

class Currency 
{
	use CoreSearchList,CoreCrud;//,CoreImage;
	
	const TABLE				= 'currency';
	const TABLE_ID			= 'currency_id';
	const SEARCH_TABLE		= 'currency';//'view_currency_list';

	public function __construct($ID=0)
	{
		$this->ID = $ID;
		$this->GetData();
	}
	
	public static function GetSelectCurrency()
	{
	    return Core::Select(self::TABLE,self::TABLE_ID.",CONCAT(title,' (',prefix,')') AS title","","currency_id");
	}
	
	public static function GetCurrencyPrefix($CurrencyID)
	{
		return Core::Select(self::TABLE,"prefix",self::TABLE_ID."=".$CurrencyID)[0]['prefix'];
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// SEARCHLIST FUNCTIONS ///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	protected static function MakeActionButtonsHTML($Object,$Mode='list')
	{
		//if($Mode!='grid') $HTML .=	'<a class="hint--bottom hint--bounce" aria-label="M&aacute;s informaci&oacute;n"><button type="button" class="btn bg-navy ExpandButton" id="expand_'.$Object->ID.'"><i class="fa fa-plus"></i></button></a> ';
		$HTML	.= 	'<a href="edit.php?id='.$Object->ID.'" class="hint--bottom hint--bounce hint--info" aria-label="Editar"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
		if($Object->Data['status']=="A")
		{
			$HTML	.= '<a class="deleteElement hint--bottom hint--bounce hint--error" aria-label="Eliminar" process="'.PROCESS.'" id="delete_'.$Object->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','delete_question_'.$Object->ID,'&iquest;Desea eliminar la moneda <b>'.$Object->Data['title'].'</b>?');
			$HTML	.= Core::InsertElement('hidden','delete_text_ok_'.$Object->ID,'La moneda <b>'.$Object->Data['title'].'</b> ha sido eliminada.');
			$HTML	.= Core::InsertElement('hidden','delete_text_error_'.$Object->ID,'Hubo un error al intentar eliminar la moneda <b>'.$Object->Data['title'].'</b>.');
		}else{
			$HTML	.= '<a class="activateElement hint--bottom hint--bounce hint--success" aria-label="Activar" process="'.PROCESS.'" id="activate_'.$Object->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','activate_question_'.$Object->ID,'&iquest;Desea activar la moneda <b>'.$Object->Data['title'].'</b>?');
			$HTML	.= Core::InsertElement('hidden','activate_text_ok_'.$Object->ID,'La moneda <b>'.$Object->Data['title'].'</b> ha sido activada.');
			$HTML	.= Core::InsertElement('hidden','activate_text_error_'.$Object->ID,'Hubo un error al intentar activar la moneda <b>'.$Object->Data['title'].'</b>.');
		}
		return $HTML;
	}
	
	protected static function MakeListHTML($Object)
	{
		$HTML = '
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
			<div class="listRowInner">
				<span class="listTextStrong">'.$Object->Data['prefix'].'</span>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
			<div class="listRowInner">
				<span class="listTextStrong">'.$Object->Data['title'].'</span>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
			<div class="listRowInner">
				<span class="listTextStrong">'.$Object->Data['afip_code'].'</span>
			</div>
		</div>
		<div class="col-lg-1 col-md-1 col-sm-1 hideMobile990"></div>';
		return $HTML;
	}
	
	protected static function MakeItemsListHTML($Object)
	{
		return '';
	}
	
	protected static function MakeGridHTML($Object)
	{
		return '';
	}
	
	public static function MakeNoRegsHTML()
	{
		$Entities = 'monedas';
		return '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron '.$Entities.'.</h4><p>Puede crear una nueva moneda haciendo click <a href="new.php">aqui</a>.</p></div>';
	}
	
	protected function SetSearchFields()
	{
		$this->SearchFields['title'] = Core::InsertElement('text','title','','form-control','placeholder="Nombre"');
	}
	
	protected function InsertSearchButtons()
	{
		return '<a href="new.php" class="hint--bottom hint--bounce hint--success" aria-label="Nueva Moneda"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-plus-square"></i></button></a>';
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
		// Basic Data
		$Title	= $_POST['title'];
		$Prefix	= htmlentities($_POST['prefix']);
		$AFIP	= $_POST['afip_code'];
		$APICode= $_POST['api_code'];
		if(strlen($AFIP)==3)
			Core::Insert(self::TABLE,'title,prefix,api_code,afip_code,creation_date,created_by,'.CoreOrganization::TABLE_ID,"'".$Title."','".$Prefix."','".$APICode."','".$AFIP."',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID]);
		else
			echo "402";
	}	
	
	public function Update()
	{
		// Set Object
		$ID 	= $_POST['id'];
		$Object	= new Currency($ID);
		
		// Basic Data
		$Title	= $_POST['title'];
		$Prefix	= htmlentities($_POST['prefix']);
		$AFIP	= $_POST['afip_code'];
		$APICode= $_POST['api_code'];
		if(strlen($AFIP)==3)
			Core::Update(self::TABLE,"title='".$Title."',prefix='".$Prefix."',api_code='".$APICode."',afip_code='".$AFIP."',updated_by=".$_SESSION[CoreUser::TABLE_ID],self::TABLE_ID."=".$ID);
		else
			echo "402";
	}
	
	public function Validate()
	{
		echo self::ValidateValue('title',$_POST['title'],$_POST['actualtitle']);
	}
	
	public function Checkcurrency()
	{
		
		$Currencies = Core::Select(self::TABLE,'*',"status='A' AND last_api_refresh<'".date("Y-m-d")."'","last_api_refresh DESC");
		if(count($Currencies)>0)
		{
			//API url
			$CH = curl_init("http://apilayer.net/api/live?access_key=e9d4aea4e97e025c313a71f4fd98f07a");
			curl_setopt($CH, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($CH, CURLOPT_CUSTOMREQUEST, "GET");
			$Response = curl_exec($CH);
			curl_close($CH);
			if(!$Response)
			{
				echo "API ERROR";
			}else{
				$Response = json_decode($Response, true);
				$Values = $Response['quotes'];
				foreach($Currencies as $Currency)
				{
					$DolarExchange = $Values['USD'.$Currency['api_code']];
					if($DolarExchange)
					{
						// Update dolar exchange rate
						Core::Update(self::TABLE,"dollar_exchange=".$DolarExchange.",last_api_refresh=NOW(),updated_by=".$_SESSION[CoreUser::TABLE_ID],self::TABLE_ID."=".$Currency[self::TABLE_ID]);
						// Insert a history row
					}	Core::Insert('currency_exchange_history',self::TABLE_ID.",dollar_exchange,created_by",$Currency[self::TABLE_ID].",".$DolarExchange.",".$_SESSION[CoreUser::TABLE_ID]);
				}
			}
		}
	}
}
?>
