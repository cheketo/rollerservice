<?php

class Quotation 
{
	use CoreSearchList,CoreCrud,CoreImage;
	
	const TABLE				= 'quotation';
	const TABLE_ID			= 'quotation_id';
	const SEARCH_TABLE		= 'view_quotation_list';
	const DEFAULT_FILE_DIR	= '../../../../skin/files/quotation/';
	const DEFAULT_IMG		= '../../../../skin/images/quotations/default/default.png';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/quotations/default/';
	const IMG_DIR			= '../../../../skin/images/quotations/';

	public function __construct($ID=0)
	{
		$this->ID = $ID;
		if($this->ID!=0)
		{
			$Data = Core::Select(self::SEARCH_TABLE,'*',self::TABLE_ID."=".$this->ID,self::TABLE_ID);
			$this->Data = $Data[0];
			$this->Data['items'] = $Data;
		}
	}
	
	public static function GetParams()
	{
		if($_GET['provider'] && $_GET['provider']!="undefined" )
			$Params .= '&provider='.$_GET['provider'];
		else
			$Params .= '&provider=N';
		if($_GET['customer'] && $_GET['customer']!="undefined" )
			$Params .= '&customer='.$_GET['customer'];
		else
			$Params .= '&customer=N';
		if($_GET['international'] && $_GET['international']!="undefined")
			$Params .= '&international='.$_GET['international'];
		else
			$Params .= '&international=N';
		return $Params;
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// SEARCHLIST FUNCTIONS ///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	protected static function MakeActionButtonsHTML($Object,$Mode='list')
	{
		if($Mode!='grid') $HTML .=	'<a class="hint--bottom hint--bounce" aria-label="M&aacute;s informaci&oacute;n"><button type="button" class="btn bg-navy ExpandButton" id="expand_'.$Object->ID.'"><i class="fa fa-plus"></i></button></a> ';;
		if($Object->Data['status']!="I")
		{
			$HTML	.= '<a class="hint--bottom hint--bounce" aria-label="Ver Detalle" href="view.php?id='.$Object->ID.'" id="payment_'.$Object->ID.'"><button type="button" class="btn btn-github"><i class="fa fa-eye"></i></button></a> ';
			if($Object->Data['status']!="F")
			{
				$HTML	.= '<a class="hint--bottom hint--bounce hint--success" aria-label="Crear Orden" process="'.PROCESS.'" id="purchase_'.$Object->ID.'" status="'.$Row->Data['status'].'"><button type="button" class="btn bg-olive"><i class="fa fa-truck"></i></button></a> ';
				$HTML	.= '<a class="hint--bottom hint--bounce hint--info storeElement" aria-label="Archivar" process="'.PROCESS.'" id="store_'.$Object->ID.'"><button type="button" class="btn btn-primary"><i class="fa fa-archive"></i></button></a>';	
				$HTML	.= '<a href="edit.php?id='.$Object->ID.self::GetParams().'" class="hint--bottom hint--bounce hint--info" aria-label="Editar"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
				$HTML	.= '<a class="deleteElement hint--bottom hint--bounce hint--error" aria-label="Eliminar" process="'.PROCESS.'" id="delete_'.$Object->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
				$HTML	.= Core::InsertElement('hidden','delete_question_'.$Object->ID,'&iquest;Desea eliminar la cotizaci&oacute;n de <b>'.$Object->Data['company'].'</b>?');
				$HTML	.= Core::InsertElement('hidden','delete_text_ok_'.$Object->ID,'La cotizaci&oacute;n de <b>'.$Object->Data['company'].'</b> ha sido eliminada.');
				$HTML	.= Core::InsertElement('hidden','delete_text_error_'.$Object->ID,'Hubo un error al intentar eliminar la cotizaci&oacute;n de <b>'.$Object->Data['company'].'</b>.');
			}
		}else{
			$HTML	.= '<a class="activateElement hint--bottom hint--bounce hint--success" aria-label="Activar" process="'.PROCESS.'" id="activate_'.$Object->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','activate_question_'.$Object->ID,'&iquest;Desea activar la cotizaci&oacute;n de <b>'.$Object->Data['company'].'</b>?');
			$HTML	.= Core::InsertElement('hidden','activate_text_ok_'.$Object->ID,'La cotizaci&oacute;n de <b>'.$Object->Data['company'].'</b> ha sido activada.');
			$HTML	.= Core::InsertElement('hidden','activate_text_error_'.$Object->ID,'Hubo un error al intentar activar la cotizaci&oacute;n de <b>'.$Object->Data['company'].'</b>.');
		}
		return $HTML;
	}
	
	protected static function MakeListHTML($Object)
	{
		$HTML = '<div class="col-lg-4 col-md-5 col-sm-5 col-xs-3">
					<div class="listRowInner">
						<img class="img-circle hideMobile990" src="'.Quotation::DEFAULT_IMG.'" alt="'.$Object->Data['company'].'">
						<span class="listTextStrong">'.$Object->Data['company'].'</span>
						<span class="smallTitle"><b>(ID: '.$Object->Data['quotation_id'].')</b></span>
					</div>
				</div>
				<div class="col-lg-3 col-md-2 col-sm-2 col-xs-3">
					<div class="listRowInner">
						<span class="smallTitle">Total</span>
						<span class="listTextStrong">
							<span class="label label-brown">'.$Object->Data['currency'].' '.$Object->Data['total_quotation'].'</span>
						</span>
					</div>
				</div>
				<div class="col-lg-1 col-md-2 col-sm-3 col-xs-3">
					<div class="listRowInner">
						<span class="smallTitle">Entrega</span>
						<span class="listTextStrong"><span class="label label-info">
							'.Core::FromDBToDate($Object->Data['creation_date']).'
						</span></span>
					</div>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 hideMobile990"></div>';
		return $HTML;
	}
	
	protected static function MakeItemsListHTML($Object)
	{
		foreach($Object->Data['items'] as $Item)
		{
			$RowClass = $RowClass != 'bg-gray'? 'bg-gray':'bg-gray-active';
			$HTML .= '
						<div class="row '.$RowClass.'" style="padding:5px;">
							<div class="col-lg-4 col-sm-5 col-xs-12">
								<div class="listRowInner">
									<img class=" hideMobile990" src="'.Product::DEFAULT_IMG.'" alt="'.$Item['code'].'">
									<span class="listTextStrong">'.$Item['code'].'</span>
									<span class="smallTitle hideMobile990"><b>'.$Item['category'].' ('.$Item['brand'].')</b></span>
								</div>
							</div>
							<div class="col-sm-2 col-xs-12">
								<div class="listRowInner">
									<span class="smallTitle">Precio</span>
									<span class="emailTextResp"><span class="label label-brown">'.$Item['currency'].' '.$Item['price'].'</span></span>
								</div>
							</div>
							<div class="col-sm-3 col-xs-12">
								<div class="listRowInner">
									<span class="smallTitle">Cantidad</span>
									<span class="listTextStrong"><span class="label bg-navy">'.$Item['total_quantity'].'</span></span>
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
		                <img src="'.$Object->Img.'" alt="'.$Object->Data['company'].'" class="img-responsive">
		                <div class="imgSelectorContent">
		                  <div class="roundItemBigActions">
		                    '.$ButtonsHTML.'
		                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
		                  </div>
		                </div>
		              </div>
		              <div class="roundItemText">
		                <p><b>'.$Object->Data['company'].'</b></p>
		                <p>('.$Object->Data['quotation_id'].')</p>
		              </div>
		            </div>';
		return $HTML;
	}
	
	public static function MakeNoRegsHTML()
	{
		return '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron cotizaciones.</h4><p>Puede crear una nueva haciendo click <a href="new.php?'.self::GetParams().'">aqui</a>.</p></div>';
	}
	
	protected function SetSearchFields()
	{
		$this->SearchFields['quotation_id'] = Core::InsertElement('text','quotation_id','','form-control','placeholder="C&oacute;digo"');
		$this->SearchFields['code'] = Core::InsertElement('text','code','','form-control inputMask','placeholder="Art&iacute;culo"');
		$this->SearchFields['quantity'] = Core::InsertElement('text','quantity','','form-control','placeholder="Cantidad"');
	}
	
	protected function InsertSearchButtons()
	{
		return '<a href="new.php?'.self::GetParams().'" class="hint--bottom hint--bounce hint--success" aria-label="Nueva Cotizaci&oacute;n"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-plus-square"></i></button></a>';
	}
	
	public function ConfigureSearchRequest()
	{
		$_POST['view_order_mode'] = $_POST['view_order_mode']? $_POST['view_order_mode']:'DESC';
		$_POST['view_order_field'] = $_POST['view_order_field']? $_POST['view_order_field']:'quotation_id';
		$this->SetSearchRequest();
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function Insert()
	{
		// ITEMS DATA
		$Total = 0;
		$Items = array();
		for($I=1;$I<=$_POST['items'];$I++)
		{
			if($_POST['item_'.$I])
			{
				$Total += ($_POST['price_'.$I]*$_POST['quantity_'.$I]);
				$ItemDate = Core::FromDateToDB($_POST['date_'.$I]);
				$Items[] = array('id'=>$_POST['item_'.$I],'price'=>$_POST['price_'.$I],'quantity'=>$_POST['quantity_'.$I], 'delivery_date'=>$ItemDate, 'days'=>$_POST['day_'.$I]);
				if(!$Date)
				{
					$Date = $ItemDate;
				}
				if(strtotime($ItemDate." 00:00:00") > strtotime($Date." 00:00:00")){
					$Date = $ItemDate;
				}
			}
		}
		
		// Basic Data
		$TypeID			= $_POST['type_id'];
		$CompanyID		= $_POST['company'];
		$AgentID 		= $_POST['agent']? $_POST['agent']: 0;
		$CurrencyID		= $_POST['currency'];
		$Extra			= $_POST['extra'];
		$Field			= $_POST['company_type'].'_id';
		$NewID			= Core::Insert(self::TABLE,'type_id,company_id,'.$Field.',agent_id,currency_id,total,extra,delivery_date,status,creation_date,created_by,'.CoreOrganization::TABLE_ID,$TypeID.",".$CompanyID.",".$CompanyID.",".$AgentID.",".$CurrencyID.",".$Total.",'".$Extra."','".$Date."','A',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION['organization_id']);
		// INSERT ITEMS
		foreach($Items as $Item)
		{
			$Item['days'] = $Item['days']?intval($Item['days']):"0";
			if($Fields)
				$Fields .= "),(";
			$Fields .= $NewID.",".$CompanyID.",".$Item['id'].",".$Item['price'].",".$Item['quantity'].",".($Item['price']*$Item['quantity']).",'".$Item['delivery_date']."',".$Item['days'].",".$CurrencyID.",NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID];
		}
		Core::Insert(QuotationItem::TABLE,self::TABLE_ID.','.Company::TABLE_ID.','.Product::TABLE_ID.',price,quantity,total,delivery_date,days,currency_id,creation_date,created_by,'.CoreOrganization::TABLE_ID,$Fields);	
	}
	
	public function Update()
	{
		$ID 	= $_POST['id'];
		$Edit	= new Quotation($ID);
		
		// ITEMS DATA
		$Total = 0;
		$Items = array();
		for($I=1;$I<=$_POST['items'];$I++)
		{
			if($_POST['item_'.$I])
			{
				$Total += ($_POST['price_'.$I]*$_POST['quantity_'.$I]);
				$ItemDate = Core::FromDateToDB($_POST['date_'.$I]);
				$CreationDate = $_POST['creation_date_'.$I]? "'".$_POST['creation_date_'.$I]."'":'NOW()';
				$Items[] = array('id'=>$_POST['item_'.$I],'price'=>$_POST['price_'.$I],'quantity'=>$_POST['quantity_'.$I], 'delivery_date'=>$ItemDate,'creation_date'=>$CreationDate,'days'=>$_POST['day_'.$I]);
				if(!$Date)
				{
					$Date = $ItemDate;
				}
				if(strtotime($ItemDate." 00:00:00") > strtotime($Date." 00:00:00")){
					$Date = $ItemDate;
				}
			}
		}
		
		// Basic Data
		$CompanyID		= $_POST['company'];
		$AgentID 		= $_POST['agent']? $_POST['agent']: 0;
		$CurrencyID		= $_POST['currency'];
		$Extra			= $_POST['extra'];
		$Update		= Core::Update(self::TABLE,Company::TABLE_ID."=".$CompanyID.",agent_id=".$AgentID.",currency_id=".$CurrencyID.",delivery_date='".$Date."',extra='".$Extra."',total=".$Total.",updated_by=".$_SESSION[CoreUser::TABLE_ID],self::TABLE_ID."=".$ID);
		
		// DELETE OLD ITEMS
		QuotationItem::DeleteItems($ID);
		
		// INSERT ITEMS
		foreach($Items as $Item)
		{
			$Item['days'] = $Item['days']?intval($Item['days']):"0";
			if($Fields)
				$Fields .= "),(";
			$Fields .= $ID.",".$CompanyID.",".$Item['id'].",".$Item['price'].",".$Item['quantity'].",".($Item['price']*$Item['quantity']).",'".$Item['delivery_date']."',".$Item['days'].",".$CurrencyID.",".$Item['creation_date'].",".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID];
		}
		Core::Insert(QuotationItem::TABLE,self::TABLE_ID.','.Company::TABLE_ID.','.Product::TABLE_ID.',price,quantity,total,delivery_date,days,currency_id,creation_date,created_by,'.CoreOrganization::TABLE_ID,$Fields);
	}
	
	public function Store()
	{
		$ID	= $_POST['id'];
		Core::Update(self::TABLE,"status = 'F'",self::TABLE_ID."=".$ID);
	}
	
	public function Addnewfile()
	{
		if(count($_FILES['tfile'])>0)
		{
			$ProductID = $_POST['product'];
			$QuotationID = $_POST['id'];
			$FileDir = self::DEFAULT_FILE_DIR."new/";
			//$FileName = date("s").date("i").date("H").date("d").date("m").date("Y");
			$FileName = explode(".",str_replace(" ","",$_FILES['tfile']['name']));
			$Ext = $FileName[count($FileName)-1];
			unset($FileName[count($FileName)-1]);
			$FileName = implode(".",$FileName);
			$File = new CoreFileData($_FILES['tfile'],$FileDir,$FileName);
			if($Ext!="jpg" || $Ext!="jpeg" || $Ext!="png" || $Ext!="bmp")
			{
				$File->SaveFile();
				$FileURL = $FileDir.$FileName.".".$File->GetExtension();
			}else{
				$FileURL = $File	-> BuildImage(200,200);	
			}
			
			
			
			$ID = Core::Insert('quotation_file_new',"product_id,name,url,creation_date,created_by,organization_id",$ProductID.",'".$FileName."','".$FileURL."',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID]);
			$File = array("id"=>$ID,"name"=>$FileName,"url"=>$FileURL,"ext"=>$Ext);
			echo json_encode($File,JSON_HEX_QUOT);
		}
	}
	
	public function Removenewfile()
	{
		$FileID = $_GET['fid'];
		Core::Update('quotation_file_new',"status='I',updated_by=".$_SESSION[CoreUser::TABLE_ID],"file_id=".$FileID);
	}
	
	public function Newquotation()
	{
		$ProductID = $_POST['product'];
		$Days = $_POST['tday'];
		$Date = Core::FromDateToDB($_POST['tdate']);
		$Price = $_POST['tprice'];
		$Quantity = $_POST['tquantity'];
		$CompanyID = $_POST['tprovider'];
		$CurrencyID = $_POST['tcurrency'];
		
		$Extra = $_POST['textra'];
		$FilesCount = $_POST['filecount'];
		$IDs = "0";
		$Total = $Price*$Quantity;
		
		$International = Core::Select(Company::TABLE,'international',Company::TABLE_ID."=".$CompanyID)[0]['international'];
		$TypeID = $International=='N'? 1:2;
		
		$QuotationID = Core::Insert(self::TABLE,"company_id,sender_id,currency_id,total,type_id,status,quotation_date,extra,creation_date,created_by,organization_id",$CompanyID.",".$CompanyID.",".$CurrencyID.",".$Total.",".$TypeID.",'A','".$Date."','".$Extra."',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID]);
		$Field = $QuotationID.",".$CompanyID.",".$ProductID.",".$Price.",".$Quantity.",".$Total.",".$Days.",".$CurrencyID.",NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID];
		Core::Insert(QuotationItem::TABLE,self::TABLE_ID.','.Company::TABLE_ID.','.Product::TABLE_ID.',price,quantity,total,days,currency_id,creation_date,created_by,'.CoreOrganization::TABLE_ID,$Field);
		for($I=1;$I<=$FilesCount;$I++)
		{
			
			$FileID = $_POST['fileid_'.$I];
			if($FileID)
			{
				$IDs .= ",".$FileID;
			}
		}
		$Files = Core::Select("quotation_file_new","*","status='A' AND file_id IN (".$IDs.")");
		$FilePath = '../../../../skin/files/quotation/'.$QuotationID.'/';
		foreach($Files as $File)
		{
			$FileObj = new CoreFileData($File['url']);
			$FileObj->MoveFileTo($FilePath);
			$NewUrl = $FileObj->GetFile();
			
			$Field = $File['file_id'].",".$QuotationID.",".$ProductID.",'".$File['name']."','".$NewUrl."',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID];
			$Fields .= $Fields? "),(".$Field:$Field;
		}
		if($Fields)
			Core::Insert("quotation_file","new_id,quotation_id,product_id,name,url,creation_date,created_by,organization_id",$Fields);
		Core::Delete("quotation_file_new","status='A' AND DATEDIFF(CURDATE(),creation_date)>0");
		echo json_encode(array("id"=>$QuotationID,"filepath"=>$FilePath,"currency"=>Currency::GetCurrencyPrefix($CurrencyID)),JSON_HEX_QUOT);
	}
	
	public function Fillproviderquotations()
	{
		$ProductID = $_POST['product'];
		$Quotations = Core::Select(self::SEARCH_TABLE,"*","receiver_id=0 AND ".Product::TABLE_ID."=".$ProductID,'quotation_date DESC,creation_date DESC,quotation_id DESC');
		foreach($Quotations as $Quotation)
		{
			$FilesHTML = "";
			$Files = Core::Select('quotation_file',"*",self::TABLE_ID."=".$Quotation[self::TABLE_ID]);
			if(count($Files)>0)
			{
				foreach($Files as $File)
				{
					$IconURL = Core::GetFileIcon($File['url']);
					$FilesHTML .= '<div><a href="'.$File['url'].'" target="_blank"><img src="'.$IconURL.'" width="32" height="32"> '.$File['name'].'</a></div>';
				}
			}
			$HTML .= '<tr class="ClearWindow">
		                <td>'.Core::FromDBToDate($Quotation['quotation_date']).'</td>
		                <td>'.$Quotation['company'].'</td>
		                <td><span class="label label-success">'.$Quotation['currency'].' '.$Quotation['price'].'</span></td>
		                <td>'.$Quotation['quantity'].'</td>
		                <td>'.$Quotation['currency'].' '.$Quotation['total_item'].'</td>
		                <td>'.$Quotation['days'].' D&iacute;as</td>
		                <td>'.$Quotation['extra'].'</td>
		                <td>'.$FilesHTML.'</td>
		              </tr>';
		}
		echo $HTML;
	}
	
	public function Fillcustomerquotations()
	{
		$ProductID = $_POST['product'];
		$CompanyID = $_POST['company'];
		$Quotations = Core::Select(self::SEARCH_TABLE,"*","sender_id=0 AND ".Company::TABLE_ID."=".$CompanyID." AND ".Product::TABLE_ID."=".$ProductID,'quotation_date DESC,creation_date DESC,quotation_id DESC');
		foreach($Quotations as $Quotation)
		{
			$HTML .= '<tr class="ClearWindow">
						<td><span class="label label-default">'.Core::FromDBToDate($Quotation['quotation_date']).'</span></td>
		                <td><span class="label label-success">'.$Quotation['currency'].' '.$Quotation['price'].'</span></td>
		                <td>'.$Quotation['quantity'].'</td>
		                <td><span class="label label-success">'.$Quotation['currency'].' '.$Quotation['total_item'].'</span></td>
		                <td><span class="label label-warning">'.$Quotation['days'].' D&iacute;as</span></td>
		                <td>
		                  <button type="button" class="btn btn-github SeeQuotation hint--bottom hint--bounce" aria-label="Ver Cotizaci&oacute;n" style="margin:0px;" item="'.$Quotation[self::TABLE_ID].'"><i class="fa fa-eye"></i></button>
		                </td>
		              </tr>';
		}
		echo $HTML;
	}
	
}
?>
