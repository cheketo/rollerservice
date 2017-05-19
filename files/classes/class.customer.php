<?php

class Customer extends DataBase
{
	var	$ID;
	var $Data;
	var $ImgGalDir		= '../../../skin/images/providers/';
	var $Branches 		= array();
	var $Table			= "customer";
	var $TableID		= "customer_id";
	
	const DEFAULTIMG	= "../../../skin/images/customers/default/default.png";

	public function __construct($ID=0)
	{
		$this->Connect();
		$this->ID = $ID;
		if($ID!=0)
		{
			$Data = $this->fetchAssoc($this->Table,"*",$this->TableID."=".$ID);
			$this->Data = $Data[0];
			$this->Data['branches'] = $this->GetBranches();
		}
	}
	
	public function GetBranches()
	{
		if(empty($this->Branches))
		{
			$this->Branches = $this->fetchAssoc(
				$this->Table."_branch a 
				LEFT JOIN geolocation_country b ON (a.country_id = b.country_id)
				LEFT JOIN geolocation_province c ON (a.province_id = c.province_id)
				LEFT JOIN geolocation_region d ON (a.region_id = d.region_id)
				LEFT JOIN geolocation_zone e ON (a.zone_id = e.zone_id)
				",
				"a.*,b.name as country,b.short_name as country_short,c.name as province,c.short_name as province_short,d.name as region,d.short_name as region_short,e.name as zone,e.short_name as zone_short",
				$this->TableID."=".$this->ID,'a.main_branch DESC,a.branch_id');
		}
		return $this->Branches;
	}

	public function GetDefaultImg()
	{
		return self::DEFAULTIMG;
	}
	
	public function GetImg()
	{
		if(!$this->Data['logo'] || !file_exists($this->Data['logo']))
			return $this->GetDefaultImg();
		else
			return $this->Data['logo'];
	}
	
	public function ImgGalDir()
	{
		$Dir = $this->ImgGalDir.'/'.$this->ID;
		if(!file_exists($Dir))
			mkdir($Dir);
		return $Dir;
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
			$Row	=	new Customer($Rows[$i][$this->TableID]);
			//var_dump($Row);
			// $UserGroups = $Row->GetGroups();
			// $Groups='';
			// foreach($UserGroups as $Group)
			// {
			// 	$Groups .= '<span class="label label-warning">'.$Group['title'].'</span> ';
			// }
			// if(!$Groups) $Groups = 'Ninguno';
			$Actions	= 	'<span class="roundItemActionsGroup"><a><button type="button" class="btn btnGreen ExpandButton" id="expand_'.$Row->ID.'"><i class="fa fa-plus"></i></button></a>';
			$Actions	.= 	'<a href="edit.php?id='.$Row->ID.'"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
			if($Row->Data['status']=="A")
			{
				$Actions	.= '<a class="deleteElement" process="../../library/processes/proc.common.php" id="delete_'.$Row->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
			}else{
				$Actions	.= '<a class="activateElement" process="../../library/processes/proc.common.php" id="activate_'.$Row->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			}
			$Actions	.= '</span>';
			// echo '<pre>';
			// print_r($Row->Data['branches']);
			// echo '</pre>';
			
			$Branches = '';
			$I=0;
			foreach($Row->Data['branches'] as $Branch)
			{
				$I++;
				$RowClass = $I % 2 != 0? 'bg-gray':'';
				
				$BranchAddress = $Branch['address'].', '.$Branch['zone_short'].', '.$Branch['region_short'].', '.$Branch['province_short'].', '.$Branch['country'];
				
				$BranchDataFields = '';
				$BranchDataFields .= $Branch['phone']? '<span class="smallDetails"><i class="fa fa-phone"></i> '.$Branch['phone'].'</span>':'';
				$BranchDataFields .= $Branch['email']? '<span class="smallDetails"><i class="fa fa-envelope"></i> '.$Branch['email'].'</span>':'';
				$BranchDataFields .= $Branch['fax']? '<span class="smallDetails"><i class="fa fa-fax"></i> '.$Branch['fax'].'</span>':'';
				$BranchDataFields .= $Branch['website']? '<span class="smallDetails"><i class="fa fa-desktop"></i> '.$Branch['website'].'</span>':'';
				$BranchData = $BranchDataFields? '<div class="col-md-4 col-md-6 col-sm-12"><div class="listRowInner"><span class="listTextStrong">Datos de Contacto</span>'.$BranchDataFields.'</div></div>':'';
				
				$Branches .= '
							<div class="row '.$RowClass.'">
								<div class="col-md-4 col-md-6 col-sm-12">
									<div class="listRowInner">
										<span class="listTextStrong">Sucursal '.$Branch['name'].'</span>
										<span class="smallDetails">'.$BranchAddress.'</span>
									</div>
								</div>
								'.$BranchData.'
									
							</div>';
			}
			
			switch(strtolower($Mode))
			{
				case "list":
					$RowBackground = $i % 2 == 0? '':' listRow2 ';
					$Phone = $Row->Data['branches'][0]['phone']? '<span class="smallDetails"><i class="fa fa-phone"></i> '.$Row->Data['branches'][0]['phone'].'</span>':'';
					
					$Regs	.= '<div class="row listRow'.$RowBackground.$Restrict.'" id="row_'.$Row->ID.'" title="'.$Row->Data['name'].'">
									<div class="col-lg-3 col-md-5 col-sm-8 col-xs-10">
										<div class="listRowInner">
											<img class="img-circle" src="'.$Row->GetImg().'" alt="'.$Row->Data['name'].'">
											<span class="listTextStrong">'.$Row->Data['name'].'</span>
											<span class="smallDetails"><i class="fa fa-map-marker"></i> '.$Row->Data['branches'][0]['address'].', '.$Row->Data['branches'][0]['zone'].'</span>
											'.$Phone.'
										</div>
									</div>
									<div class="col-lg-2 col-md-3 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="listTextStrong">Saldo</span>
											<span class="emailTextResp"><span class="label label-success">$ 712.32</span>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="listTextStrong">Tipo Cliente</span>
											<span class="listTextStrong"><span class="label label-primary">'.$Rows[$i]['type'].'</span></span>
										</div>
									</div>
									<div class="animated DetailedInformation Hidden col-md-12">
										'.$Branches.'
									</div>
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
						                <p>'.ucfirst($Row->Data['iibb']).'</p>
						                <p>('.$Row->Data['cuit'].')</p>
						              </div>
						            </div>
						          </li>';
				break;
			}
        }
        if(!$Regs) $Regs.= '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron empresas.</h4><p>Puede crear una empresa haciendo click <a href="new.php">aqui</a>.</p></div>';
		return $Regs;
	}
	
	protected function InsertSearchField()
	{
		if(!$_GET['country'])
		{
			$CountryField = '<!-- Country -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="country" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('text','country','','form-control','placeholder="Pa&iacute;s"').'
          </div>';
		}
		
		return '
			<!-- Name -->
          <div class="input-group">
            <span class="input-group-addon order-arrows sort-activated" order="name" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('text','name','','form-control','placeholder="Nombre"').'
          </div>
          <!-- CUIT -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="cuit" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('text','cuit_number','','form-control','placeholder="CUIT"').'
          </div>
          <!-- IIBB -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="iibb" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('text','iibb','','form-control','placeholder="Ingresos Brutos"').'
          </div>
          <!-- Address -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="address" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('text','address','','form-control','placeholder="Direcci&oacute;n"').'
          </div>
          '.$CountryField;
	}
	
	protected function InsertSearchButtons()
	{
		return '<!-- New Button -->
		    	<a href="new.php"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-user-plus"></i> Nuevo Cliente</button></a>
		    	<!-- /New Button -->';
	}
	
	public function ConfigureSearchRequest()
	{
		$this->SetTable(
			$this->Table.' a 
			INNER JOIN customer_branch b ON (b.customer_id = a.customer_id)
			LEFT JOIN customer_agent c ON (c.customer_id = a.customer_id)
			INNER JOIN customer_type d ON (d.type_id = a.type_id)
			LEFT JOIN relation_customer_broker e ON (e.branch_id = b.branch_id)
			LEFT JOIN admin_user f ON (f.admin_id = e.broker_id)
			LEFT JOIN geolocation_country g ON (g.country_id = b.country_id)
			LEFT JOIN geolocation_province h ON (h.province_id = b.province_id)
			LEFT JOIN geolocation_region i ON (i.region_id = b.region_id)
			LEFT JOIN geolocation_zone j ON (j.zone_id = b.zone_id)
		');
		$this->SetFields('
			a.*,
			b.branch_id AS branch_id,
			d.name AS type
			');
			// b.name as branch_name, 
			// b.phone as branch_phone, 
			// b.email as branch_email
			// b.website as branch_website
			// b.postal_code as branch_postal_code
			// g.name as branch_country
			// h.name as branch_province
			// i.name as branch_region
			// j.name as branch_zone
		$this->SetWhere("a.company_id=".$_SESSION['company_id']);
		//$this->AddWhereString(" AND c.company_id = a.company_id");
		$this->SetOrder('name');
		$this->SetGroupBy("a.".$this->TableID);
		
		foreach($_POST as $Key => $Value)
		{
			$_POST[$Key] = $Value;
		}
			
		if($_POST['name']) $this->SetWhereCondition("a.name","LIKE","%".$_POST['name']."%");
		if($_POST['address']) $this->SetWhereCondition("b.address","LIKE","%".$_POST['address']."%");
		if($_POST['cuit_number']) $this->SetWhereCondition("a.cuit","=",$_POST['cuit_number']);
		
		
		// if($_POST['agent_name'])
		// {
		// 	$this->AddWhereString(" AND c.provider_id = a.provider_id");
		// }
		
		if($_POST['country']) $this->SetWhereCondition("g.name","LIKE", '%'.$_POST['country'].'%');
		if($_GET['country']) $this->SetWhereCondition("g.name","LIKE", '%'.$_GET['country'].'%');
		
		if($_REQUEST['status'])
		{
			if($_POST['status']) $this->SetWhereCondition("a.status","=", $_POST['status']);
			if($_GET['status']) $this->SetWhereCondition("a.status","=", $_GET['status']);	
		}else{
			$this->SetWhereCondition("a.status","=","A");
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
				case "address": 
					//$this->AddWhereString(" AND a.customer_id = b.customer_id");
					$Order = 'address';
					$Prefix = "b.";
				break;
				case "country": 
					//$this->AddWhereString(" AND a.customer_id = b.customer_id");
					$Order = 'name';
					$Prefix = "g.";
				break;
				default:
					$Prefix = "a.";
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
		// Basic Data
		$Image 			= $_POST['newimage'];
		$Type 			= $_POST['type'];
		$Name			= $_POST['name'];
		$CUIT			= str_replace('-','',$_POST['cuit']);
		$IVA			= $_POST['iva'];
		$GrossIncome	= $_POST['gross_income_number'];
		$International	= $_POST['international'];
		
		//VALIDATIONS
		if(!$Name) echo 'Falta Nombre';
		if(!$Type) echo 'Tipo incompleto';
		if(!$CUIT) echo 'CUIT incompleto';
		if(!$IVA) echo 'IVA incompleto';
		if(!$GrossIncome) echo 'IIBB incompleto';
		
		$Insert			= $this->execQuery('INSERT',$this->Table,'type_id,name,cuit,iva,iibb,international,creation_date,created_by,company_id',"'".$Type."','".$Name."',".$CUIT.",".$IVA.",".$GrossIncome.",'".$International."',NOW(),".$_SESSION['admin_id'].",".$_SESSION['company_id']);
		//echo $this->lastQuery();
		$NewID 		= $this->GetInsertId();
		$New 	= new Customer($NewID);
		$Dir 	= array_reverse(explode("/",$Image));
		if($Dir[1]!="default")
		{
			$Temp 	= $Image;
			$Image 	= $New->ImgGalDir().$Dir[0];
			copy($Temp,$Image);
		}
		$this->execQuery('update',$this->Table,"logo='".$Image."'",$this->TableID."=".$NewID);
		
		$New->InsertBranchInfo();
	}	
	
	public function Update()
	{
		$ID 	= $_POST['id'];
		$Edit	= new Customer($ID);
		
		// Basic Data
		$Image 			= $_POST['newimage'];
		$Type 			= $_POST['type'];
		$Name			= $_POST['name'];
		$CUIT			= str_replace('-','',$_POST['cuit']);
		$IVA			= $_POST['iva'];
		$GrossIncome	= $_POST['gross_income_number'];
		
		// CREATE NEW IMAGE IF EXISTS
		if($Image!=$Edit->Data['logo'])
		{
			if($Image!=$Edit->GetDefaultImg())
			{
				if(file_exists($Edit->GetImg()))
					unlink($Edit->GetImg());
				$Dir 	= array_reverse(explode("/",$Image));
				$Temp 	= $Image;
				$Image 	= $Edit->ImgGalDir().$Dir[0];
				copy($Temp,$Image);
			}
		}
		
		$Update		= $this->execQuery('update',$this->Table,"name='".$Name."',type_id='".$Type."',cuit=".$CUIT.",iva='".$IVA."',iibb='".$GrossIncome."',updated_by=".$_SESSION['admin_id'],$this->TableID."=".$ID);
		//echo $this->lastQuery();
		$Edit->InsertBranchInfo(1);
		
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
	}
	
	public function Search()
	{
		$this->ConfigureSearchRequest();
		echo $this->InsertSearchResults();
	}
	
	public function Newimage()
	{
		if(count($_FILES['image'])>0)
		{
			$ID	= $_POST['id'];
			if($ID)
			{
				$New = new Customer($ID);
				if($_POST['newimage']!=$New->GetImg() && file_exists($_POST['newimage']))
					unlink($_POST['newimage']);
				$TempDir= $this->ImgGalDir;
				$Name	= "customer".intval(rand()*rand()/rand());
				$Img	= new FileData($_FILES['image'],$TempDir,$Name);
				echo $Img	-> BuildImage(100,100);
			}else{	
				if($_POST['newimage']!=$this->GetDefaultImg() && file_exists($_POST['newimage']))
					unlink($_POST['newimage']);
				$TempDir= $this->ImgGalDir;
				$Name	= "customer".intval(rand()*rand()/rand());
				$Img	= new FileData($_FILES['image'],$TempDir,$Name);
				echo $Img	-> BuildImage(100,100);
			}
		}
	}
	
	public function Validate()
	{
		$Name 			= strtolower($_POST['name']);
		$ActualName 	= strtolower($_POST['actualname']);

	    if($ActualName)
	    	$TotalRegs  = $this->numRows($this->Table,'*',"name = '".$Name."' AND name <> '".$ActualName."'");
    	else
		    $TotalRegs  = $this->numRows($this->Table,'*',"name = '".$Name."'");
		if($TotalRegs>0) echo $TotalRegs;
	}
	
	public function InsertBranchInfo($DeleteInfo=0)
	{
		
		// DELETE ALL BRANCHES
		if($DeleteInfo)
		{
			$this->execQuery('DELETE','customer_agent',"customer_id=".$this->ID);
			$this->execQuery('DELETE','relation_customer_broker',"customer_id=".$this->ID);
			$this->execQuery('DELETE','customer_branch',"customer_id=".$this->ID);
		}
		
		// BRANCHES
		for($I=1;$I<=$_POST['total_branches'];$I++)
		{
			if($_POST['branch_name_'.$I])
			{
				$Branches[$I]['name']			= $_POST['branch_name_'.$I];
				$Branches[$I]['website']		= $_POST['website_'.$I];
				$Branches[$I]['fax']			= $_POST['fax_'.$I];
				$Branches[$I]['email']			= $_POST['email_'.$I];
				$Branches[$I]['phone']			= $_POST['phone_'.$I];
				
				if($I==1)
					$Branches[$I]['main_branch']			= 'Y';
				else
					$Branches[$I]['main_branch']			= 'N';
					
				// LOCATION DATA
				$Branches[$I]['lat']			= $_POST['map'.$I.'_lat'];
				$Branches[$I]['lng']			= $_POST['map'.$I.'_lng'];
				
				$Branches[$I]['address']		= $_POST['map'.$I.'_address_short'];
				if(!$Branches[$I]['address'])
					$Branches[$I]['address']	= $_POST['address_'.$I];
				$Branches[$I]['postal_code']	= $_POST['map'.$I.'_postal_code'];
				if(!$Branches[$I]['postal_code'])
					$Branches[$I]['postal_code']= $_POST['postal_code_'.$I];
				
				$Branches[$I]['zone_short']		= $_POST['map'.$I.'_zone_short'];
				$Branches[$I]['region_short']	= $_POST['map'.$I.'_region_short'];
				$Branches[$I]['province_short']	= $_POST['map'.$I.'_province_short'];
				$Branches[$I]['country_short']	= $_POST['map'.$I.'_country_short'];
				
				$Branches[$I]['zone']			= $_POST['map'.$I.'_zone'];
				$Branches[$I]['region'] 		= $_POST['map'.$I.'_region'];
				$Branches[$I]['province']		= $_POST['map'.$I.'_province'];
				$Branches[$I]['country']		= $_POST['map'.$I.'_country'];
			
				// INSERT NEW LOCATIONS
				
				// COUNTRY
				$DBQ = $this->fetchAssoc('geolocation_country','country_id as id',"name='".$Branches[$I]['country']."'");
				if($DBQ[0]['id'])
				{
					$Branches[$I]['country_id'] = $DBQ[0]['id'];
				}else{
					// INSERT NEW COUNTRY
					$this->execQuery('INSERT','geolocation_country','name,short_name',"'".$Branches[$I]['country']."','".$Branches[$I]['country_short']."'");
					$Branches[$I]['country_id'] = $this->GetInsertId();
				}
				
				//PROVINCE
				$DBQ = $this->fetchAssoc('geolocation_province','province_id as id',"country_id = ".$Branches[$I]['country_id']." AND short_name='".$Branches[$I]['province_short']."'");
				if($DBQ[0]['id'])
				{
					$Branches[$I]['province_id'] = $DBQ[0]['id'];
				}else{
					// INSERT NEW PROVINCE
					$this->execQuery('INSERT','geolocation_province','name,short_name,country_id',"'".$Branches[$I]['province']."','".$Branches[$I]['province_short']."',".$Branches[$I]['country_id']);
					$Branches[$I]['province_id'] = $this->GetInsertId();
				}
				
				//REGION
				$DBQ = $this->fetchAssoc('geolocation_region','region_id as id',"country_id = ".$Branches[$I]['country_id']." AND province_id = ".$Branches[$I]['province_id']." AND name='".$Branches[$I]['region']."'");
				if($DBQ[0]['id'])
				{
					$Branches[$I]['region_id'] = $DBQ[0]['id'];
				}else{
					// INSERT NEW REGION
					$this->execQuery('INSERT','geolocation_region','name,short_name,country_id,province_id',"'".$Branches[$I]['region']."','".$Branches[$I]['region_short']."',".$Branches[$I]['country_id'].",".$Branches[$I]['province_id']);
					$Branches[$I]['region_id'] = $this->GetInsertId();
				}
				
				//ZONE
				$DBQ = $this->fetchAssoc('geolocation_zone','zone_id as id',"country_id = ".$Branches[$I]['country_id']." AND province_id = ".$Branches[$I]['province_id']." AND region_id = ".$Branches[$I]['region_id']." AND name='".$Branches[$I]['zone']."'");
				if($DBQ[0]['id'])
				{
					$Branches[$I]['zone_id'] = $DBQ[0]['id'];
				}else{
					// INSERT NEW ZONE
					$this->execQuery('INSERT','geolocation_zone','name,short_name,country_id,province_id,region_id',"'".$Branches[$I]['zone']."','".$Branches[$I]['zone_short']."',".$Branches[$I]['country_id'].",".$Branches[$I]['province_id'].",".$Branches[$I]['region_id']);
					$Branches[$I]['zone_id'] = $this->GetInsertId();
				}
				
				$this->execQuery("INSERT","customer_branch","customer_id,country_id,province_id,region_id,zone_id,name,address,phone,email,website,fax,postal_code,main_branch,lat,lng,creation_date,created_by,company_id",$this->ID.",".$Branches[$I]['country_id'].",".$Branches[$I]['province_id'].",".$Branches[$I]['region_id'].",".$Branches[$I]['zone_id'].",'".$Branches[$I]['name']."','".$Branches[$I]['address']."','".$Branches[$I]['phone']."','".$Branches[$I]['email']."','".$Branches[$I]['website']."','".$Branches[$I]['fax']."','".$Branches[$I]['postal_code']."','".$Branches[$I]['main_branch']."',".$Branches[$I]['lat'].",".$Branches[$I]['lng'].",NOW(),".$_SESSION['admin_id'].",".$_SESSION['company_id']);
				//echo $this->lastQuery();
				$BranchID 		= $this->GetInsertId();
				
				// AGENTS DATA
				$Agents = array();
				for($A=1;$A<=$_POST['branch_total_agents_'.$I];$A++)
				{
					if($_POST['agent_name_'.$A.'_'.$I])
					{
						$AgentName		= ucfirst($_POST['agent_name_'.$A.'_'.$I]);
						$AgentCharge	= ucfirst($_POST['agent_charge_'.$A.'_'.$I]);
						$AgentEmail 	= $_POST['agent_email_'.$A.'_'.$I];
						$AgentPhone		= $_POST['agent_phone_'.$A.'_'.$I];
						$AgentExtra 	= $_POST['agent_extra_'.$A.'_'.$I];
						$Agents[]		= array('name'=>$AgentName,'charge'=>$AgentCharge,'email'=>$AgentEmail,'phone'=>$AgentPhone,'extra'=>$AgentExtra);
					}
				}
				
				// INSERT AGENTS
				$Fields="";
				foreach($Agents as $Agent)
				{
					if($Fields)
						$Fields .= "),(";
					$Fields .= $this->ID.",".$BranchID.",'".$Agent['name']."','".$Agent['charge']."','".$Agent['email']."','".$Agent['phone']."','".$Agent['extra']."',NOW(),".$_SESSION['admin_id'].",".$_SESSION['company_id'];
				}
				$this->execQuery('insert','customer_agent','customer_id,branch_id,name,charge,email,phone,extra,creation_date,created_by,company_id',$Fields);
				
				// INSERT BROKERS
				$Brokers = explode(",",$_POST['brokers_'.$I]);
				$Fields="";
				foreach($Brokers as $Broker)
				{
					if($Broker>0)
					{
						if($Fields)
							$Fields .= "),(";
						$Fields .= $this->ID.",".$BranchID.",".$Broker.",".$_SESSION['company_id'];
					}
				}
				if($Fields)
					$this->execQuery('insert','relation_customer_broker','customer_id,branch_id,broker_id,company_id',$Fields);
			}
		}
	}
	
	public function Getbranchmodal($ID=1,$Data=array())
    {
    	if($_POST['total_branches'])
    		$ID = $_POST['total_branches'];
    	if(empty($Data))
		{
			if($ID>1)
				$NewClass = "NewBranch";
			$BranchName = (intval($ID)-1);
			$Agents	= array();
		}else{
			$BranchName = $Data['name'];
			$Results	= $this->fetchAssoc('relation_customer_broker','broker_id','branch_id='.$Data['branch_id']);
			foreach($Results as $Broker)
		    {
		      $Brokers .= $Brokers? ','.$Broker['broker_id'] : $Broker['broker_id']; 
		    }
		    $Agents	= $this->fetchAssoc('customer_agent','*','branch_id='.$Data['branch_id']);
		    $A=0;
		    $AgentsHTML = "";
		    foreach($Agents as $Agent)
		    {
		    	$A++;
		    	$Charge = $Agent['charge']? '<br><span><i class="fa fa-briefcase"></i> '.$Agent['charge'].'</span>':'';
		    	$Email = $Agent['email']? '<br><span><i class="fa fa-envelope"></i> '.$Agent['email'].'</span>':'';
		    	$Phone = $Agent['phone']? '<br><span><i class="fa fa-phone"></i> '.$Agent['phone'].'</span>':'';
		    	$Extra = $Agent['extra']? '<br><span><i class="fa fa-info-circle"></i> '.$Agent['extra'].'</span>':'';
		    	$AgentsHTML .= '<div class="col-md-6 col-sm-6 col-xs-12 AgentCard"><div class="info-card-item"><input type="hidden" id="agent_name_'.$A.'_'.$ID.'" value="'.$Agent['name'].'" /><input type="hidden" id="agent_charge_'.$A.'_'.$ID.'" value="'.$Agent['charge'].'" /><input type="hidden" id="agent_email_'.$A.'_'.$ID.'" value="'.$Agent['email'].'" /><input type="hidden" id="agent_phone_'.$A.'_'.$ID.'" value="'.$Agent['phone'].'" /><input type="hidden" id="agent_extra_'.$A.'_'.$ID.'" value="'.$Agent['extra'].'" /><div class="close-btn DeleteAgent"><i class="fa fa-times"></i></div><span><i class="fa fa-user"></i> <b>'.$Agent['name'].'</b></span>'.$Charge.$Phone.$Email.$Extra.'</div></div>';
		    }
		    // if($A==0)
		    // {
		    // 	$NoAgents = '<span id="empty_agent_'.$ID.'" class="Info-Card-Empty info-card-empty">No hay representantes ingresados</span>';
		    // }
		}
		
		if(!$A)
	    {
	    	$NoAgents = '<span id="empty_agent_'.$ID.'" class="Info-Card-Empty info-card-empty">No hay representantes ingresados</span>';
	    }
        
    	
    	if($ID>1)
    	{
    		$BranchNameHTML = '<div class="row form-group inline-form-custom">
						<div class="col-xs-12 col-sm-4">
                            Nombre de la Sucursal:
                        </div>
                        <div class="col-xs-12 col-sm-8">
                            '.insertElement('text','branch_name_'.$ID,$BranchName,'form-control branchname','branch="'.$ID.'" placeholder="Nombre" validateEmpty="Ingrese un nombre de sucursal"').'
                        </div>
                        </div>';
    	}else{
    		$BranchName = 'Central';
    		$BranchNameHTML = insertElement('hidden','branch_name_'.$ID,$BranchName);
    	}
    	
        $HTML .= '
        <!-- //// BEGIN BRANCH MODAL '.$ID.' //// -->
        <div id="branch_modal_'.$ID.'" class="modal fade '.$NewClass.'" role="dialog" style="opacity:1!important;">
            <div class="modal-dialog" style="top:12em;">
                
                <div class="modal-content">
                    <div class="modal-header">
                        <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
                        <h4 class="modal-title" id="BranchTitle'.$ID.'">Editar Sucursal '.$BranchName.'</i></h4>
                    </div>
                    <div class="modal-body" style="max-height:35em;overflow-y:scroll;">
                    <form id="branch_form_'.$ID.'" name="branch_form_'.$ID.'">
                            '.$BranchNameHTML.'
                        <h4 class="subTitleB"><i class="fa fa-globe"></i> Geolocalizaci&oacute;n</h4>
                        <div class="row form-group inline-form-custom">
                            <div class="col-xs-12 col-sm-6">
                                <span class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                    '.insertElement('text','address_'.$ID,$Data['address'],'form-control','disabled="disabled" placeholder="Direcci&oacute;n" validateMinLength="4///La direcci&oacute;n debe contener 4 caracteres como m&iacute;nimo."').'
                                </span>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <span class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-bookmark"></i></span>
                                    '.insertElement('text','postal_code_'.$ID,$Data['postal_code'],'form-control','disabled="disabled" placeholder="C&oacute;digo Postal" validateMinLength="4///La direcci&oacute;n debe contener 4 caracteres como m&iacute;nimo."').'
                                </span>
                            </div>
                        </div>
                        <div class="row form-group inline-form-custom">
                            <div class="col-xs-12 col-sm-12 MapWrapper">
                                '.InsertAutolocationMap($ID,$Data).'
                            </div>
                        </div>
                        
                        <br>
                        <h4 class="subTitleB"><i class="fa fa-phone"></i> Datos de contacto</h4>
                        <div class="row form-group inline-form-custom">
                            <div class="col-sm-6 col-xs-12">
                                <span class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                    '.insertElement('text','email_'.$ID,$Data['email'],'form-control',' placeholder="Email"').'
                                </span>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <span class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                    '.insertElement('text','phone_'.$ID,$Data['phone'],'form-control',' placeholder="Tel&eacute;fono"').'
                                </span>
                            </div>
                        </div>
                        <div class="row form-group inline-form-custom">
                            <div class="col-sm-6 col-xs-12">
                                <span class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-desktop"></i></span>
                                    '.insertElement('text','website_'.$ID,$Data['website'],'form-control',' placeholder="Sitio Web"').'
                                </span>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <span class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-fax"></i></span>
                                    '.insertElement('text','fax_'.$ID,$Data['fax'],'form-control',' placeholder="Fax"').'
                                </span>
                            </div>
                        </div>
                        </form>
                        <br>
                        <div class="row">
                            <div class="col-md-12 info-card">
                                <h4 class="subTitleB"><i class="fa fa-male"></i> Representantes</h4>
                                '.$NoAgents.'
                                <div id="agent_list_'.$ID.'" branch="'.$ID.'" class="row">
                                '.$AgentsHTML.'
                                </div>
                                <div class="row txC">
                                    <button id="agent_new_'.$ID.'" branch="'.$ID.'" type="button" class="btn btn-warning Info-Card-Form-Btn agent_new"><i class="fa fa-plus"></i> Agregar un representante</button>
                                </div>
                                '.insertElement("hidden","branch_total_agents_".$ID,count($Agents),'','branch="'.$ID.'"').'
                                <div id="agent_form_'.$ID.'" class="Info-Card-Form Hidden">
                                    <form id="new_agent_form_'.$ID.'">
                                        <div class="info-card-arrow">
                                            <div class="arrow-up"></div>
                                        </div>
                                        <div class="info-card-form animated fadeIn">
                                            <div class="row form-group inline-form-custom">
                                                <div class="col-xs-12 col-sm-6">
                                                    <span class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                        '.insertElement('text','agentname_'.$ID,'','form-control',' placeholder="Nombre y Apellido" validateEmpty="Ingrese un nombre"').'
                                                    </span>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <span class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
                                                        '.insertElement('text','agentcharge_'.$ID,'','form-control',' placeholder="Cargo"').'
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row form-group inline-form-custom">
                                                <div class="col-xs-12 col-sm-6">
                                                    <span class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                        '.insertElement('text','agentemail_'.$ID,'','form-control',' placeholder="Email" validateEmail="Ingrese un email v&aacute;lido."').'
                                                    </span>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <span class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                        '.insertElement('text','agentphone_'.$ID,'','form-control',' placeholder="Tel&eacute;fono"').'
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row form-group inline-form-custom">
                                                <div class="col-xs-12 col-sm-12">
                                                    <span class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-info-circle"></i></span>
                                                        '.insertElement('textarea','agentextra_'.$ID,'','form-control','rows="1" placeholder="Informaci&oacute;n Extra"').'
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row txC">
                                                <button id="agent_add_'.$ID.'" branch="'.$ID.'" type="button" class="Info-Card-Form-Done btn btnGreen agent_add"><i class="fa fa-check"></i> Agregar</button>
                                                <button id="agent_cancel_'.$ID.'" branch="'.$ID.'" type="button" class="Info-Card-Form-Done btn btnRed agent_cancel"><i class="fa fa-times"></i> Cancelar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <br>
                        <h4 class="subTitleB"><i class="fa fa-briefcase"></i> Corredores</h4>
                        <div id="agent_list_'.$ID.'" branch="'.$ID.'" class="row">
                            <div class="col-xs-12">
                                '.insertElement('multiple','select_broker_'.$ID,$Brokers,'form-control select2 selectTags BrokerSelect','style="width:100%;" branch="'.$ID.'"',$this->fetchAssoc('admin_user',"admin_id,CONCAT(first_name,' ',last_name) as name","status='A' AND profile_id = 361",'name'),'0','Seleccione una Opci&oacute;n').'
                                '.insertElement('hidden','brokers_'.$ID,$Brokers).'
                            </div>
                        </div>
                    
                    </div>
                    <div class="modal-footer txC" style="background-color:#6f69bd!important;">
						<button type="button" name="button" class="btn btn-success btnBlue SaveBranchEdition" id="SaveBranchEdition'.$ID.'" data-dismiss="modal" branch="'.$ID.'">Guardar</button>
						<button type="button" name="button" class="btn btn-success btnRed CancelBranchEdition" id="CancelBranchEdition'.$ID.'" data-dismiss="modal" branch="'.$ID.'">Cancelar</button>
					</div>
                </div>
            </div>
        </div>
        <!-- //// END BRANCH MODAL '.$ID.' //// -->
        ';
    
        echo $HTML;
    }
}
?>
