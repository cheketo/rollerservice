<?php

class ProductRelation
{
	use CoreSearchList,CoreCrud,CoreImage;
	
	const TABLE				= 'product_relation';
	const TABLE_ID			= 'relation_id';
	const SEARCH_TABLE		= 'view_product_relation_list';
	const DEFAULT_IMG		= '../../../../skin/images/products/default/default2.png';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/products/default/';
	const IMG_DIR			= '../../../../skin/images/products/';
	const DEFAULT_FILE_DIR	= '../../../../skin/files/price_list/';

	public function __construct($ID=0)
	{
		$this->ID = $ID;
		$this->GetData();
		self::SetImg($this->Data['img']);
	}
	
	public static function GetLastImport($CompanyID)
	{
		$Data = Core::Select('product_relation_import',"*","status = 'A' AND company_id =".$CompanyID,"creation_date DESC")[0];
		if(!empty($Data))
			$Data['items'] = self::GetImportedProducts($Data['import_id']);
		else
			$Data = false;
		return $Data;
	}
	
	public static function GetImportedProducts($ImportID)
	{
		return Core::Select('product_relation_import_item',"*","import_id=".$ImportID);
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// SEARCHLIST FUNCTIONS ///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	protected static function MakeActionButtonsHTML($Object,$Mode='list')
	{
		if($Mode!='grid') $HTML .=	'<a class="hint--bottom hint--bounce showMobile990" aria-label="M&aacute;s informaci&oacute;n"><button type="button" class="btn bg-navy ExpandButton" id="expand_'.$Object->ID.'"><i class="fa fa-plus"></i></button></a> ';
		$HTML	.= 	'<a href="new.php?id='.$Object->ID.'" class="hint--bottom hint--bounce hint--info" aria-label="Editar"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
		
		$HTML	.= '<a class="deleteElement hint--bottom hint--bounce hint--error" aria-label="Eliminar" process="'.PROCESS.'" id="delete_'.$Object->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
		$HTML	.= Core::InsertElement('hidden','delete_question_'.$Object->ID,'&iquest;Desea eliminar la relaci&oacute;n <b>'.$Object->Data['code'].'</b> ?');
		$HTML	.= Core::InsertElement('hidden','delete_text_ok_'.$Object->ID,'La relaci&oacute;n <b>'.$Object->Data['code'].'</b> ha sido eliminada.');
		$HTML	.= Core::InsertElement('hidden','delete_text_error_'.$Object->ID,'Hubo un error al intentar eliminar el art&iacute;culo <b>'.$Object->Data['code'].'</b>.');
			
		
		return $HTML;
	}
	
	protected static function MakeListHTML($Object)
	{
		$Abstract = $Object->Data['abstract_code']?'<span class="label label-info">'.$Object->Data['abstract_code'].'</span>':'Sin c&oacute;digo asociado';
		$Roller = $Object->Data['product_code']?'<span class="label label-warning">'.$Object->Data['product_code'].'</span>':'Sin c&oacute;digo asociado';
		$Price = $Object->Data['price']>0?'<span class="badge bg-gray text-green"><b>'.$Object->Data['currency_prefix'].' '.$Object->Data['price'].'</b></span>':'Sin especificar';
		$Stock = $Object->Data['stock']>0?'<span class="badge bg-gray text-blue"><b>'.$Object->Data['stock'].'</b></span>':'Sin especificar';
		
		$HTML = '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					<div class="listRowInner">
						<span class="listTextStrong text-muted" style="max-width:100%;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;display: inline-block;">'.$Object->Data['company'].'</span>
						<img class="img-circle hideMobile990" src="'.$Object->Img.'" alt="'.$Object->Data['company'].'">
						<div class="form-inline">
							<div class="form-group">
								<span class="listTextStrong"><span class="label label-primary">'.$Object->Data['code'].'</span></span>
							</div>
							<div class="form-group">
								<span class="listTextStrong"><span class="label bg-purple">'.$Object->Data['brand'].'</span></span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 hideMobile990">
					<div class="listRowInner">
						<span class="listTextStrong">Precio</span>
						<span class="smallTitle txC '.$PriceClass.'">'.$Price.'</span>
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 hideMobile990">
					<div class="listRowInner">
						<span class="listTextStrong">Stock</span>
						<span class="smallTitle txC '.$StockClass.'">'.$Stock.'</span>
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
					<div class="listRowInner">
						<span class="listTextStrong">C&oacute;digo Gen&eacute;rico</span>
						<span class="smallTitle">'.$Abstract.'</span>
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 hideMobile990">
					<div class="listRowInner">
						<span class="listTextStrong">C&oacute;digo Roller</span>
						<span class="smallTitle">'.$Roller.'</span>
					</div>
				</div>
				';
		return $HTML;
	}
	
	protected static function MakeItemsListHTML($Object)
	{
		$HTML .= '
				<div class="row bg-gray" style="padding:5px;">
					
					<div class="col-xs-6 showMobile990">
						<div class="listRowInner">
							<span class="smallDetails"><b>Marca</b></span>
							<span class="label label-primary">'.$Object->Data['brand'].'</span>
						</div>
					</div>
					<div class="col-xs-6 showMobile990">
						<div class="listRowInner">
							<span class="smallDetails"><b>Categor&iacute;a</b></span>
							<span class="label label-primary">'.$Object->Data['category'].'</span>
						</div>
					</div>
					
				</div>';
		return $HTML;
	}
	
	public static function MakeNoRegsHTML()
	{
		return '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron relaciones de art&iacute;culos.</h4><p>Puede crear una nueva relaci&oacute;n de art&iacute;culo haciendo click <a href="new.relation.php">aqui</a>.</p></div>';	
	}
	
	protected function SetSearchFields()
	{
		$this->SearchFields['code'] = Core::InsertElement('text','code','','form-control','placeholder="C&oacute;digo"');
		
		// $this->SearchFields['product_id'] = Core::InsertElement('select',Product::TABLE_ID,'','form-control chosenSelect','',Product::GetFullCodes(),'','Cualquier Art&iacute;culo');
		$this->SearchFields['abstract_id'] = Core::InsertElement('autocomplete','abstract_id','','form-control','placeholder="C&oacute;digo Gen&eacute;rico" placeholderauto="C&oacute;digo no encontrado"','ProductAbstract','SearchAbstractCodes');
		$this->SearchFields['brand_id'] = Core::InsertElement('select',Brand::TABLE_ID,'','form-control chosenSelect','',Core::Select(Brand::TABLE,Brand::TABLE_ID.',name',"status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],"name"),'','Cualquier Marca');
		// $this->SearchFields['category_id'] = Core::InsertElement('select',Category::TABLE_ID,'','form-control chosenSelect','',Core::Select(Category::TABLE,Category::TABLE_ID.',title',"status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],"title"),'','Cualquier L&iacute;nea');
		$this->SearchFields['company_id'] = Core::InsertElement('autocomplete','company_id',$_GET['company_id'].",".$_GET['element'],'form-control','placeholder="Empresa" placeholderauto="Empresa no encontrada"','Company','SearchCompanies');
		$this->SearchFields['price_from'] = Core::InsertElement('text','price_from','','form-control','placeholder="Precio Desde" validateOnlyNumbers="Ingrese &uacute;nicamente n&uacute;meros"');
		$this->SearchFields['price_to'] = Core::InsertElement('text','price_to','','form-control','placeholder="Precio Hasta" validateOnlyNumbers="Ingrese &uacute;nicamente n&uacute;meros"');
		$this->SearchFields['stock_from'] = Core::InsertElement('text','stock_from','','form-control','placeholder="Stock Desde" validateOnlyNumbers="Ingrese &uacute;nicamente n&uacute;meros"');
		$this->SearchFields['stock_to'] = Core::InsertElement('text','stock_to','','form-control','placeholder="Stock Hasta" validateOnlyNumbers="Ingrese &uacute;nicamente n&uacute;meros"');
		$this->HiddenSearchFields['removeget'] = 1;
	}
	
	protected function InsertSearchButtons()
	{
		return '<a href="new.relation.php" class="hint--bottom hint--bounce hint--success" aria-label="Nueva Relaci&oacute;n"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-plus-square"></i></button></a>';
	}
	
	public function ConfigureSearchRequest()
	{
		if($_POST['removeget'])
		{
			// $_POST['company_id'] = $_GET['company_id'];
			unset($_GET['company_id']);
		}
		
		if($_POST['abstract_id'])
		{
			$_POST['abstract_id_condition']="=";
		}
		
		if($_POST['price_from'])
		{
			$Price=$_POST['price_from'];
			$this->AddWhereString(" AND price>=".$Price);
		}
		if($_POST['price_to'])
		{
			$Price=$_POST['price_to'];
			$this->AddWhereString(" AND price<=".$Price);
		}
		
		if($_POST['stock_from'])
		{
			$Stock=$_POST['stock_from'];
			$this->AddWhereString(" AND stock>=".$Stock);
		}
		if($_POST['stock_to'])
		{
			$Stock=$_POST['stock_to'];
			$this->AddWhereString(" AND stock<=".$Stock);
		}
		
		if($_POST['view_order_field']=="price_from" || $_POST['view_order_field']=="price_to")
			$_POST['view_order_field'] = "price";
		
		if($_POST['view_order_field']=="stock_from" || $_POST['view_order_field']=="stock_to")
			$_POST['view_order_field'] = "stock";
			
		$this->SetSearchRequest();
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function Compare()
	{
		
	}
}
?>