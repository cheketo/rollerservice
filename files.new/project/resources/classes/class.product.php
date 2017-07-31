<?php

class Product 
{
	use CoreSearchList,CoreCrud,CoreImage;
	
	var $Providers = array();
	
	const TABLE				= 'product';
	const TABLE_ID			= 'product_id';
	const SEARCH_TABLE		= 'view_product_list';
	const DEFAULT_IMG		= '../../../../skin/images/products/default/default.jpg';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/products/default/';
	const IMG_DIR			= '../../../../skin/images/products/';

	public function __construct($ID=0)
	{
		$this->ID = $ID;
		$this->GetData();
		self::SetImg($this->Data['img']);
	}
	
	// public function GetProviders()
	// {
	// 	if(!$this->Providers)
	// 	{
	// 		$Providers = Core::Select("relation_provider",'provider_id',$this->TableID." =".$this->ID);
	// 		foreach($Providers as $Provider)
	// 		{
	// 			if($ProvidersID) $ProvidersID .= ',';
	// 			$ProvidersID .= $Provider['provider_id'];
	// 		}
	// 		$this->Providers = Core::Select('provider','*',"status='A' AND provider_id IN ('.$ProvidersID.')");
	// 	}
	// 	return $this->Providers;
	// }

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
			$HTML	.= Core::InsertElement('hidden','delete_question_'.$Object->ID,'&iquest;Desea eliminar el art&iacute;culo <b>'.$Object->Data['code'].'</b> ?');
			$HTML	.= Core::InsertElement('hidden','delete_text_ok_'.$Object->ID,'El art&iacute;culo <b>'.$Object->Data['code'].'</b> ha sido eliminado.');
			$HTML	.= Core::InsertElement('hidden','delete_text_error_'.$Object->ID,'Hubo un error al intentar eliminar el art&iacute;culo <b>'.$Object->Data['code'].'</b>.');
			
		}else{
			$HTML	.= '<a class="activateElement hint--bottom hint--bounce hint--success" aria-label="Activar" process="'.PROCESS.'" id="activate_'.$Object->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','activate_question_'.$Object->ID,'&iquest;Desea activar el art&iacute;culo <b>'.$Object->Data['code'].'</b> ?');
			$HTML	.= Core::InsertElement('hidden','activate_text_ok_'.$Object->ID,'El art&iacute;culo <b>'.$Object->Data['code'].'</b> ha sido activado.');
			$HTML	.= Core::InsertElement('hidden','activate_text_error_'.$Object->ID,'Hubo un error al intentar activar el art&iacute;culo <b>'.$Object->Data['code'].'</b>.');
		}
		return $HTML;
	}
	
	protected static function MakeListHTML($Object)
	{
		$StockLabel = $Object->Data['stock_min']>$Object->Data['stock'] || $Object->Data['stock_max']<$Object->Data['stock']? 'warning':'primary';
		$StockLabel = $Object->Data['stock']==0? 'danger':$StockLabel;
		
		$HTML = '<div class="col-lg-3 col-md-4 col-sm-4 col-xs-7">
					<div class="listRowInner">
						<img class="img-circle hideMobile990" src="'.$Object->Img.'" alt="'.$Object->Data['code'].'">
						<span class="listTextStrong">'.$Object->Data['code'].'</span>
						<span class="smallTitle"><b>'.$Object->Data['brand'].'</b></span>
					</div>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 hideMobile990">
					<div class="listRowInner">
						<span class="listTextStrong">Stock</span>
						<span class="listTextStrong"><span class="label label-'.$StockLabel.'">'.$Object->Data['stock'].'</span></span>
					</div>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-5">
					<div class="listRowInner">
						<span class="listTextStrong">Precio</span>
						<span class="listTextStrong"><span class="label label-success">'.Core::FromDBToMoney($Object->Data['price']).'</span></span>
					</div>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 hideMobile990">
					<div class="listRowInner">
						<span class="smallTitle">'.$Object->Data['description'].'</span>
					</div>
				</div>
				';
		return $HTML;
	}
	
	protected static function MakeItemsListHTML($Object)
	{
		$StockLabel = $Object->Data['stock_min']>$Object->Data['stock'] || $Object->Data['stock_max']<$Object->Data['stock']? 'warning':'primary';
		$StockLabel = $Object->Data['stock']==0? 'danger':$StockLabel;
		$Object->Data['rack'] = $Object->Data['rack'] ? '<span class="label label-brown">'.$Object->Data['rack'].'</span>':'No especificado';
		$Object->Data['size'] = $Object->Data['size']? $Object->Data['size']:'Sin especificar';
		$Object->Data['description'] = $Object->Data['description']? '<span class="smallDetails"><b>Descripci&oacute;n</b></span>'.$Object->Data['description']:'';
		$StockMM = $Object->Data['stock_max']>0? '<span class="label label-primary">'.$Object->Data['stock_min'].'/'.$Object->Data['stock_max'].'</span>':'Indistinto';
		$HTML .= '
				<div class="row bg-gray" style="padding:5px;">
					<div class="col-md-3 col-sm-4 col-xs-6">
						<div class="listRowInner">
							<span class="smallDetails"><b>L&iacute;nea</b></span>
							'.$Object->Data['category'].'
						</div>
					</div>
					<div class="col-md-1 col-sm-1 col-xs-6">
						<div class="listRowInner">
							<span class="itemRowtitle">
								<span class="smallDetails"><b>Stock Min/Max</b></span> 
								'.$StockMM.'
							</span>
						</div>
					</div>
					<div class="col-xs-6 showMobile990">
						<div class="listRowInner">
							<span class="smallDetails"><b>Stock</b></span>
							<span class="label label-'.$StockLabel.'">'.$Object->Data['stock'].'</span>
						</div>
					</div>
					<div class="col-md-1 col-sm-1 col-xs-6">
						<div class="listRowInner">
							<span class="smallDetails"><b>Estanter&iacute;a</b></span>
							'.$Object->Data['rack'].'
						</div>
					</div>
					<div class="col-md-1 col-sm-2 col-xs-6"">
						<div class="listRowInner">
							<span class="smallDetails"><b>Medidas</b></span>
							'.$Object->Data['size'].'
						</div>
					</div>
					<div class="col-xs-12 showMobile990">
						<div class="listRowInner">
						'.$Object->Data['description'].'
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
		                <img src="'.$Object->Img.'" alt="'.$Object->Data['code'].'" class="img-responsive">
		                <div class="imgSelectorContent">
		                  <div class="roundItemBigActions">
		                    '.$ButtonsHTML.'
		                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
		                  </div>
		                </div>
		              </div>
		              <div class="roundItemText">
		                <p><b>'.$Object->Data['code'].'</b></p>
		                <p>('.ucfirst($Object->Data['category']).')</p>
		              </div>
		            </div>';
		return $HTML;
	}
	
	public static function MakeNoRegsHTML()
	{
		return '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron art&iacute;culos.</h4><p>Puede crear un nuevo art&iacute;culo haciendo click <a href="new.php">aqui</a>.</p></div>';	
	}
	
	protected function SetSearchFields()
	{
		$this->SearchFields['code'] = Core::InsertElement('text','code','','form-control','placeholder="C&oacute;digo"');
		$this->SearchFields['stock_from'] = Core::InsertElement('text','stock_from','','form-control','placeholder="Stock Desde"');
		$this->SearchFields['stock_to'] = Core::InsertElement('text','stock_to','','form-control','placeholder="Stock Hasta"');
		$this->SearchFields['brand_id'] = Core::InsertElement('select',Brand::TABLE_ID,'','form-control chosenSelect','',Core::Select(Brand::TABLE,Brand::TABLE_ID.',name',"status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],"name"),'','Cualquier Marca');
		$this->SearchFields['category_id'] = Core::InsertElement('select',Category::TABLE_ID,'','form-control chosenSelect','',Core::Select(Category::TABLE,Category::TABLE_ID.',title',"status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],"title"),'','Cualquier L&iacute;nea');
		$this->SearchFields['price_from'] = Core::InsertElement('text','price_from','','form-control','placeholder="Precio Desde"');
		$this->SearchFields['price_to'] = Core::InsertElement('text','price_to','','form-control','placeholder="Precio Hasta"');
	}
	
	protected function InsertSearchButtons()
	{
		return '<a href="new.php" class="hint--bottom hint--bounce hint--success" aria-label="Nuevo Art&iacute;culo"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-plus-square"></i></button></a>';
	}
	
	public function ConfigureSearchRequest()
	{
		if($_POST['price_from'])
		{
			$Price=Core::FromMoneyToDB($_POST['price_from']);
			$this->AddWhereString(" AND price>=".$Price);
		}
		if($_POST['price_to'])
		{
			$Price=Core::FromMoneyToDB($_POST['price_to']);
			$this->AddWhereString(" AND price<=".$Price);
		}
		
		if($_POST['stock_from'])
		{
			$this->AddWhereString(" AND stock>=".$_POST['stock_from']);
		}
		if($_POST['stock_to'])
		{
			$this->AddWhereString(" AND stock<=".$_POST['stock_to']);
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
	
	public function Insert()
	{
		$Code		= $_POST['code'];
		$Category	= $_POST['category'];
		$Price		= str_replace('$','',$_POST['price']);
		$Brand		= $_POST['brand'];
		$Rack		= $_POST['rack'];
		$Size		= $_POST['size'];
		$Stock		= $_POST['stock'];
		$StockMin	= $_POST['stock_min'];
		$StockMax	= $_POST['stock_max'];
		$Description= $_POST['description'];
		$Dispatch	= $_POST['dispatch'];
		$PriceFob	= $_POST['price_fob'];
		$PriceDispatch	= $_POST['price_dispatch'];
		if(!$Stock) $Stock = 0;
		if(!$StockMin) $StockMin = 0;
		if(!$StockMax) $StockMax = 0;
		if(!$PriceFob) $PriceFob = 0;
		if(!$PriceDispatch) $PriceDispatch = 0;
		Core::Insert(self::TABLE,'code,'.Category::TABLE_ID.',price,'.Brand::TABLE_ID.',rack,size,stock_min,stock_max,description,creation_date,organization_id,created_by',"'".$Code."',".$Category.",".$Price.",".$Brand.",'".$Rack."','".$Size."',".$StockMin.",".$StockMax.",'".$Description."',NOW(),".$_SESSION[CoreOrganization::TABLE_ID].",".$_SESSION[CoreUser::TABLE_ID]);
		//echo $this->LastQuery();
	}	
	
	public function Update()
	{
		$ID 		= $_POST['id'];
		$Edit		= new Product($ID);
		
		$Code		= $_POST['code'];
		$Category	= $_POST['category'];
		$Price		= str_replace('$','',$_POST['price']);
		$Brand		= $_POST['brand'];
		$Rack		= $_POST['rack'];
		$Size		= $_POST['size'];
		$StockMin	= $_POST['stock_min'];
		$StockMax	= $_POST['stock_max'];
		$Description= $_POST['description'];
		if(!$StockMin) $StockMin = 0;
		if(!$StockMax) $StockMax = 0;
		Core::Update(self::TABLE,"code='".$Code."',".Category::TABLE_ID."=".$Category.",".Brand::TABLE_ID."=".$Brand.",price=".$Price.",rack='".$Rack."',size='".$Size."',stock_min='".$StockMin."',stock_max='".$StockMax."',description='".$Description."',updated_by=".$_SESSION[CoreUser::TABLE_ID],self::TABLE_ID."=".$ID);
		//echo $this->LastQuery();
	}
	
	public function Validate()
	{
		self::ValidateValue("code",$_POST['code'],$_POST['actualcode']);
	}
}
?>
