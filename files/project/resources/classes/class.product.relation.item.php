<?php

class ProductRelationItem
{
	use CoreSearchList,CoreCrud,CoreImage;
	
	const TABLE				= 'product_relation_import_item';
	const TABLE_ID			= 'item_id';
	const SEARCH_TABLE		= 'view_product_relation_import_list';
	const DEFAULT_IMG		= '../../../../skin/images/products/default/default.jpg';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/products/default/';
	const IMG_DIR			= '../../../../skin/images/products/';
	const DEFAULT_FILE_DIR	= '../../../../skin/files/price_list/';

	var $ImportID;
	
	public function __construct($ID=0)
	{
		$this->ID = $ID;
		$this->GetData();
		self::SetImg($this->Data['img']);
	}
	
	public function SetImportID($ID)
	{
		$this->ImportID = $ID;
	}
	
	public static function GetLastImport($CompanyID)
	{
		$Data = Core::Select('product_relation_import',"*","status = 'A' AND company_id =".$CompanyID,"creation_date DESC")[0];
		if(!empty($Data))
			$Data['items'] = self::GetImportedProducts($Data['import_id']);
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
		//$HTML	.= 	'<a href="new.relation.php?id='.$Object->ID.'" class="hint--bottom hint--bounce hint--info" aria-label="Editar"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
		
		if($Object->Data['item_status']=='A')
		{
			$HTML	.= 	'<a id="save_'.$Object->ID.'" item="'.$Object->ID.'" class="hint--bottom hint--bounce hint--success SaveRowChanges" aria-label="Guardar Cambios"><button type="button" class="btn btnGreen"><i class="fa fa-floppy-o"></i></button></a>';
			//$HTML	.= 	'<a id="reload_'.$Object->ID.'" item="'.$Object->ID.'" class="hint--bottom hint--bounce ReloadRowData" aria-label="Recargar Datos"><button type="button" class="btn btn-github"><i class="fa fa-repeat"></i></button></a>';
			
			$HTML	.= '<a class="deleteElement hint--bottom hint--bounce hint--error" aria-label="Descartar" process="'.PROCESS.'" id="delete_'.$Object->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','delete_question_'.$Object->ID,'&iquest;Desea descartar el c&oacute;digo <b>'.$Object->Data['code'].'</b> de la importaci&oacute;n?');
			$HTML	.= Core::InsertElement('hidden','delete_text_ok_'.$Object->ID,'El c&oacute;digo <b>'.$Object->Data['code'].'</b> ha sido descartado.');
			$HTML	.= Core::InsertElement('hidden','delete_text_error_'.$Object->ID,'Hubo un error al intentar descartar el c&oacute;digo <b>'.$Object->Data['code'].'</b>.');
		}else{
			$HTML	.= '<a class="activateElement hint--bottom hint--bounce hint--success" aria-label="Recuperar" process="'.PROCESS.'" id="activate_'.$Object->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-external-link"></i></button></a>';
			$HTML	.= Core::InsertElement('hidden','activate_question_'.$Object->ID,'&iquest;Desea recuperar el c&oacute;digo <b>'.$Object->Data['code'].'</b> y agregarlo a la importaci&oacute;n?');
			$HTML	.= Core::InsertElement('hidden','activate_text_ok_'.$Object->ID,'El c&oacute;digo <b>'.$Object->Data['code'].'</b> ha sido recuperado.');
			$HTML	.= Core::InsertElement('hidden','activate_text_error_'.$Object->ID,'Hubo un error al intentar recuperar el c&oacute;digo <b>'.$Object->Data['code'].'</b>.');
		}
			
		
		return $HTML;
	}
	
	protected static function MakeListHTML($Object)
	{
		if(!$Object->SelectBrand)
			$Object->SelectBrand = Core::Select(Brand::TABLE,Brand::TABLE_ID.",name","status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],'name');
		if($Object->Data['abstract_id'])
			$Abstract = $Object->Data['abstract_id'].','.$Object->Data['abstract_code'];
		else
			$Abstract = '';
		
		// $Object->Data['brand'] =$Object->Data['brand']?$Object->Data['brand']:'<label label-danger></label>';
		
		$HTML = '<form id="form_'.$Object->Data['item_id'].'">
				<div class="col-sm-4 col-xs-12">
					<div class="listRowInner">
						<span class="smallDetails">C&oacute;digo</span>
						'.Core::InsertElement('text','code_'.$Object->Data['item_id'],$Object->Data['code'],'form-control txC','validateEmpty="Ingrese un c&oacute;digo"').'
					</div>
				</div>
				<div class="col-sm-5 col-xs-12">
					<div class="listRowInner">
						<span class="smallDetails">Marca</span>
						'.Core::InsertElement('select','brand_'.$Object->Data['item_id'],$Object->Data['item_brand_id'],'txC form-control chosenSelect','validateEmpty="Seleccione una marca." data-placeholder="Seleccionar Marca" ',$Object->SelectBrand,' ','').'
					</div>
				</div>
				<div class="col-sm-3 col-xs-12" style="height:70px;">
					<div class="listRowInner">
						&nbsp;
					</div>
				</div>
				<div class="col-sm-2 col-xs-12">
					<div class="listRowInner">
						<span class="smallDetails">Precio</span>
						'.Core::InsertElement('text','price_'.$Object->Data['item_id'],$Object->Data['price'],'txC form-controls inputMask','placeholder="Sin Precio" style="max-width:100px;" data-inputmask="\'alias\': \'numeric\', \'groupSeparator\': \'\', \'autoGroup\': true, \'digits\': 2, \'digitsOptional\': true, \'prefix\': \'\', \'placeholder\': \'0\'"').'
					</div>
				</div>
				<div class="col-sm-2 col-xs-12">
					<div class="listRowInner">
						<span class="smallDetails">Stock</span>
						'.Core::InsertElement('text','stock_'.$Object->Data['item_id'],$Object->Data['stock'],'txC form-controls','placeholder="Sin Stock" style="max-width:100px;"').'
					</div>
				</div>
				<div class="col-sm-5 col-xs-12">
					<div class="listRowInner">
						<span class="smallDetails">C&oacute;digo Gen&eacute;rico</span>
						'.Core::InsertElement('autocomplete','abstract_'.$Object->Data['item_id'],$Abstract,'txC form-control','placeholder="Seleccionar C&oacute;digo" placeholderauto="C&oacute;digo no encontrada"','ProductAbstract','SearchAbstractCodes').'
					</div>
				</div>
				</form>
				
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
		                <p>'.$Object->Data['brand'].' - <b>'.$Object->Data['code'].'</b></p>
		                <p>('.$Object->Data['abstract_code'].')</p>
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
		// $this->SearchFields['local_code'] = Core::InsertElement('text','local_code','','form-control','placeholder="C&oacute;digo en Roller"');
		// $this->SearchFields['product_id'] = Core::InsertElement('autocomplete',Product::TABLE_ID,'','form-control txC','placeholder="" placeholderauto="Empresa no encontrada" iconauto="building"','Product','GetFullCodes');
		$this->SearchFields['data'] = Core::InsertElement('select','data','','form-control chosenSelect','',array('1'=>"Marca Completa",'2'=>"Marca Incompleta"),'','Marca Completa o Incompleta');
		$this->NoOrderSearchFields['data']=true;
		$this->SearchFields['item_status'] = Core::InsertElement('select','item_status','A','form-control chosenSelect','',array('A'=>"C&oacute;digos a Importar",'I'=>"C&oacute;digos Descartados"));
		$this->NoOrderSearchFields['item_status']=true;
		$this->SearchFields['item_brand_id'] = Core::InsertElement('select','item_'.Brand::TABLE_ID,'','form-control chosenSelect','',Core::Select(Brand::TABLE,Brand::TABLE_ID.',name',"status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],"name"),'','Cualquier Marca');
		// $this->SearchFields['category_id'] = Core::InsertElement('select',Category::TABLE_ID,'','form-control chosenSelect','',Core::Select(Category::TABLE,Category::TABLE_ID.',title',"status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],"title"),'','Cualquier L&iacute;nea');
		$this->SearchFields['price_from'] = Core::InsertElement('text','price_from','','form-control txC','placeholder="Precio Desde" validateOnlyNumbers="Ingrese &uacute;nicamente n&uacute;meros"');
		$this->SearchFields['price_to'] = Core::InsertElement('text','price_to','','form-control txC','placeholder="Precio Hasta" validateOnlyNumbers="Ingrese &uacute;nicamente n&uacute;meros"');
		$this->SearchFields['stock_from'] = Core::InsertElement('text','stock_from','','form-control txC','placeholder="Stock Desde" validateOnlyNumbers="Ingrese &uacute;nicamente n&uacute;meros"');
		$this->SearchFields['stock_to'] = Core::InsertElement('text','stock_to','','form-control txC','placeholder="Stock Hasta" validateOnlyNumbers="Ingrese &uacute;nicamente n&uacute;meros"');
		$this->HiddenSearchFields['import_id'] = $this->ImportID;
		$this->HiddenSearchFields['import_id_condition'] = "=";
		$this->HiddenSearchFields['company_id_condition'] = "=";
		
	}
	
	public function InsertDefaultSearchButtons()
	{
		return '<div style="width:1px;height:35px;"></div>';
	}
	
	protected function InsertSearchButtons()
	{
		return '';
	}
	
	public function ConfigureSearchRequest()
	{
		//$_POST['import_id_condition'] = ;
		
		if($_POST['data'])
		{
			if($_POST['data']==1)
			{
				$this->AddWhereString(" AND item_brand_id>0");
			}else{
				$this->AddWhereString(" AND item_brand_id=0 ");
			}
		}
		if(!$_POST['item_status'])
		{
			if($_GET['item_status'])
			{
				$_POST['item_status'] = $_GET['item_status'];
			}else{
				$_POST['item_status'] = 'A';
			}
		}
		$_POST['item_status_condition']='=';
		
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
			$Stock=Core::FromMoneyToDB($_POST['stock_from']);
			$this->AddWhereString(" AND stock>=".$Stock);
		}
		if($_POST['stock_to'])
		{
			$Stock=Core::FromMoneyToDB($_POST['stock_to']);
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
	
	// public function Relation()
	// {
	// 	$Code		= $_POST['code'];
	// 	$ProductID	= $_POST[Product::TABLE_ID];
	// 	$RelationID	= $_POST['relation'];
		
	// 	if(!$RelationID)
	// 	{
	// 		Core::Insert(self::TABLE,'code,'.Product::TABLE_ID.',creation_date,'.CoreOrganization::TABLE_ID.',created_by',"'".$Code."',".$ProductID.",NOW(),".$_SESSION[CoreOrganization::TABLE_ID].",".$_SESSION[CoreUser::TABLE_ID]);		
	// 	}else{
	// 		Core::Update(self::TABLE,"code='".$Code."',".Product::TABLE_ID."=".$ProductID,self::TABLE_ID."=".$RelationID);
	// 	}
		
	// 	// echo $this->LastQuery();
	// }
	
	// public function Delete()
	// {
	// 	$ID = $_POST['id'];
	// 	Core::Delete(self::TABLE,self::TABLE_ID."=".$ID);
	// }
	
	// public function Update()
	// {
	// 	$ID 		= $_POST['id'];
	// 	$Edit		= new Product($ID);
		
	// 	$Code		= $_POST['code'];
	// 	$Category	= $_POST['category'];
	// 	$Price		= str_replace('$','',$_POST['price']);
	// 	$Brand		= $_POST['brand'];
	// 	$Rack		= $_POST['rack'];
	// 	$Size		= $_POST['size'];
	// 	$StockMin	= $_POST['stock_min'];
	// 	$StockMax	= $_POST['stock_max'];
	// 	$Description= $_POST['description'];
	// 	if(!$StockMin) $StockMin = 0;
	// 	if(!$StockMax) $StockMax = 0;
	// 	Core::Update(self::TABLE,"code='".$Code."',".Category::TABLE_ID."=".$Category.",".Brand::TABLE_ID."=".$Brand.",price=".$Price.",rack='".$Rack."',size='".$Size."',stock_min='".$StockMin."',stock_max='".$StockMax."',description='".$Description."',updated_by=".$_SESSION[CoreUser::TABLE_ID],self::TABLE_ID."=".$ID);
	// 	//echo $this->LastQuery();
	// }
	
	// public function Consult()
	// {
		// $ID = $_POST['id'];
		// $Data = Core::Select(Product::SEARCH_TABLE,'brand,category',Product::TABLE_ID."=".$ID)[0];
		// echo 'L&iacute;nea: <span class="label label-warning">'.$Data['category'].'</span><br>Precio: <span class="label label-primary">$ '.number_format($Data['price'],'.','',2).'</span>';
	// }
	
	// public function Checkrelation()
	// {
	// 	$ID = $_POST['id'];
	// 	$Data = Core::Select(self::TABLE,self::TABLE_ID.",code",Product::TABLE_ID."=".$ID)[0];
	// 	echo $Data['code']."///".$Data[self::TABLE_ID];
	// }
	
	public function Checkimport()
	{
		$ImportID = Core::Select('product_relation_import','*',"status='A' AND ".Company::TABLE_ID."=".$_POST['id'],"creation_date DESC")[0]['import_id'];
		if($ImportID)
			echo $ImportID;
	}
	
	public function Checkimportdate()
	{
		$ImportID = Core::Select('product_relation_import','*',"status='F' AND list_date>'".Core::FromDateToDB($_POST['date'])."' AND ".Company::TABLE_ID."=".$_POST['id'],"creation_date DESC")[0]['import_id'];
		if($ImportID)
			echo $ImportID;
	}
	
	public function Updateimportitem()
	{
		$ID = $_REQUEST['itemid'];
		$Code = $_POST['code_'.$ID];
		$BrandID = $_POST['brand_'.$ID];
		$Price = $_POST['price_'.$ID]?$_POST['price_'.$ID]:0;
		$Stock = $_POST['stock_'.$ID]?$_POST['stock_'.$ID]:0;
		$Abstract = $_POST['abstract_'.$ID]?$_POST['abstract_'.$ID]:0;
		if($Abstract && $BrandID)
			$ProductID = Core::Select(Product::TABLE,Product::TABLE_ID,ProductAbstract::TABLE_ID."=".$Abstract." AND ".Brand::TABLE_ID."=".$BrandID)[0][Product::TABLE_ID];	
		$ProductID=$ProductID?$ProductID:0;
		if($ID)
		{
			Core::Update(self::TABLE,"code='".$Code."',".Brand::TABLE_ID."=".$BrandID.",price=".$Price.",stock=".$Stock.",".ProductAbstract::TABLE_ID."=".$Abstract.",".Product::TABLE_ID."=".$ProductID,self::TABLE_ID."=".$ID);
		}else{
			echo "No ID";
		}
	}
	
	public function Updateimportstatus($ImportID=0)
	{
		if($ImportID)
			Core::Update('product_relation_import',"status='I'","status='A' AND import_id=".$ImportID);
		else
			Core::Update('product_relation_import',"status='I'","status='A' AND ".Company::TABLE_ID."=".$_REQUEST['id']);
	}
	
	public function Import()
	{
		if(count($_FILES['price_list'])>0)
		{
			$CompanyID = $_POST['id'];
			$BrandID = $_POST['brand']?$_POST['brand']:0;
			$ListDate = Core::FromDateToDB($_POST['date']);
			$Description = $_POST['description'];
			$OriginalName = $_FILES['price_list']['name'];
			$FileDir = self::DEFAULT_FILE_DIR.$CompanyID."/";
			$FileName = date("s").date("i").date("H").date("d").date("m").date("Y");
			$File = new CoreFileData($_FILES['price_list'],$FileDir,$FileName);
			$File->SaveFile();
			$FileURL = $FileDir.$FileName.".".$File->GetExtension();
			$ImportID = Core::Insert('product_relation_import',Company::TABLE_ID.','.Brand::TABLE_ID.',list_date,file,name,description,creation_date,created_by,'.CoreOrganization::TABLE_ID,$CompanyID.",".$BrandID.",'".$ListDate."','".$FileURL."','".$OriginalName."','".$Description."',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID]);
			$this->ReadImportedFile($ImportID,$BrandID);
		}else{
			echo "No se encontr&oacute; el archivo";
		}
	}
	
	private function ReadImportedFile($ImportID,$BrandID=0)
	{
		if($BrandID)
			$Brand = Core::Select(Brand::TABLE,"*",Brand::TABLE_ID."=".$BrandID)[0];
		$Import = Core::Select('product_relation_import','*','import_id='.$ImportID)[0];
		include("../../../../vendors/PHPExcel/Classes/PHPExcel/IOFactory.php");
		$FileType	= PHPExcel_IOFactory::identify($Import['file']);
		$Reader 	= PHPExcel_IOFactory::createReader($FileType);
		$PHPExcel	= $Reader->load($Import['file']);
		foreach($PHPExcel->getWorksheetIterator() as $Worksheet)
		{
			// echo 'Worksheet - ' , $Worksheet->getTitle() , EOL;
			foreach($Worksheet->getRowIterator() as $Row)
			{
				$RowIndex = $Row->getRowIndex()-1;
				if($RowIndex>0)
				{
					$BID = 0;
					$Data = array();
					//echo '    Row number - ' . $RowIndex."<br>";
					$CellIterator = $Row->getCellIterator();
					// $CellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
					foreach($CellIterator as $Cell)
					{
						if(!is_null($Cell))
						{
							// if($Cell->getCoordinate() == 'B'.$Row->getRowIndex() || $Cell->getCoordinate() == 'C'.$Row->getRowIndex() || $Cell->getCoordinate() == 'D'.$Row->getRowIndex() )
							if($Cell->getCalculatedValue())
								$Data[] = $Cell->getCalculatedValue();//str_replace("  "," ",str_replace("  "," ",$Cell->getCalculatedValue()));
							else
							{
								switch($Cell->getCoordinate())
								{
									case 'A'.$Row->getRowIndex():
										echo '406';
										$this->Updateimportstatus($ImportID);
										die();
									break;
									case 'B'.$Row->getRowIndex():
										$Data[] = "'0'";
									break;
									case 'C'.$Row->getRowIndex():
										$Data[] = "0";
									break;
									case 'D'.$Row->getRowIndex():
										$Data[] = "";
									break;
								}	
							}
							//echo '        Cell  - ' . $Cell->getCoordinate() . ' - ' . $Cell->getCalculatedValue()."<br>";
						}
					}
					if($BrandID)
					{
						$BID = $BrandID;	
					}
					
					if($Data[3])
					{
						$SearchID = Core::Select(Brand::TABLE,"*","name='".$Data[3]."' AND status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID])[0][Brand::TABLE_ID];
						if($SearchID)
							$BID = $SearchID;
					}
					if($BID)
						$Relation = Core::Select(ProductRelation::TABLE,"*",Company::TABLE_ID."=".$Import[Company::TABLE_ID]." AND ".Brand::TABLE_ID."=".$BID." AND code='".$Data[0]."'")[0];
					
					if(!$Relation)
						$Relation = Core::Select(ProductAbstract::TABLE,ProductAbstract::TABLE_ID,"code='".$Data[0]."'")[0];
						
					if(!$Relation[Product::TABLE_ID] && $BID)
					{
						$Product = Core::Select(Product::TABLE,Product::TABLE_ID.",".ProductAbstract::TABLE_ID,"code='".$Data[0]."' AND ".Brand::TABLE_ID."=".$BID)[0];
						$Relation[Product::TABLE_ID] = $Product[Product::TABLE_ID];
						if($Relation[Product::TABLE_ID] && !$Relation[ProductAbstract::TABLE_ID])
						{
							$Relation[ProductAbstract::TABLE_ID] = $Product[ProductAbstract::TABLE_ID];
						}
						
					}
					$ProductID = $Relation[Product::TABLE_ID]?$Relation[Product::TABLE_ID]:0;
					$AbstractID = $Relation[ProductAbstract::TABLE_ID]?$Relation[ProductAbstract::TABLE_ID]:0;
					
					
					$Fields = $ImportID.",".$Import[Company::TABLE_ID].",".$BID.",".$AbstractID.",".$ProductID.",'".$Data[0]."',".$Data[1].",".$Data[2].",'".$Data[3]."',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID];
					$Values .= $Values? "),(".$Fields : $Fields;
				}
			}
		}
		$Result = Core::Insert('product_relation_import_item',"import_id,".Company::TABLE_ID.",".Brand::TABLE_ID.",".ProductAbstract::TABLE_ID.",".Product::TABLE_ID.",code,price,stock,brand,creation_date,created_by,".CoreOrganization::TABLE_ID,$Values);
		if($Result==false)
			$this->Updateimportstatus($ImportID);
		//echo Core::LastQuery();
	}
	
	public function Relation()
	{
		$ImportID = $_POST['id'];
		$CompanyID = $_POST[Company::TABLE_ID];
		$CurrencyID = $_POST['currency'];
		$Date = Core::FromDateToDB($_POST['date']);
		$Description = $_POST['description'];
		
		$Items = Core::Select(self::TABLE,"*","status='A' AND ".Brand::TABLE_ID.">0 AND import_id=".$ImportID);
		foreach ($Items as $Item)
		{
			$ProductID = 0;
			$Relation = Core::Select(ProductRelation::TABLE,"*",Company::TABLE_ID."=".$CompanyID." AND code='".$Item['code']."' AND ".Brand::TABLE_ID."=".$Item[Brand::TABLE_ID])[0];
			$Item[ProductAbstract::TABLE_ID] = $Item[ProductAbstract::TABLE_ID]?$Item[ProductAbstract::TABLE_ID]:0;
			if($Item[ProductAbstract::TABLE_ID]>0 && $Item[Product::TABLE_ID]==0)
			{
				$ProductData = Core::Select(Product::TABLE,Product::TABLE_ID,ProductAbstract::TABLE_ID."=".$Item[ProductAbstract::TABLE_ID]." AND ".Brand::TABLE_ID."=".$Item[Brand::TABLE_ID]);
				if(count($ProductData)==1)
					$ProductID = $ProductData[0][Product::TABLE_ID];
			}
			$Item[Product::TABLE_ID] = $ProductID?$ProductID:0;
			$Item['price'] = $Item['price']?$Item['price']:0;
			$Item['stock'] = $Item['stock']?$Item['stock']:0;
			if($Relation[ProductRelation::TABLE_ID])
			{
				if(intval(str_replace("-","",$Relation['list_date']))<= intval(str_replace("-","",$Date)))
				{
					$PriceFilter = $Item['price']>0?",price=".$Item['price']:"";
					$StockFilter = $Item['stock']>0?",stock=".$Item['stock']:"";
					Core::Update(ProductRelation::TABLE,"item_id=".$Item['item_id'].",import_id=".$Item['import_id'].",".Product::TABLE_ID."=".$Item[Product::TABLE_ID].",".ProductAbstract::TABLE_ID."=".$Item[ProductAbstract::TABLE_ID].",".Currency::TABLE_ID."=".$CurrencyID.",".Brand::TABLE_ID."=".$Item[Brand::TABLE_ID].$PriceFilter.$StockFilter.",list_date='".$Date."',updated_by=".$_SESSION[CoreUser::TABLE_ID],ProductRelation::TABLE_ID."=".$Relation[ProductRelation::TABLE_ID]);
				}
			}else{
				$Values = $CompanyID.",".$Item['item_id'].",".$Item[ProductAbstract::TABLE_ID].",".$Item['import_id'].",".$CurrencyID.",".$Item[Brand::TABLE_ID].",".$Item[Product::TABLE_ID].",'".$Item['code']."',".$Item['price'].",".$Item['stock'].",'".$Date."',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID];
				$Fields .= $Fields? "),(".$Values:$Values;	
			}
		}
		if($Fields)
			Core::Insert(ProductRelation::TABLE,Company::TABLE_ID.",item_id,".ProductAbstract::TABLE_ID.",import_id,".Currency::TABLE_ID.",".Brand::TABLE_ID.",".Product::TABLE_ID.",code,price,stock,list_date,creation_date,created_by,".CoreOrganization::TABLE_ID,$Fields);
		Core::Update(self::TABLE,"status='F'","status='A' AND ".Brand::TABLE_ID.">0 AND import_id=".$ImportID);
		Core::Update(self::TABLE,"status='I'","status='A' AND ".Brand::TABLE_ID."=0 AND import_id=".$ImportID);
		Core::Update("product_relation_import",Currency::TABLE_ID."=".$CurrencyID.",status='F'","import_id=".$ImportID);
		// echo Core::LastQuery();
		//Core::Update(self::TABLE,"status='F'","status ='A' AND brand_id>0 AND abstract_id>0");
	}
}
?>