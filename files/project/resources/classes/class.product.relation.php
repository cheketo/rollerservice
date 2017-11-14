<?php

class ProductRelation
{
	use CoreSearchList,CoreCrud,CoreImage;
	
	const TABLE				= 'product_relation';
	const TABLE_ID			= 'relation_id';
	const SEARCH_TABLE		= 'view_product_relation_list';
	const DEFAULT_IMG		= '../../../../skin/images/products/default/default.jpg';
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
		if($Mode!='grid') $HTML .=	'<a class="hint--bottom hint--bounce showMobile990" aria-label="M&aacute;s informaci&oacute;n"><button type="button" class="btn bg-navy ExpandButton" id="expand_'.$Object->ID.'"><i class="fa fa-plus"></i></button></a> ';;
		$HTML	.= 	'<a href="new.relation.php?id='.$Object->ID.'" class="hint--bottom hint--bounce hint--info" aria-label="Editar"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
		
		$HTML	.= '<a class="deleteElement hint--bottom hint--bounce hint--error" aria-label="Eliminar" process="'.PROCESS.'" id="delete_'.$Object->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
		$HTML	.= Core::InsertElement('hidden','delete_question_'.$Object->ID,'&iquest;Desea eliminar la relaci&oacute;n <b>'.$Object->Data['code'].'</b> ?');
		$HTML	.= Core::InsertElement('hidden','delete_text_ok_'.$Object->ID,'La relaci&oacute;n <b>'.$Object->Data['code'].'</b> ha sido eliminada.');
		$HTML	.= Core::InsertElement('hidden','delete_text_error_'.$Object->ID,'Hubo un error al intentar eliminar el art&iacute;culo <b>'.$Object->Data['code'].'</b>.');
			
		
		return $HTML;
	}
	
	protected static function MakeListHTML($Object)
	{
		$HTML = '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					<div class="listRowInner">
						<img class="img-circle hideMobile990" src="'.$Object->Img.'" alt="'.$Object->Data['code'].'">
						<span class="listTextStrong"><span class="label label-info">'.$Object->Data['brand'].'</span></span>
						<span class="listTextStrong"><b>'.$Object->Data['code'].'</b></span>
						<span class="smallTitle">U$D '.$Object->Data['price'].'</span>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					<div class="listRowInner">
						<span class="listTextStrong">Roller Service</span>
						<span class="listTextStrong"><b>'.$Object->Data['local_code'].'</b></span>
						<span class="smallTitle">U$D '.$Object->Data['local_price'].'</span>
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 hideMobile990">
					<div class="listRowInner">
						<span class="listTextStrong">Marca</span>
						<span class="listTextStrong"><span class="label label-primary">'.$Object->Data['brand'].'</span></span>
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 hideMobile990">
					<div class="listRowInner">
						<span class="listTextStrong">Categor&iacute;a</span>
						<span class="listTextStrong"><span class="label label-warning">'.$Object->Data['category'].'</span></span>
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
		                <p>('.$Object->Data['local_code'].')</p>
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
		$this->SearchFields['product_id'] = Core::InsertElement('select',Product::TABLE_ID,'','form-control chosenSelect','',Product::GetFullCodes(),'','Cualquier Art&iacute;culo');
		$this->SearchFields['brand_id'] = Core::InsertElement('select',Brand::TABLE_ID,'','form-control chosenSelect','',Core::Select(Brand::TABLE,Brand::TABLE_ID.',name',"status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],"name"),'','Cualquier Marca');
		$this->SearchFields['category_id'] = Core::InsertElement('select',Category::TABLE_ID,'','form-control chosenSelect','',Core::Select(Category::TABLE,Category::TABLE_ID.',title',"status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],"title"),'','Cualquier L&iacute;nea');
		$this->SearchFields['price_from'] = Core::InsertElement('text','price_from','','form-control','placeholder="Precio Desde"');
		$this->SearchFields['price_to'] = Core::InsertElement('text','price_to','','form-control','placeholder="Precio Hasta"');
	}
	
	protected function InsertSearchButtons()
	{
		return '<a href="new.relation.php" class="hint--bottom hint--bounce hint--success" aria-label="Nueva Relaci&oacute;n"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-plus-square"></i></button></a>';
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
		
		if($_POST['view_order_field']=="price_from" || $_POST['view_order_field']=="price_to")
			$_POST['view_order_field'] = "price";
		
		if($_POST['view_order_field']=="stock_from" || $_POST['view_order_field']=="stock_to")
			$_POST['view_order_field'] = "stock";
			
		$this->SetSearchRequest();
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function Relation()
	{
		$Code		= $_POST['code'];
		$ProductID	= $_POST[Product::TABLE_ID];
		$RelationID	= $_POST['relation'];
		
		if(!$RelationID)
		{
			Core::Insert(self::TABLE,'code,'.Product::TABLE_ID.',creation_date,'.CoreOrganization::TABLE_ID.',created_by',"'".$Code."',".$ProductID.",NOW(),".$_SESSION[CoreOrganization::TABLE_ID].",".$_SESSION[CoreUser::TABLE_ID]);		
		}else{
			Core::Update(self::TABLE,"code='".$Code."',".Product::TABLE_ID."=".$ProductID,self::TABLE_ID."=".$RelationID);
		}
		
		// echo $this->LastQuery();
	}
	
	public function Delete()
	{
		$ID = $_POST['id'];
		Core::Delete(self::TABLE,self::TABLE_ID."=".$ID);
	}
	
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
	
	public function Checkrelation()
	{
		$ID = $_POST['id'];
		$Data = Core::Select(self::TABLE,self::TABLE_ID.",code",Product::TABLE_ID."=".$ID)[0];
		echo $Data['code']."///".$Data[self::TABLE_ID];
	}
	
	public function Checkimport()
	{
		$ImportID = Core::Select('product_relation_import','*',"status='A' AND company_id=".$_POST['id'],"creation_date DESC")[0]['import_id'];
		//echo Core::LastQuery();
		if($ImportID)
			echo $ImportID;
	}
	
	public function Updateimportstatus($ImportID=0)
	{
		if($ImportID)
			Core::Update('product_relation_import',"status='I'","status='A' AND import_id=".$ImportID);
		else
			Core::Update('product_relation_import',"status='I'","status='A' AND company_id=".$_REQUEST['id']);
	}
	
	public function Import()
	{
		if(count($_FILES['price_list'])>0)
		{
			$CompanyID = $_POST['id'];
			$OriginalName = $_FILES['price_list']['name'];
			$FileDir = self::DEFAULT_FILE_DIR.$CompanyID."/";
			$FileName = date("s").date("i").date("H").date("d").date("m").date("Y");
			$File = new CoreFileData($_FILES['price_list'],$FileDir,$FileName);
			$File->SaveFile();
			$FileURL = $FileDir.$FileName.".".$File->GetExtension();
			$ImportID = Core::Insert('product_relation_import',Company::TABLE_ID.',file,name,creation_date,created_by,'.CoreOrganization::TABLE_ID,$CompanyID.",'".$FileURL."','".$OriginalName."',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID]);
			$this->ReadImportedFile($ImportID);
		}else{
			echo "No se encontr&oacute; el archivo";
		}
	}
	
	private function ReadImportedFile($ImportID)
	{
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
								$Data[] = $Cell->getCalculatedValue();
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
										$Data[] = "-1";
									break;
									case 'D'.$Row->getRowIndex():
										$Data[] = "";
									break;
								}	
							}
							//echo '        Cell  - ' . $Cell->getCoordinate() . ' - ' . $Cell->getCalculatedValue()."<br>";
						}
					}
					$Fields = $ImportID.",".$Import['company_id'].",'".$Data[0]."',".$Data[1].",".$Data[2].",'".$Data[3]."',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID];
					$Values .= $Values? "),(".$Fields : $Fields;
				}
			}
		}
		$Result = Core::Insert('product_relation_import_item',"import_id,company_id,code,price,stock,brand,creation_date,created_by,organization_id",$Values);
		if($Result==false)
			$this->Updateimportstatus($ImportID);
		//echo Core::LastQuery();
	}
}
?>