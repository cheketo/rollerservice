<?php

class ProductComparation
{
	use CoreSearchList,CoreCrud,CoreImage;
	
	const TABLE				= 'product_comparation';
	const TABLE_ID			= 'comparation_id';
	const SEARCH_TABLE		= 'view_product_comparation_list';
	const DEFAULT_IMG		= '../../../../skin/images/products/default/default2.png';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/products/default/';
	const IMG_DIR			= '../../../../skin/images/products/';
	const DEFAULT_FILE_DIR	= '../../../../skin/files/price_list/';

	public function __construct($ID=0)
	{
		$this->ID = $ID;
		$this->GetData();
		self::SetImg($this->Data['img']);
		$this->Data['brands'] = Core::Select(self::SEARCH_TABLE,"DISTINCT brand",self::TABLE_ID."=".$this->ID);
		$this->Data['companies'] = Core::Select(self::SEARCH_TABLE,"DISTINCT company",self::TABLE_ID."=".$this->ID);
	}
	
	// public static function GetLastImport($CompanyID)
	// {
	// 	$Data = Core::Select('product_relation_import',"*","status = 'A' AND company_id =".$CompanyID,"creation_date DESC")[0];
	// 	if(!empty($Data))
	// 		$Data['items'] = self::GetImportedProducts($Data['import_id']);
	// 	else
	// 		$Data = false;
	// 	return $Data;
	// }
	
	// public static function GetImportedProducts($ImportID)
	// {
	// 	return Core::Select('product_relation_import_item',"*","import_id=".$ImportID);
	// }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// SEARCHLIST FUNCTIONS ///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	protected static function MakeActionButtonsHTML($Object,$Mode='list')
	{
		// if($Mode!='grid') $HTML .=	'<a class="hint--bottom hint--bounce showMobile990" aria-label="M&aacute;s informaci&oacute;n"><button type="button" class="btn bg-navy ExpandButton" id="expand_'.$Object->ID.'"><i class="fa fa-plus"></i></button></a> ';
		$HTML	.= 	'<a href="edit.php?comparation='.$Object->ID.'" class="hint--bottom hint--bounce hint--info" aria-label="Analizar"><button type="button" class="btn btnBlue"><i class="fa fa-eye"></i></button></a>';
		$HTML	.= '<a class="hint--bottom hint--bounce hint--success" aria-label="Generar Ordenes" process="'.PROCESS.'" id="generate_'.$Object->ID.'"><button type="button" class="btn btn-success"><i class="fa fa-cubes"></i></button></a>';
		
		// $HTML	.= '<a class="deleteElement hint--bottom hint--bounce hint--error" aria-label="Eliminar" process="'.PROCESS.'" id="delete_'.$Object->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
		// $HTML	.= Core::InsertElement('hidden','delete_question_'.$Object->ID,'&iquest;Desea eliminar la relaci&oacute;n <b>'.$Object->Data['code'].'</b> ?');
		// $HTML	.= Core::InsertElement('hidden','delete_text_ok_'.$Object->ID,'La relaci&oacute;n <b>'.$Object->Data['code'].'</b> ha sido eliminada.');
		// $HTML	.= Core::InsertElement('hidden','delete_text_error_'.$Object->ID,'Hubo un error al intentar eliminar el art&iacute;culo <b>'.$Object->Data['code'].'</b>.');
			
		
		return $HTML;
	}
	
	protected static function MakeListHTML($Object)
	{
		// $Abstract = $Object->Data['abstract_code']?'<span class="label label-info">'.$Object->Data['abstract_code'].'</span>':'Sin c&oacute;digo asociado';
		// $Roller = $Object->Data['product_code']?'<span class="label label-warning">'.$Object->Data['product_code'].'</span>':'Sin c&oacute;digo asociado';
		// $Price = $Object->Data['price']>0?'<span class="badge bg-gray text-green"><b>'.$Object->Data['currency_prefix'].' '.$Object->Data['price'].'</b></span>':'Sin especificar';
		// $Stock = $Object->Data['stock']>0?'<span class="badge bg-gray text-blue"><b>'.$Object->Data['stock'].'</b></span>':'Sin especificar';
		foreach ($Object->Data['companies'] as $Company)
		{
			$Companies .= '<span class="label label-primary">'.$Company['company'].'</span><br>';
		}
		
		foreach ($Object->Data['brands'] as $Brand)
		{
			$Brands .= '<span class="label bg-purple">'.$Brand['brand'].'</span><br>';
		}
		
		$StockMin = $Object->Data['stock_min']=='Y'?'Si':'No';
		
		
		$HTML = '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					<div class="listRowInner">
						<span class="smallTitle">Fecha</span>
						<span class="listTextStrong text-muted" style="max-width:100%;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;display: inline-block;">'.Core::DateTimeFormat($Object->Data['creation_date'],'complete').'</span>
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 hideMobile990">
					<div class="listRowInner">
						<span class="smallTitle">Empresas</span>
						'.$Companies.'
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 hideMobile990">
					<div class="listRowInner">
						<span class="smallTitle">Marcas</span>
						'.$Brands.'
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
					<div class="listRowInner">
						<span class="smallTitle">Solo Stock Min.</span>
						<span class="listTextStrong">'.$StockMin.'</span>
					</div>
				</div>
				
				<div class="animated DetailedInformation col-xs-12">
					<div class="list-margin-top">
						
					</div>
				</div>
				';
		return $HTML;
	}
	
	protected static function MakeItemsListHTML($Object)
	{
		// $HTML .= '
		// 		<div class="row bg-gray" style="padding:5px;">
					
		// 			<div class="col-xs-6 showMobile990">
		// 				<div class="listRowInner">
		// 					<span class="smallDetails"><b>Marca</b></span>
		// 					<span class="label label-primary">'.$Object->Data['brand'].'</span>
		// 				</div>
		// 			</div>
		// 			<div class="col-xs-6 showMobile990">
		// 				<div class="listRowInner">
		// 					<span class="smallDetails"><b>Categor&iacute;a</b></span>
		// 					<span class="label label-primary">'.$Object->Data['category'].'</span>
		// 				</div>
		// 			</div>
					
		// 		</div>';
		return $HTML;
	}
	
	public static function MakeNoRegsHTML()
	{
		return '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron art&iacute;culos para comparar.</h4><p>Puede realizar una nueva comparaci&oacute;n de art&iacute;culo haciendo click <a href="compare_price_list.php">aqui</a>.</p></div>';	
	}
	
	protected function SetSearchFields()
	{
		$this->SearchFields['product_code'] = Core::InsertElement('text','product_code','','form-control','placeholder="C&oacute;digo Roller"');
		$this->SearchFields['abstract_code'] = Core::InsertElement('text','abstract_code','','form-control','placeholder="C&oacute;digo Gen&eacuterico"');
		$this->SearchFields['code'] = Core::InsertElement('text','code','','form-control','placeholder="C&oacute;digo Empresa"');
		$this->NoOrderSearchFields['code']=true;
		$this->NoOrderSearchFields['product_code']=true;
		
		// $this->SearchFields['product_id'] = Core::InsertElement('select',Product::TABLE_ID,'','form-control chosenSelect','',Product::GetFullCodes(),'','Cualquier Art&iacute;culo');
		// $this->SearchFields['abstract_id'] = Core::InsertElement('autocomplete','abstract_id','','form-control','placeholder="C&oacute;digo Gen&eacute;rico" placeholderauto="C&oacute;digo no encontrado"','ProductAbstract','SearchAbstractCodes');
		// $this->SearchFields['brand_id'] = Core::InsertElement('select',Brand::TABLE_ID,'','form-control chosenSelect','',Core::Select(Brand::TABLE,Brand::TABLE_ID.',name',"status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],"name"),'','Cualquier Marca');
		// $this->SearchFields['category_id'] = Core::InsertElement('select',Category::TABLE_ID,'','form-control chosenSelect','',Core::Select(Category::TABLE,Category::TABLE_ID.',title',"status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],"title"),'','Cualquier L&iacute;nea');
		// $this->SearchFields['company_id'] = Core::InsertElement('autocomplete','company_id',$_GET['company_id'].",".$_GET['element'],'form-control','placeholder="Empresa" placeholderauto="Empresa no encontrada"','Company','SearchCompanies');
		// $this->SearchFields['price_from'] = Core::InsertElement('text','price_from','','form-control','placeholder="Precio Desde" validateOnlyNumbers="Ingrese &uacute;nicamente n&uacute;meros"');
		// $this->SearchFields['price_to'] = Core::InsertElement('text','price_to','','form-control','placeholder="Precio Hasta" validateOnlyNumbers="Ingrese &uacute;nicamente n&uacute;meros"');
		// $this->SearchFields['stock_from'] = Core::InsertElement('text','stock_from','','form-control','placeholder="Stock Desde" validateOnlyNumbers="Ingrese &uacute;nicamente n&uacute;meros"');
		// $this->SearchFields['stock_to'] = Core::InsertElement('text','stock_to','','form-control','placeholder="Stock Hasta" validateOnlyNumbers="Ingrese &uacute;nicamente n&uacute;meros"');
		// $this->HiddenSearchFields['removeget'] = 1;
	}
	
	protected function InsertSearchButtons()
	{
		return '<a href="new.php" class="hint--bottom hint--bounce hint--success" aria-label="Nueva Comparaci&oacute;n"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-plus-square"></i></button></a>';
	}
	
	public function ConfigureSearchRequest()
	{
		
		// if($_GET['abstract_id'])
		// {
		// 	$_POST['abstract_id_condition']="=";
		// }
		
		if($_GET['id'])
		{
			$this->AddWhereString(" AND ".self::TABLE_ID."=".$_GET['id']);
		}
		
		
		// if($_POST['view_order_field']=="price_from" || $_POST['view_order_field']=="price_to")
		// 	$_POST['view_order_field'] = "price";
		
		// if($_POST['view_order_field']=="stock_from" || $_POST['view_order_field']=="stock_to")
		$_POST['view_order_field'] = "creation_date";
		$_POST['view_order_mode'] = 'DESC';
			
		$this->SetSearchRequest();
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function Compare()
	{
		$Companies	= $_POST['companies'];
		$Brands 	= $_POST['brands'];
		$StockMin	= $_POST['stockmin']?"Y":"N";
		
		// If companies were selected a filter is created to include only those companies in the query
		if($Companies && $Companies!="null")
			$CompaniesFilter = " AND company_id IN (".$Companies.")";
		// If brands were selected a filter is created to include only those brands in the query
		if($Brands && $Brands!="null")
			$BrandsFilter = " AND a.brand_id IN (".$Brands.")";
		// If stock min comparation is setted a filter is created
		if($StockMin=="Y")
			$StockMinFilter = " AND b.stock_min>0 AND b.stock_total<b.stock_min";
		
		$Products = Core::Select(ProductRelation::SEARCH_TABLE." a LEFT JOIN ".Product::SEARCH_TABLE." b ON (b.abstract_id>0 AND b.abstract_id=a.abstract_id AND b.brand_id=a.brand_id)","a.*,b.product_id AS actual_product_id,(b.stock_min-b.stock_total) AS actual_stock_diff,b.stock_total AS actual_stock","a.status='A' AND a.abstract_id>0 AND a.price>0 AND b.status='A'".$CompaniesFilter.$BrandsFilter,"a.abstract_id,a.price","a.relation_id");
		
		// DO NOT FORGET TO CALCULATE DOLLAR EXCHANGE RATE FOR EACH COMPANY PRICE LIST THAT DOESN'T HAVE DOLLAR AS DEFINED CURRENCY
		
		
		if($Products)
		{
			$ComparationID = Core::Insert(self::TABLE,"stock_min,status,creation_date,created_by,".CoreOrganization::TABLE_ID,"'".$StockMin."','A',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID]);
		}
		if($ComparationID)
		{
			$AbstractID = 0;
			foreach($Products as $Key => $Product)
			{
				$Currency = Core::Select(Currency::TABLE,"*",Currency::TABLE_ID."=".$Product['currency_id'])[0];
				$Position++;
				if($Product['abstract_id']!=$AbstractID)
				{
					$BrandsIDs = array();
					foreach($Products as $Prod)
					{
						if($Prod['abstract_id']==$Product['abstract_id'] && !in_array($Prod['brand_id'],$BrandsIDs))
						{
							array_push($BrandsIDs, $Prod['brand_id']);
						}
					}
					$Brands = implode(',',$BrandsIDs);
					$BrandsFilter = " AND brand_id IN (".$Brands.")";
					
					
					$AbstractStock = Core::Select(Product::SEARCH_TABLE,'SUM(stock_total) as abstract_stock,SUM(stock_min) - SUM(stock_total) AS abstract_stock_diff',"status='A' AND ".ProductAbstract::TABLE_ID."=".$Product['abstract_id'].$BrandsFilter)[0];
					// echo Core::LastQuery();
					$AbstractID = $Product['abstract_id'];
					$Position = 1;
				}
				if($Products[$Key-1]['abstract_id']==$Product['abstract_id'] || $Products[$Key+1]['abstract_id']==$Product['abstract_id'])
				{
					$Product['single_comparation'] = '0';
				}else{
					$Product['single_comparation'] = '1';
				}
				$Product['position'] = $Position;
				$Product['product_id'] = $Product['actual_product_id'];
				$Product['abstract_stock'] = $AbstractStock['abstract_stock'];
				$Product['abstract_stock_diff'] = $AbstractStock['abstract_stock_diff'];
				$Product['dollar_exchange_rate'] = $Currency['dollar_exchange']; // DO NOT FORGET TO CALCULATE DOLLAR EXCHANGE RATE FOR EACH COMPANY PRICE LIST THAT DOESN'T HAVE DOLLAR AS DEFINED CURRENCY
				
				$Field =	$ComparationID.",".$Product['relation_id'].",".$Product['company_id'].",".$Product['product_id'].",".
							$Product['abstract_id'].",".$Product['brand_id'].",".$Product['position'].",'A',".$Product['price'].",".
							$Product['stock'].",".$Product['currency_id'].",".$Product['dollar_exchange_rate'].",".$Product['actual_stock'].",".
							$Product['actual_stock_diff'].",".$Product['abstract_stock'].",".
							$Product['abstract_stock_diff'].",'".$Product['list_date']."',".$Product['single_comparation'].",NOW(),".$_SESSION[CoreUser::TABLE_ID].",".
							$_SESSION[CoreOrganization::TABLE_ID];
							
				$Fields .= $Fields? "),(".$Field:$Field;
			}
			if($ComparationID)
			{
			Core::Insert('product_comparation_item',"
								comparation_id,
								relation_id,
								company_id,
								product_id,
								abstract_id,
								brand_id,
								position,
								status,
								price,
								stock,
								currency_id,
								dollar_exchange_rate,
								actual_stock,
								actual_stock_diff,
								abstract_stock,
								abstract_stock_diff,
								list_date,
								single_comparation,
								creation_date,
								created_by,
								organization_id
								",$Fields);
				echo $ComparationID;
			}
		}
		
		// echo Core::LastQuery();
		// print_r($Products);
	}
}
?>