<?php

class Product extends DataBase
{
	var	$ID;
	var $Data;
	var $Providers = array();
	var $DefaultImgURL = '../../../skin/images/products/default/default.jpg';
	var $Table = 'product';
	var $TableID = 'product_id';

	public function __construct($ID=0)
	{
		$this->Connect();
		if($ID!=0)
		{
			$Data = $this->fetchAssoc($this->Table,"*",$this->TableID."=".$ID);
			$this->Data = $Data[0];
			$this->ID = $ID;
			$Data = $this->fetchAssoc($this->Table.'_category',"title","category_id=".$this->Data['category_id']);
			$this->Data['category'] = $Data[0]['title'];
			$Data = $this->fetchAssoc($this->Table.'_brand',"name","brand_id=".$this->Data['brand_id']);
			$this->Data['brand'] = $Data[0]['name'];
		}
	}
	
	public function GetProviders()
	{
		if(!$this->Providers)
		{
			$Providers = $this->fetchAssoc("relation_provider",'provider_id',$this->TableID." =".$this->ID);
			foreach($Providers as $Provider)
			{
				if($ProvidersID) $ProvidersID .= ',';
				$ProvidersID .= $Provider['provider_id'];
			}
			$this->Providers = $this->fetchAssoc('provider','*',"status='A' AND provider_id IN ('.$ProvidersID.')");
		}
		return $this->Providers;
	}
	
	// public function GetCategoryTree()
	// {
		
	// }
	
	public function GetImg()
	{
		return $this->DefaultImgURL;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// SEARCHLIST FUNCTIONS ///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function MakeRegs($Mode="List")
	{
		$Rows	= $this->GetRegs();
		//echo $this->lastQuery();
		for($i=0;$i<count($Rows);$i++)
		{
			$Row	=	new Product($Rows[$i][$this->TableID]);
			$Actions= '';
			//$Row->Data['code'] = $Row->Data['code'];
			//var_dump($Row);
			// $UserGroups = $Row->GetGroups();
			// $Groups='';
			// foreach($UserGroups as $Group)
			// {
			// 	$Groups .= '<span class="label label-warning">'.$Group['title'].'</span> ';
			// }
			// if(!$Groups) $Groups = 'Ninguno';
			$Actions	.= 	'<span class="roundItemActionsGroup"><a><button type="button" class="btn btnGreen ExpandButton" id="expand_'.$Row->ID.'"><i class="fa fa-plus"></i></button></a>';
			$Actions	.= 	'<span class="roundItemActionsGroup"><a href="edit.php?id='.$Row->ID.'"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
			if($Row->Data['status']=="A")
			{
				$Actions	.= '<a class="deleteElement" process="../../library/processes/proc.common.php" id="delete_'.$Row->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
			}else{
				$Actions	.= '<a class="activateElement" process="../../library/processes/proc.common.php" id="activate_'.$Row->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			}
			$Actions	.= '</span>';
			switch(strtolower($Mode))
			{
				case "list":
					$RowBackground = $i % 2 == 0? '':' listRow2 ';
					$Regs	.= '<div class="row listRow'.$RowBackground.$Restrict.'" id="row_'.$Row->ID.'" title="'.$Row->Data['code'].'">
									<div class="col-lg-3 col-md-3 col-sm-10 col-xs-10">
										<div class="listRowInner" style="text-align:left!important;">
											<img class="img-circle" style="margin-right:1em!important;" src="'.$Row->GetImg().'" alt="'.$Row->Data['cod'].'">
											<span class="listTextStrong" style="margin-top:0.7em;">'.$Row->Data['code'].'</span>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3">
										<div class="listRowInner">
											<span class="smallTitle">L&iacute;nea</span>
											<span class="listTextStrong"><span class="label label-primary">'.ucfirst($Row->Data['category']).'</span></span>
										</div>
									</div>
									<div class="col-lg-1 col-md-1 col-sm-1 hideMobile990">
										<div class="listRowInner">
											<span class="smallTitle">Estanter&iacute;a</span>
											<span class="listTextStrong"><span class="label label-warning">'.strtoupper($Row->Data['rack']).'</span></span>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 hideMobile990">
										<div class="listRowInner">
											<span class="smallTitle">'.$Row->Data['description'].'</span>
										</div>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-1 hideMobile990"></div>
									<div class="listActions flex-justify-center Hidden">
										<div>'.$Actions.'</div>
									</div>
									
								</div>';
				break;
				case "grid":
				$Regs	.= '<li id="grid_'.$Row->ID.'" class="RoundItemSelect roundItemBig'.$Restrict.'" title="'.$Row->Data['code'].'">
						            <div class="flex-allCenter imgSelector">
						              <div class="imgSelectorInner">
						                <img src="'.$Row->GetImg().'" alt="'.$Row->Data['code'].'" class="img-responsive">
						                <div class="imgSelectorContent">
						                  <div class="roundItemBigActions">
						                    '.$Actions.'
						                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
						                  </div>
						                </div>
						              </div>
						              <div class="roundItemText">
						                <p><b>'.$Row->Data['code'].'</b></p>
						                <p><span class="label label-primary">'.ucfirst($Row->Data['category']).'</span></p>
						              </div>
						            </div>
						          </li>';
				break;
			}
        }
        if(!$Regs) $Regs.= '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron art&iacute;culos.</h4><p>Puede crear un art&iacute;culo haciendo click <a href="new.php">aqui</a>.</p></div>';
		return $Regs;
	}
	
	protected function InsertSearchField()
	{
		return '<!-- Name -->
          <div class="input-group">
            <span class="input-group-addon order-arrows sort-activated" order="code" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('text','code','','form-control','placeholder="C&oacute;digo"').'
          </div>
          <!-- Categories -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="category" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('select','group','','form-control','',$this->fetchAssoc('product_category a, relation_product_category b','a.*',"a.category_id = b.category_id AND a.status = 'A' AND a.company_id = ".$_SESSION['company_id'],"a.title"),'', 'L&iacute;nea').'
          </div>
          ';
	}
	
	protected function InsertSearchButtons()
	{
		return '<!-- New Button -->
		    	<a href="new.php"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-user-plus"></i> Nuevo Art&iacute;culo</button></a>
		    	<!-- /New Button -->';
	}
	
	public function ConfigureSearchRequest()
	{
		$this->SetTable($this->Table.' b');
		$this->SetFields('b.*');
		$this->SetWhere("b.company_id = ".$_SESSION['company_id']);
		//$this->AddWhereString(" AND c.company_id = a.company_id");
		$this->SetOrder('code');
		$this->SetGroupBy("b.".$this->TableID);
		
		foreach($_POST as $Key => $Value)
		{
			$_POST[$Key] = $Value;
		}
			
		if($_POST['code']) $this->SetWhereCondition("b.code","LIKE","%".$_POST['code']."%");
		if(!is_null($_POST['parent']))
		{
			switch($_POST['parent'])
			{
				case "0": $this->SetWhereCondition("b.parent_id","=",0); break;
				case '': break;
				default:
					$this->SetWhereCondition("b.parent_id","=",$_POST['parent']);
				break;
			}
			
			
			
		}
		// if($_POST['country']){
		// 	$this->SetWhereCondition("c.title","LIKE","%".$_POST['country']."%");
		// 	$this->AddWhereString(" AND b.country_id = c.country_id");
		// }
		
		//if($_POST['agent_email']) $this->SetWhereCondition("a.email","LIKE","%".$_POST['agent_email']."%");
		//if($_POST['agent_charge']) $this->SetWhereCondition("a.charge","LIKE", "%".$_POST['agent_charge']."%");
		// if($_POST['phone']) $this->AddWhereString(" AND (c.phone LIKE '%".$_POST['phone']."%' OR a.phone LIKE '%".$_POST['phone']."%')");
		//if($_POST['parent']) $this->SetWhereCondition("c.parent_id","=",$_POST['parent']);
		// if($_POST['agent_name'])
		// {
		// 	$this->AddWhereString(" AND c.company_id = a.company_id");
		// }
		if($_REQUEST['status'])
		{
			if($_POST['status']) $this->SetWhereCondition("b.status","=", $_POST['status']);
			if($_GET['status']) $this->SetWhereCondition("b.status","=", $_GET['status']);	
		}else{
			$this->SetWhereCondition("b.status","=","A");
		}
		if($_POST['view_order_field'])
		{
			if(strtolower($_POST['view_order_mode'])=="desc")
				$Mode = "DESC";
			else
				$Mode = $_POST['view_order_mode'];
			
			$Order = strtolower($_POST['view_order_field']);
			switch($Order)
			{
				// case "agent_name": 
				// 	$this->AddWhereString(" AND b.country_id = c.country_id");
				// 	$Order = 'title';
				// 	$Prefix = "c.";
				// break;
				default:
					$Prefix = "b.";
				break;
			}
			$this->SetOrder($Prefix.$Order." ".$Mode);
		}
		if($_POST['regsperview'])
		{
			$this->SetRegsPerView($_POST['regsperview']);
		}
		if(intval($_POST['view_page'])>0)
			$this->SetPage($_POST['view_page']);
	}

	public function MakeList()
	{
		return $this->MakeRegs("List");
	}

	public function MakeGrid()
	{
		return $this->MakeRegs("Grid");
	}

	public function GetData()
	{
		return $this->Data;
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
		$Insert		= $this->execQuery('insert',$this->Table,'code,category_id,price,brand_id,rack,size,stock_min,stock_max,description,creation_date,company_id,created_by',"'".$Code."',".$Category.",".$Price.",".$Brand.",'".$Rack."','".$Size."',".$StockMin.",".$StockMax.",'".$Description."',NOW(),".$_SESSION['company_id'].",".$_SESSION['admin_id']);
		//echo $this->lastQuery();
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
		
		$Update		= $this->execQuery('update',$this->Table,"code='".$Code."',category_id=".$Category.",brand_id=".$Brand.",price=".$Price.",rack='".$Rack."',size='".$Size."',stock_min='".$StockMin."',stock_max='".$StockMax."',description='".$Description."',updated_by=".$_SESSION['admin_id'],$this->TableID."=".$ID);
		//echo $this->lastQuery();
	}
	
	public function Activate()
	{
		$ID	= $_POST['id'];
		$this->execQuery('update',$this->Table,"status = 'A'",$this->TableID."=".$ID);
	}
	
	public function Delete()
	{
		$ID	= $_POST['id'];
		$this->execQuery('update',$this->Table,"status = 'I'",$this->TableID."=".$ID);
		//echo $this->lastQuery();
	}
	
	public function Search()
	{
		$this->ConfigureSearchRequest();
		echo $this->InsertSearchResults();
	}
	
	// public function Newimage()
	// {
	// 	if(count($_FILES['image'])>0)
	// 	{
	// 		if($_POST['newimage']!=$this->GetDefaultImg() && file_exists($_POST['newimage']))
	// 			unlink($_POST['newimage']);
	// 		$TempDir = $this->ImgGalDir;
	// 		$Name	= "company".intval(rand()*rand()/rand());
	// 		$Img	= new FileData($_FILES['image'],$TempDir,$Name);
	// 		echo $Img	-> BuildImage(365,110);
	// 	}
	// }
	
	public function Validate()
	{
		$Name 			= strtolower($_POST['code']);
		$ActualName 	= strtolower($_POST['actualcode']);

	    if($ActualName)
	    	$TotalRegs  = $this->numRows($this->Table,'*',"code = '".$Name."' AND code<> '".$ActualName."'");
    	else
		    $TotalRegs  = $this->numRows($this->Table,'*',"code = '".$Name."'");
		if($TotalRegs>0) echo $TotalRegs;
	}
}
?>
