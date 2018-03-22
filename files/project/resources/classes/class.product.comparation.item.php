<?php

class ProductComparationItem
{
	use CoreSearchList,CoreCrud,CoreImage;
	
	const TABLE				= 'product_comparation_item';
	const TABLE_ID			= 'item_id';
	const GROUP_BY			= 'abstract_id';
	const SEARCH_TABLE		= 'view_product_comparation_list';
	const DEFAULT_IMG		= '../../../../skin/images/products/default/default2.png';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/products/default/';
	const IMG_DIR			= '../../../../skin/images/products/';
	const DEFAULT_FILE_DIR	= '../../../../skin/files/price_list/';

	public function __construct($ID=0)
	{
		
		$this->ID = $ID;
		if($ID>0)
		{
			$Data = Core::Select(self::SEARCH_TABLE,'*',self::TABLE_ID."=".$this->ID);
			$this->Data = $Data[0];
		}
		
		// $this->GetData();
		// self::SetImg($this->Data['img']);
		// $this->Data['items'] = $this->GetItems();
	}
	
	
	public function GetItems()
	{
		if($this->Data['comparation_id'] && $this->Data['abstract_id'])
		{
			$Items = Core::Select(self::SEARCH_TABLE,'*',"comparation_id=".$this->Data['comparation_id']." AND abstract_id=".$this->Data['abstract_id'],'position');
			$this->Data['items'] = $Items;
			
		}
		return $Items;
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
		// $HTML	.= 	'<a href="new.php?id='.$Object->ID.'" class="hint--bottom hint--bounce hint--info" aria-label="Editar"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
		
		// $HTML	.= '<a class="hint--bottom hint--bounce hint--success" aria-label="Guardar" process="'.PROCESS.'" id="abstract_'.$Object->ID.'"><button type="button" class="btn bg-green"><i class="fa fa-save"></i></button></a>';
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
		$Items = "";
		if($Object->Data['abstract_stock_diff']>0)
		{
			$AbstractStockClass = "label-danger";
		}else{
			$AbstractStockClass = "bg-olive";
			$Object->Data['abstract_stock_diff'] = $Object->Data['abstract_stock_diff']*(-1);
		}
		$Object->Data['abstract_stockmin'] = ($Object->Data['abstract_stock_diff'] - $Object->Data['abstract_stock']);
		$Object->Data['abstract_stockmin'] *= $Object->Data['abstract_stockmin']>0?1:-1;
		
		$Object->GetItems();
		foreach($Object->Data['items'] as $Item)
		{
			$RowBg = $RowBg=='bg-gray'?'bg-gray-active':'bg-gray';
				
			switch ($Item['position'])
			{
				case 1:
					$PosClass = "label-success";
				break;
				case 2:
					$PosClass = "bg-olive";
				break;
				case 3:
				case 4:
					$PosClass = "label-warning";
				break;
				
				default:
					$PosClass = "label-danger";
				break;
			}
			
			if($Item['actual_stock_diff']>0)
			{
				$ActualStockDiffClass = "label-danger";
				$Item['stockmin'] = $Item['actual_stock']-$Item['actual_stock_diff'];
			}else{
				$ActualStockDiffClass = "bg-olive";
				$Item['stockmin'] = $Item['actual_stock_diff'] - $Item['actual_stock'];
				$Item['actual_stock_diff'] *= -1;
			}
			$Item['stockmin'] *= $Item['stockmin']>0?1:-1;
			
			// if()
			// 	print_r($Item);
			
			
			$Item['stockmin'] = Core::Select(Product::TABLE,'stock_min',Product::TABLE_ID."=".$Item[Product::TABLE_ID])[0]['stock_min'];
			
			$Items .= '
			<div class="row '.$RowBg.'" style="padding:5px;">
					
							<div class="col-xs-1">
								<div class="listRowInner">
									<span class="label '.$PosClass.'">'.$Item['position'].'</span>
								</div>
							</div>
							<div class="col-xs-2">
								<div class="listRowInner">
									<span class="label label-primary">'.$Item['company'].'</span>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="listRowInner">
									<div class="form-inline">
										<div class="form-group">
											<span class="smallTitle"><span class="label label-brown">'.$Item['code'].'</span> -> <span class="label label-brown">'.$Item['product_code'].'</span></span>
										</div>
										<br>
										<div class="form-group">
											<span class="listTextStrong"><span class="label label-info">'.$Item['brand'].'</span></span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-1">
								<div class="listRowInner">
									<span class="label '.$PosClass.'">'.$Item['price'].'</span>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="listRowInner">
									<span class="label label-primary hint--bottom hint--bounce hint--info" aria-label="Stock del Proveedor">'.$Item['stock'].'</span>/<span class="label bg-teal-active hint--bottom hint--bounce hint--info" aria-label="Stock M&iacute;nimo">'.$Item['stockmin'].'</span>/<span class="label label-default hint--bottom hint--bounce" aria-label="Stock Actual (Dep&oacute;sito + Ordenes en Camino)">'.$Item['actual_stock'].'</span>/<span class="label '.$ActualStockDiffClass.' hint--bottom hint--bounce" aria-label="Stock Sobrante(verde) o Faltante(rojo)">'.$Item['actual_stock_diff'].'</span>
								</div>
							</div>
							<div class="col-xs-1">
								<div class="listRowInner">
									'.Core::InsertElement('text','item_'.$Item['item_id'],$Item['order_quantity'],'form-control ItemStock txC','placeholder="0" item="'.$Item['item_id'].'" validateOnlyNumbers="Ingrese &uacute;nicamente n&uacute;meros."').'
								</div>
							</div>
							
						</div>
			';
		}
		
		$HTML = '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					<div class="listRowInner">
						<span class="smallTitle text-muted">C&oacute;digo Gen&eacute;rico</span>
						<span class="listTextStrong"><span class="label bg-purple">'.$Object->Data['abstract_code'].'</span></span>
					</div>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1">
					<div class="listRowInner">
						<span class="smallTitle">Stock</span>
						<span class="listTextStrong"><span class="label label-default">'.$Object->Data['abstract_stock'].'</span></span>
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2">
					<div class="listRowInner">
						<span class="smallTitle">Stock M&iacute;nimo</span>
						<span class="listTextStrong"><span class="label bg-teal-active">'.$Object->Data['abstract_stockmin'].'</span></span>
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2">
					<div class="listRowInner">
						<span class="listTextStrong">Balance Stock</span>
						<span class="smallTitle"><span class="label '.$AbstractStockClass.'">'.$Object->Data['abstract_stock_diff'].'</span></span>
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
					<div class="listRowInner">
						<span class="listTextStrong"></span>
						<span class="smallTitle"></span>
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2">
					<div class="listRowInner">
						<span class="listTextStrong"></span>
						<span class="smallTitle"></span>
					</div>
				</div>
				<div class="animated DetailedInformation col-xs-12">
					<div class="list-margin-top row" style="background-color:#EEE;padding-top:5px;">
						<div class="col-xs-1"><span class="listTextStrong">Posici&oacute;n</span></div>
						<div class="col-xs-2">Proveedor</div>
						<div class="col-xs-3">C&oacute;d. Prov -> Roller<br>Marca</div>
						<div class="col-xs-1">Precio</div>
						<div class="col-xs-3">Stock</div>
						<div class="col-xs-1">Ordenar</div>
					</div>
				</div>
				<div class="animated DetailedInformation col-xs-12">
					<div class="">
						'.$Items.'
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
		if($_GET['comparation'])
		{
			$this->HiddenSearchFields['comparation_id'] = $_GET['comparation'];
		}
		$this->SearchFields['product_code'] = Core::InsertElement('text','product_code','','form-control','placeholder="C&oacute;digo Roller"');
		$this->SearchFields['abstract_code'] = Core::InsertElement('text','abstract_code','','form-control','placeholder="C&oacute;digo Gen&eacuterico"');
		$this->SearchFields['code'] = Core::InsertElement('text','code','','form-control','placeholder="C&oacute;digo Empresa"');
		$this->NoOrderSearchFields['code']=true;
		$this->NoOrderSearchFields['product_code']=true;
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
		
		if($_POST['comparation'])
		{
			$this->AddWhereString(" AND comparation_id =".$_POST['comparation_id']);
		}
		
		
		// if($_POST['view_order_field']=="price_from" || $_POST['view_order_field']=="price_to")
		// 	$_POST['view_order_field'] = "price";
		
		// if($_POST['view_order_field']=="stock_from" || $_POST['view_order_field']=="stock_to")
		$_POST['view_order_field'] = "abstract_code";
			
		$this->SetSearchRequest();
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function Updateitemstock()
	{
		$ID = $_POST['item'];
		$Stock = $_POST['stock'];
		Core::Update(self::TABLE,'order_quantity='.$Stock,self::TABLE_ID."=".$ID);
	}
	
	// public function Compare()
	// {
	// 	$Companies	= $_POST['companies'];
	// 	$Brands 	= $_POST['brands'];
	// 	$StockMin	= $_POST['stockmin']?"Y":"N";
		
	// 	// If companies were selected a filter is created to include only those companies in the query
	// 	if($Companies && $Companies!="null")
	// 		$CompaniesFilter = " AND company_id IN (".$Companies.")";
	// 	// If brands were selected a filter is created to include only those brands in the query
	// 	if($Brands && $Brands!="null")
	// 		$BrandsFilter = " AND a.brand_id IN (".$Brands.")";
	// 	// If stock min comparation is setted a filter is created
	// 	if($StockMin=="Y")
	// 		$StockMinFilter = " AND b.stock_min>0 AND b.stock_total<b.stock_min";
		
	// 	$Products = Core::Select(ProductRelation::SEARCH_TABLE." a LEFT JOIN ".Product::SEARCH_TABLE." b ON (b.abstract_id=a.abstract_id AND b.brand_id=a.brand_id)","a.*,b.product_id AS actual_product_id,(b.stock_min-b.stock_total) AS actual_stock_diff,b.stock AS actual_stock","a.abstract_id>0 AND b.status='A'".$CompaniesFilter.$BrandsFilter,"a.abstract_id,a.price","a.relation_id");
		
	// 	// DO NOT FORGET TO CALCULATE DOLLAR EXCHANGE RATE FOR EACH COMPANY PRICE LIST THAT DOESN'T HAVE DOLLAR AS DEFINED CURRENCY
		
		
	// 	if($Products)
	// 		$ComparationID = Core::Insert(self::TABLE,"stock_min,status,creation_date,created_by,".CoreOrganization::TABLE_ID,"'".$StockMin."','A',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID]);
	// 	if($ComparationID)
	// 	{
	// 		$AbstractID = 0;
	// 		foreach($Products as $Key=>$Product)
	// 		{
	// 			$Position++;
	// 			if($Product['abstract_id']!=$AbstractID)
	// 			{
	// 				$AbstractStock = Core::Select(Product::SEARCH_TABLE,'SUM(stock_total) as abstract_stock,SUM(stock_min) - SUM(stock_total) AS abstract_stock_diff',"status='A' AND ".ProductAbstract::TABLE_ID."=".$AbstractID)[0];
	// 				$AbstractID = $Product['abstract_id'];
	// 				$Position = 1;
	// 			}
	// 			$Product['position'] = $Position;
	// 			$Product['product_id'] = $Product['actual_product_id'];
	// 			$Product['abstract_stock'] = $AbstractStock['abstract_stock'];
	// 			$Product['abstract_stock_diff'] = $AbstractStock['abstract_stock_diff'];
	// 			$Product['dollar_exchange_rate'] = 1; // DO NOT FORGET TO CALCULATE DOLLAR EXCHANGE RATE FOR EACH COMPANY PRICE LIST THAT DOESN'T HAVE DOLLAR AS DEFINED CURRENCY
				
	// 			$Field =	$ComparationID.",".$Product['relation_id'].",".$Product['company_id'].",".$Product['product_id'].",".
	// 						$Product['abstract_id'].",".$Product['brand_id'].",".$Product['position'].",'A',".$Product['price'].",".
	// 						$Product['stock'].",".$Product['currency_id'].",".$Product['dollar_exchange_rate'].",".$Product['actual_stock'].",".
	// 						$Product['actual_stock_diff'].",".$Product['abstract_stock'].",".
	// 						$Product['abstract_stock_diff'].",'".$Product['list_date']."',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".
	// 						$_SESSION[CoreOrganization::TABLE_ID];
							
	// 			$Fields .= $Fields? "),(".$Field:$Field;
	// 		}
	// 		Core::Insert('product_comparation_item',"
	// 							comparation_id,
	// 							relation_id,
	// 							company_id,
	// 							product_id,
	// 							abstract_id,
	// 							brand_id,
	// 							position,
	// 							status,
	// 							price,
	// 							stock,
	// 							currency_id,
	// 							dollar_exchange_rate,
	// 							actual_stock,
	// 							actual_stock_diff,
	// 							abstract_stock,
	// 							abstract_stock_diff,
	// 							list_date,
	// 							creation_date,
	// 							created_by,
	// 							organization_id
	// 							",$Fields);
	// 	}
		
	// 	// echo Core::LastQuery();
	// 	// print_r($Products);
	// }
}
?>