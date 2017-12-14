<?php

class ProductAbstract
{
	use CoreSearchList,CoreCrud,CoreImage;
	
	const TABLE				= 'product_abstract';
	const TABLE_ID			= 'abstract_id';
	const SEARCH_TABLE		= 'view_product_abstract_list';
	const DEFAULT_IMG		= '../../../../skin/images/products/default/default.png';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/products/default/';
	const IMG_DIR			= '../../../../skin/images/products/';

	public function __construct($ID=0)
	{
		
		$this->ID = $ID;
		if($this->ID!=0)
		{
			$Data = Core::Select(self::SEARCH_TABLE,'*',self::TABLE_ID."=".$this->ID,self::TABLE_ID);
			$this->Data = $Data[0];
			$this->Data['products'] = $Data;
			foreach($this->Data['products'] as $Product)
			{
				$this->Data['stock'] += $Product['product_stock'];
			}
		}
		self::SetImg($this->Data['img']);
	}
	
	// public static function GetFullCodes()
	// {
	// 	return Core::Select(Product::SEARCH_TABLE,"product_id,CONCAT(code,' ',category,' - ',brand) AS code","status='A' AND organization_id=".$_SESSION['organization_id'],'code','',10);
	// }
	
	public static function SearchCodes()
	{
		$Products =  Core::Select(Product::SEARCH_TABLE,Product::TABLE_ID." as id,CONCAT('',code,' - <b>',category,'</b> - STOCK:',stock) as text","status='A' AND code LIKE '%".$_GET['text']."%' AND organization_id=".$_SESSION['organization_id'],'code','code',100);
		if(empty($Products))
			$Products[0]=array("id"=>"","text"=>"no-result");
		else
			
		echo json_encode($Products,JSON_HEX_QUOT);
		
	}
	
	public static function SearchAbstractCodes()
	{
		$Products =  Core::Select(self::SEARCH_TABLE,self::TABLE_ID." as id,CONCAT('',code,' - <b>',category,'</b>') as text","status='A' AND code LIKE '%".$_GET['text']."%' AND organization_id=".$_SESSION['organization_id'],'code','code',100);
		if(empty($Products))
			$Products[0]=array("id"=>"","text"=>"no-result");
		else
			
		echo json_encode($Products,JSON_HEX_QUOT);
		
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
			$HTML	.= Core::InsertElement('hidden','delete_question_'.$Object->ID,'&iquest;Desea eliminar el art&iacute;culo gen&eacute;rico <b>'.$Object->Data['code'].'</b> ?');
			$HTML	.= Core::InsertElement('hidden','delete_text_ok_'.$Object->ID,'El art&iacute;culo gen&eacute;rico <b>'.$Object->Data['code'].'</b> ha sido eliminado.');
			$HTML	.= Core::InsertElement('hidden','delete_text_error_'.$Object->ID,'Hubo un error al intentar eliminar el art&iacute;culo gen&eacute;rico <b>'.$Object->Data['code'].'</b>.');
			
		}else{
			$HTML	.= '<a class="activateElement hint--bottom hint--bounce hint--success" aria-label="Activar" process="'.PROCESS.'" id="activate_'.$Object->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','activate_question_'.$Object->ID,'&iquest;Desea activar el art&iacute;culo gen&eacute;rico <b>'.$Object->Data['code'].'</b> ?');
			$HTML	.= Core::InsertElement('hidden','activate_text_ok_'.$Object->ID,'El art&iacute;culo gen&eacute;rico <b>'.$Object->Data['code'].'</b> ha sido activado.');
			$HTML	.= Core::InsertElement('hidden','activate_text_error_'.$Object->ID,'Hubo un error al intentar activar el art&iacute;culo gen&eacute;rico <b>'.$Object->Data['code'].'</b>.');
		}
		return $HTML;
	}
	
	protected static function MakeListHTML($Object)
	{
		
		$ProductsLabel = count($Object->Data['products'])>0 ? 'info':'default';
		$StockLabel = $Object->Data['stock'] ? 'primary':'danger';
		$Object->Data['category'] = $Object->Data['category']? $Object->Data['category']:'Sin L&iacute;nea';
		$CountProducts = $Object->Data['product_code']? count($Object->Data['products']):0;
		//$Object->Data['stock'] = '!ERROR!'; // Must be added on the view table
		
		$HTML = '<div class="col-lg-3 col-md-4 col-sm-4 col-xs-7">
					<div class="listRowInner">
						<img class="img-circle hideMobile990" src="'.$Object->Img.'" alt="'.$Object->Data['code'].'">
						<span class="listTextStrong">'.$Object->Data['code'].'</span>
					</div>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 hideMobile990">
					<div class="listRowInner">
						<span class="listTextStrong">L&iacute;nea</span>
						<span class="listTextStrong"><span class="label label-default">'.$Object->Data['category'].'</span></span>
					</div>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 hideMobile990">
					<div class="listRowInner">
						<span class="listTextStrong">Art&iacute;culos</span>
						<span class="listTextStrong"><span class="label label-'.$ProductsLabel.'">'.$CountProducts.'</span></span>
					</div>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 hideMobile990">
					<div class="listRowInner">
						<span class="listTextStrong">Stock</span>
						<span class="listTextStrong"><span class="label label-'.$StockLabel.'">'.$Object->Data['stock'].'</span></span>
					</div>
				</div>
				';
		return $HTML;
	}
	
	protected static function MakeItemsListHTML($Object)
	{
		foreach($Object->Data['products'] as $Item)
		{
			if($Item['product_code'])
			{
				$RowClass = $RowClass != 'bg-gray'? 'bg-gray':'bg-gray-active';
				$HTML .= '
							<div class="row '.$RowClass.'" style="padding:5px;">
								<div class="col-lg-4 col-sm-5 col-xs-12">
									<div class="listRowInner">
										<img class=" hideMobile990" src="'.Product::DEFAULT_IMG.'" alt="'.$Item['product_code'].'">
										<span class="listTextStrong">'.$Item['product_code'].'</span>
										<span class="smallTitle hideMobile990"><b>('.$Item['product_category'].')</b></span>
									</div>
								</div>
								<div class="col-sm-2 col-xs-12">
									<div class="listRowInner">
										<span class="smallTitle">Marca</span>
										<span class="emailTextResp"><span class="label label-primary">'.$Item['product_brand'].'</span></span>
									</div>
								</div>
								<div class="col-sm-3 col-xs-12">
									<div class="listRowInner">
										<span class="smallTitle">Stock</span>
										<span class="listTextStrong"><span class="label bg-navy">'.$Item['product_stock'].'</span></span>
									</div>
								</div>
							</div>';
			}
		}
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
		$this->SearchFields['code'] = Core::InsertElement('text','code','','form-control txC','placeholder="C&oacute;digo Gen&eacute;rico"');
		$this->SearchFields['order_number'] = Core::InsertElement('text','order_number','','form-control inputMask txC','data-inputmask="\'mask\': \'9{+}\'" placeholder="N&uacute;mero de Orden"');
		// $this->SearchFields['product_id'] = Core::InsertElement('autocomplete','product_id','','form-control','placeholder="Art&iacute;culo" placeholderauto="C&oacute;digo no encontrado"','Product','SearchCodes');
		$this->SearchFields['product_code'] = Core::InsertElement('text','product_code','','form-control txC','placeholder="Art&iacute;culo"');
		$this->SearchFields['brand_id'] = Core::InsertElement('select','brand_id','','form-control chosenSelect','placeholder="Marca"',Core::Select(Brand::TABLE,Brand::TABLE_ID.",name","status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],"name"),'','Cualquier Marca');
		$this->SearchFields['category_id'] = Core::InsertElement('select',Category::TABLE_ID,'','form-control chosenSelect','',Core::Select(Category::TABLE,Category::TABLE_ID.',title',"status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],"title"),'','Cualquier L&iacute;nea');
	}
	
	protected function InsertSearchButtons()
	{
		return '<a href="new.php" class="hint--bottom hint--bounce hint--success" aria-label="Nuevo Art&iacute;culo Gen&eacute;rico"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-plus-square"></i></button></a>';
	}
	
	public function ConfigureSearchRequest()
	{
		if($_POST['order_number'])
		{
			$_POST['order_number_condition'] = ">=";
			$_POST['view_order_field'] = 'order_number';
		}	
		$this->SetSearchRequest();
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function Insert()
	{
		$CodeAbs		= $_POST['code'];
		$Category		= $_POST['category'];
		$RelationStatus	= $_POST['relation_status']==1?'F':'A';
		$ID = Core::Insert(self::TABLE,'code,'.Category::TABLE_ID.',relation_status,creation_date,organization_id,created_by',"'".$CodeAbs."',".$Category.",'".$RelationStatus."',NOW(),".$_SESSION[CoreOrganization::TABLE_ID].",".$_SESSION[CoreUser::TABLE_ID]);
		for($I=1;$I<=intval($_POST['codes']);$I++)
		{
			$Code = $_POST['code_'.$I];
			if($Code)
				$Codes .= $Codes? ",".$Code:$Code;
		}
		if($Codes)
		{
			Core::Update(Product::TABLE,self::TABLE_ID."=".$ID,Product::TABLE_ID." IN (".$Codes.")");
		}
		
	}	
	
	public function Update()
	{
		$ID 			= $_POST['id'];
		$CodeAbs		= $_POST['code'];
		$Category		= $_POST['category'];
		$RelationStatus	= $_POST['relation_status']==1?'F':'A';
		
		Core::Update(self::TABLE,"code='".$CodeAbs."',".Category::TABLE_ID."=".$Category.",relation_status='".$RelationStatus."',updated_by=".$_SESSION[CoreUser::TABLE_ID],self::TABLE_ID."=".$ID);
		//echo Core::LastQuery();
		
		Core::Update(Product::TABLE,self::TABLE_ID."=0",self::TABLE_ID."=".$ID);
		for($I=1;$I<=intval($_POST['codes']);$I++)
		{
			$Code = $_POST['code_'.$I];
			if($Code)
				$Codes .= $Codes? ",".$Code:$Code;
		}
		if($Codes)
			Core::Update(Product::TABLE,self::TABLE_ID."=".$ID,Product::TABLE_ID." IN (".$Codes.")");
		
		$Products = Core::Select(Product::TABLE,"*",self::TABLE_ID."=".$ID);
		foreach ($Products as $Product)
		{
			Core::Update(ProductRelation::TABLE,Product::TABLE_ID."=".$Product[Product::TABLE_ID],self::TABLE_ID."=".$ID." AND ".Brand::TABLE_ID."=".$Product[Brand::TABLE_ID]);
		}
		
	}
	
	public function Validate()
	{
		self::ValidateValue("code",$_POST['code'],$_POST['actualcode']);
	}
}
?>
