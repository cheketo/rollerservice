<?php

class Brand extends DataBase
{
	var	$ID;
	var $Data;
	var $Products = array();
	var $DefaultImgURL = '../../../skin/images/brands/default/default.png';

	public function __construct($ID=0)
	{
		$this->Connect();
		if($ID!=0)
		{
			$Data = $this->fetchAssoc("product_brand","*","brand_id=".$ID);
			$this->Data = $Data[0];
			$this->ID = $ID;
			if($this->Data['country_id'])
			{
				$Country = $this->fetchAssoc('admin_country','*','country_id='.$this->Data['country_id']);
				$this->Data['country'] = $Country[0]['title'];
			}
		}
	}
	
	public function GetProducts()
	{
		if(!$this->Products)
		{
			$this->Prodcuts = $this->fetchAssoc("relation_product_brand",'product_id',"brand_id =".$this->ID);
		}
		return $this->Products;
		
	}
	
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
			$Row	=	new Brand($Rows[$i]['brand_id']);
			$Actions= '';
			//var_dump($Row);
			// $UserGroups = $Row->GetGroups();
			// $Groups='';
			// foreach($UserGroups as $Group)
			// {
			// 	$Groups .= '<span class="label label-warning">'.$Group['title'].'</span> ';
			// }
			// if(!$Groups) $Groups = 'Ninguno';
			//$Actions	= 	'<span class="roundItemActionsGroup"><a><button type="button" class="btn btnGreen ExpandButton" id="expand_'.$Row->ID.'"><i class="fa fa-plus"></i></button></a>';
			$Actions	.= 	'<span class="roundItemActionsGroup"><a href="edit.php?id='.$Row->ID.'"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
			if($Row->Data['status']=="A")
			{
				$Actions	.= '<a class="deleteElement" process="../../library/processes/proc.common.php" id="delete_'.$Row->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
			}else{
				$Actions	.= '<a class="activateElement" process="../../library/processes/proc.common.php" id="activate_'.$Row->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			}
			$Actions	.= '</span>';
			
			if($Row->Data['country'])
			{
				$Row->Data['country'] = '<span class="label label-primary">'.ucfirst($Row->Data['country']).'</span>';
			}else{
				$Row->Data['country'] = 'Indeterminado';
			}
			switch(strtolower($Mode))
			{
				case "list":
					$RowBackground = $i % 2 == 0? '':' listRow2 ';
					$Regs	.= '<div class="row listRow'.$RowBackground.$Restrict.'" id="row_'.$Row->ID.'" title="'.$Row->Data['name'].'">
									<div class="col-lg-7 col-md-6 col-sm-10 col-xs-10">
										<div class="listRowInner" style="text-align:left!important;">
											<img class="img-circle hideMobile990" style="margin-right:1em!important;" src="'.$Row->GetImg().'" alt="'.$Row->Data['name'].'">
											<span class="listTextStrong" style="margin-top:0.7em;">'.$Row->Data['name'].'</span>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 hideMobile990">
										<div class="listRowInner">
											<span class="smallTitle">Origen</span>
											<span class="listTextStrong">'.ucfirst($Row->Data['country']).'</span>
										</div>
									</div>
									<div class="col-lg-1 col-md-2 col-sm-1 hideMobile990"></div>
									<div class="listActions flex-justify-center Hidden">
										<div>'.$Actions.'</div>
									</div>
									
								</div>';
				break;
				case "grid":
				$Regs	.= '<li id="grid_'.$Row->ID.'" class="RoundItemSelect roundItemBig'.$Restrict.'" title="'.$Row->Data['name'].'">
						            <div class="flex-allCenter imgSelector">
						              <div class="imgSelectorInner">
						                <img src="'.$Row->GetImg().'" alt="'.$Row->Data['name'].'" class="img-responsive">
						                <div class="imgSelectorContent">
						                  <div class="roundItemBigActions">
						                    '.$Actions.'
						                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
						                  </div>
						                </div>
						              </div>
						              <div class="roundItemText">
						                <p><b>'.$Row->Data['name'].'</b></p>
						                <p><span class="label label-primary">'.ucfirst($Row->Data['country']).'</span></p>
						              </div>
						            </div>
						          </li>';
				break;
			}
        }
        if(!$Regs) $Regs.= '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron marcas.</h4><p>Puede crear una marca haciendo click <a href="new.php">aqui</a>.</p></div>';
		return $Regs;
	}
	
	protected function InsertSearchField()
	{
		return '<!-- Name -->
          <div class="input-group">
            <span class="input-group-addon order-arrows sort-activated" order="name" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('text','name','','form-control','placeholder="Marca"').'
          </div>
          <!-- Corporate Name -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="country" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('text','country','','form-control','placeholder="Origen"').'
          </div>
          ';
	}
	
	protected function InsertSearchButtons()
	{
		return '<!-- New Button -->
		    	<a href="new.php"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-user-plus"></i> Nueva Marca</button></a>
		    	<!-- /New Button -->';
	}
	
	public function ConfigureSearchRequest()
	{
		$this->SetTable('product_brand b, admin_country c');
		$this->SetFields('b.*,c.title');
		$this->SetWhere("b.company_id = ".$_SESSION['company_id']);
		//$this->AddWhereString(" AND c.company_id = a.company_id");
		$this->SetOrder('name');
		$this->SetGroupBy("b.brand_id");
		
		foreach($_POST as $Key => $Value)
		{
			$_POST[$Key] = $Value;
		}
			
		if($_POST['name']) $this->SetWhereCondition("b.name","LIKE","%".$_POST['name']."%");
		if($_POST['country']){
			$this->SetWhereCondition("c.title","LIKE","%".$_POST['country']."%");
			$this->AddWhereString(" AND b.country_id = c.country_id");
		}
		
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
				case "agent_name": 
					$this->AddWhereString(" AND b.country_id = c.country_id");
					$Order = 'title';
					$Prefix = "c.";
				break;
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
		$Name			= $_POST['name'];
		$Country		= $_POST['country'];
		if(!$Country) $Country = 0;
		$Insert			= $this->execQuery('insert','product_brand','name,country_id,creation_date,company_id',"'".$Name."',".$Country.",NOW(),".$_SESSION['company_id']);
		//echo $this->lastQuery();
	}	
	
	public function Update()
	{
		$ID 		= $_POST['id'];
		$Edit		= new Brand($ID);
		$Name		= $_POST['name'];
		$Country	= $_POST['country'];
		
		$Update		= $this->execQuery('update','product_brand',"name='".$Name."',country_id=".$Country,"brand_id=".$ID);
		//echo $this->lastQuery();
	}
	
	public function Activate()
	{
		$ID	= $_POST['id'];
		$this->execQuery('update','product_brand',"status = 'A'","brand_id=".$ID);
	}
	
	public function Delete()
	{
		$ID	= $_POST['id'];
		$this->execQuery('update','product_brand',"status = 'I'","brand_id=".$ID);
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
		$Name 			= strtolower($_POST['name']);
		$ActualName 	= strtolower($_POST['actualname']);

	    if($ActualName)
	    	$TotalRegs  = $this->numRows('product_brand','*',"name = '".$Name."' AND name<> '".$ActualName."'");
    	else
		    $TotalRegs  = $this->numRows('product_brand','*',"name = '".$Name."'");
		if($TotalRegs>0) echo $TotalRegs;
	}
}
?>
