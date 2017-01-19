<?php

class Category extends DataBase
{
	var	$ID;
	var $Data;
	var $Products = array();
	var $DefaultImgURL = '../../../skin/images/categories/default/default.jpg';
	var $Table = 'product_category';
	var $TableID = 'category_id';

	public function __construct($ID=0)
	{
		$this->Connect();
		if($ID!=0)
		{
			$Data = $this->fetchAssoc($this->Table,"*",$this->TableID."=".$ID);
			$this->Data = $Data[0];
			$this->ID = $ID;
			if($this->Data['parent_id'])
			{
				$Parent = $this->fetchAssoc($this->Table,"title","category_id=".$this->Data['parent_id']);
				$this->Data['parent'] = $Parent[0]['title'];
			}
		}
	}
	
	public function GetProducts()
	{
		if(!$this->Products)
		{
			$this->Prodcuts = $this->fetchAssoc("relation_product_category",'product_id',$this->TableID." =".$this->ID);
		}
		return $this->Products;
		
	}
	
	public function GetImg()
	{
		return $this->DefaultImgURL;
	}
	
	public function GetParentsArray($Order='title')
	{
		$Parents = array();
		$Categories = $this->fetchAssoc($this->Table,$this->TableID.',title',"status='A' AND company_id = ".$_SESSION['company_id'],$Order);
		foreach($Categories as $Category)
		{
			$Children = $this->fetchAssoc($this->Table,$this->TableID,"status='A' AND parent_id =".$Category['category_id']);
			if(count($Children)>0)
				$Parents[] = array('category_id' => $Category['category_id'], 'title' => $Category['title']);
		}
		return $Parents;
	}
	
	public function GetAllCategories($Order='parent_id,title')
	{
		return $this->fetchAssoc($this->Table,'*',"status='A' AND company_id = ".$_SESSION['company_id'],$Order);
		
	}
	
	public function CalculateCategoryLevel($CategoryID)
	{
		$Parent = $this->fetchAssoc($this->Table,"parent_id","category_id=".$CategoryID);
		$ParentID = $Parent[0]['parent_id'];
		if($ParentID>0)
		{
			return 1 + $this->CalculateCategoryLevel($ParentID);
		}else{
			return 1;
		}
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
			$Row	=	new Category($Rows[$i][$this->TableID]);
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
			if($Row->Data['parent'])
				$Row->Data['parent'] = '<span class="label label-primary">'.ucfirst($Row->Data['parent']).'</span>';
			else
				$Row->Data['parent'] = '<span class="label label-warning">L&iacute;nea Principal</span>';
			switch(strtolower($Mode))
			{
				case "list":
					$RowBackground = $i % 2 == 0? '':' listRow2 ';
					$Regs	.= '<div class="row listRow'.$RowBackground.$Restrict.'" id="row_'.$Row->ID.'" title="'.$Row->Data['title'].'">
									<div class="col-lg-7 col-md-5 col-sm-6 col-xs-10">
										<div class="listRowInner" style="text-align:left!important;">
											<img class="img-circle hideMobile990" style="margin-right:1em!important;" src="'.$Row->GetImg().'" alt="'.$Row->Data['title'].'">
											<span class="listTextStrong" style="margin-top:0.7em;">'.$Row->Data['title'].'</span>
										</div>
									</div>
									<div class="col-lg-2 col-md-3 col-sm-3 hideMobile990">
										<div class="listRowInner">
											<span class="smallTitle">Nombre corto</span>
											<span class="listTextStrong">'.ucfirst($Row->Data['short_title']).'</span>
										</div>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="smallTitle">Ubicaci&oacute;n</span>
											<span class="listTextStrong">'.ucfirst($Row->Data['parent']).'</span>
										</div>
									</div>
									<div class="col-lg-1 col-md-2 col-sm-1 hideMobile990"></div>
									<div class="listActions flex-justify-center Hidden">
										<div>'.$Actions.'</div>
									</div>
									
								</div>';
				break;
				case "grid":
				$Regs	.= '<li id="grid_'.$Row->ID.'" class="RoundItemSelect roundItemBig'.$Restrict.'" title="'.$Row->Data['title'].'">
						            <div class="flex-allCenter imgSelector">
						              <div class="imgSelectorInner">
						                <img src="'.$Row->GetImg().'" alt="'.$Row->Data['title'].'" class="img-responsive">
						                <div class="imgSelectorContent">
						                  <div class="roundItemBigActions">
						                    '.$Actions.'
						                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
						                  </div>
						                </div>
						              </div>
						              <div class="roundItemText">
						                <p><b>'.$Row->Data['title'].'</b></p>
						                <p>'.$Row->Data['short_title'].'</p>
						                <p><span class="label label-primary">'.ucfirst($Row->Data['parent']).'</span></p>
						              </div>
						            </div>
						          </li>';
				break;
			}
        }
        if(!$Regs) $Regs.= '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron l&iacute;neas.</h4><p>Puede crear una l&iacute;nea haciendo click <a href="new.php">aqui</a>.</p></div>';
		return $Regs;
	}
	
	protected function InsertSearchField()
	{
		$Parents = array();
		$Parents[] = array("menu_id"=>"0","title"=>"L&iacute;nea Principal");
		$ParentsArray = $this->GetParentsArray();
		foreach($ParentsArray as $Parent)
		{
			$Parents[] = array($this->TableID=>$Parent[$this->TableID],'title'=>$Parent['title']);
		}
		
		return '<!-- Name -->
          <div class="input-group">
            <span class="input-group-addon order-arrows sort-activated" order="title" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('text','title','','form-control','placeholder="L&iacute;nea"').'
          </div>
          <!-- Short Name -->
          <div class="input-group">
            <span class="input-group-addon order-arrows sort-activated" order="short_title" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('text','short_title','','form-control','placeholder="Nombre Corto"').'
          </div>
          <!-- Parents -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="parent" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('select','parent','','form-control','',$Parents,'','Cualquier Ubicaci&oacute;n').'
          </div>
          ';
	}
	
	protected function InsertSearchButtons()
	{
		return '<!-- New Button -->
		    	<a href="new.php"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-user-plus"></i> Nueva L&iacute;nea</button></a>
		    	<!-- /New Button -->';
	}
	
	public function ConfigureSearchRequest()
	{
		$this->SetTable($this->Table.' b');
		$this->SetFields('b.*');
		$this->SetWhere("b.company_id = ".$_SESSION['company_id']);
		//$this->AddWhereString(" AND c.company_id = a.company_id");
		$this->SetOrder('title');
		$this->SetGroupBy("b.".$this->TableID);
		
		foreach($_POST as $Key => $Value)
		{
			$_POST[$Key] = $Value;
		}
			
		if($_POST['title']) $this->SetWhereCondition("b.title","LIKE","%".$_POST['title']."%");
		if($_POST['short_title']) $this->SetWhereCondition("b.short_title","LIKE","%".$_POST['short_title']."%");
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
		$Title		= $_POST['title'];
		$ShortTitle	= $_POST['short_title'];
		$Parent		= $_POST['parent'];
		if(!$Parent) $Parent = 0;
		$Insert			= $this->execQuery('insert',$this->Table,'title,short_title,parent_id,creation_date,company_id,created_by',"'".$Title."','".$ShortTitle."',".$Parent.",NOW(),".$_SESSION['company_id'].",".$_SESSION['admin_id']);
		//echo $this->lastQuery();
	}	
	
	public function Update()
	{
		$ID 		= $_POST['id'];
		$Edit		= new Category($ID);
		$Title		= $_POST['title'];
		$ShortTitle	= $_POST['short_title'];
		$Parent		= $_POST['parent'];
		if(!$Parent) $Parent = 0;
		
		$Update		= $this->execQuery('update',$this->Table,"title='".$Title."',short_title='".$ShortTitle."',parent_id=".$Parent.",updated_by=".$_SESSION['admin_id'],$this->TableID."=".$ID);
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
		$Name 			= $_POST['title'];
		$ActualName 	= $_POST['titlename'];

	    if($ActualName)
	    	$TotalRegs  = $this->numRows($this->Table,'*',"title = '".$Name."' AND title<> '".$ActualName."'");
    	else
		    $TotalRegs  = $this->numRows($this->Table,'*',"title = '".$Name."'");
		if($TotalRegs>0) echo $TotalRegs;
	}
}
?>
