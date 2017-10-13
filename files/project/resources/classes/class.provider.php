<?php

class Provider 
{
	use SearchList;
	
	var	$ID;
	var $Data;
	var $ImgGalDir		= '../../../skin/images/providers/';
	var $Customers 		= array();
	var $Parent 		= array();
	var $Table			= "provider";
	var $TableID		= "provider_id";
	
	const DEFAULTIMG	= "../../../skin/images/providers/default/provider.png";

	public function __construct($ID=0)
	{
		
		if($ID!=0)
		{
			$Data = Core::Select($this->Table,"*",$this->TableID."=".$ID);
			$this->Data = $Data[0];
			$this->ID = $ID;
			if($Data['country_id'])
			{
				$DBQ = Core::Select('geolocation_country','*','country_id='.$Data['country_id']);
				$this->Data['country'] = $DBQ[0]['name'];
				$this->Data['country_short'] = $DBQ[0]['name_short'];
				
			}
			if($Data['province_id'])
			{
				$DBQ = Core::Select('geolocation_province','*','province_id='.$Data['province_id']);
				$this->Data['province'] = $DBQ[0]['name'];
				$this->Data['province_short'] = $DBQ[0]['name_short'];
				
			}
			if($Data['region_id'])
			{
				$DBQ = Core::Select('geolocation_region','*','region_id='.$Data['region_id']);
				$this->Data['region'] = $DBQ[0]['name'];
				$this->Data['region_short'] = $DBQ[0]['name_short'];
				
			}
			if($Data['zone_id'])
			{
				$DBQ = Core::Select('geolocation_zone','*','zone_id='.$Data['zone_id']);
				$this->Data['zone'] = $DBQ[0]['name'];
				$this->Data['zone_short'] = $DBQ[0]['name_short'];
				
			}
		}
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
		//echo $this->LastQuery();
		for($i=0;$i<count($Rows);$i++)
		{
			$Row	=	new Provider($Rows[$i][$this->TableID]);
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
				$Actions	.= '<a class="deleteElement" process="'.PROCESS.'" id="delete_'.$Row->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
			}else{
				$Actions	.= '<a class="activateElement" process="'.PROCESS.'" id="activate_'.$Row->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			}
			$Actions	.= '</span>';
			switch(strtolower($Mode))
			{
				case "list":
					$RowBackground = $i % 2 == 0? '':' listRow2 ';
					$Regs	.= '<div class="row listRow'.$RowBackground.$Restrict.'" id="row_'.$Row->ID.'" title="'.$Row->Data['name'].'">
									<div class="col-lg-3 col-md-3 col-sm-10 col-xs-10">
										<div class="listRowInner">
											<img class="img-circle" src="'.$Row->GetImg().'" alt="'.$Row->Data['name'].'">
											<span class="listTextStrong">'.$Row->Data['name'].'</span>
											<span class="smallDetails">CUIT: '.$Row->Data['cuit'].'</span>
										</div>
									</div>
									<div class="col-lg-2 col-md-3 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="smallTitle">Direcci&oacute;n</span>
											<span class="emailTextResp">'.$Row->Data['address'].'</span>
										</div>
									</div>
									<div class="col-lg-3 col-md-2 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="smallTitle">Raz&oacute;n Social</span>
											<span class="listTextStrong"><span class="label label-primary">'.ucfirst($Row->Data['corporate_name']).'</span></span>
										</div>
									</div>
									<div class="col-lg-1 col-md-1 col-sm-1 hideMobile990"></div>
									<div class="animated DetailedInformation Hidden col-md-11">
										<br>a
										<br>a
										<br>aa
										<br>a
										<br>as
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
						                <p>'.ucfirst($Row->Data['corporate_name']).'</p>
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
		return '<!-- Name -->
          <div class="input-group">
            <span class="input-group-addon order-arrows sort-activated" order="name" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','name','','form-control','placeholder="Nombre"').'
          </div>
          <!-- Corporate Name -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="corporate_name" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','corporate_name','','form-control','placeholder="Raz&oacute;n Social"').'
          </div>
          <!-- Address -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="address" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','address','','form-control','placeholder="Direcci&oacute;n"').'
          </div>
          <!-- Phone -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="phone" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','phone','','form-control','placeholder="Tel&eacute;fono"').'
          </div>
          <!-- Website -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="website" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','website','','form-control','placeholder="Sitio Web"').'
          </div>
          <!-- CUIT -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="cuit" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','cuit','','form-control','placeholder="CUIT"').'
          </div>
          <!-- Agent -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="agent_name" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','agent_name','','form-control','placeholder="Responsable"').'
          </div>';
	}
	
	protected function InsertSearchButtons()
	{
		return '<!-- New Button -->
		    	<a href="new.php"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-user-plus"></i> Nuevo Proveedor</button></a>
		    	<!-- /New Button -->';
	}
	
	public function ConfigureSearchRequest()
	{
		$this->SetTable('provider c LEFT JOIN provider_agent a ON (a.provider_id)');
		$this->SetFields('c.*,a.name as agent_name, a.phone as agent_phone, a.email as agent_email, a.charge as agent_charge');
		$this->SetWhere("c.organization_id=".$_SESSION['organization_id']);
		//$this->AddWhereString(" AND c.organization_id = a.organization_id");
		$this->SetOrder('name');
		$this->SetGroupBy("c.".$this->TableID);
		
		foreach($_POST as $Key => $Value)
		{
			$_POST[$Key] = $Value;
		}
			
		if($_POST['name']) $this->SetWhereCondition("c.name","LIKE","%".$_POST['name']."%");
		if($_POST['corporate_name']) $this->SetWhereCondition("c.corporate_name","LIKE","%".$_POST['corporate_name']."%");
		if($_POST['address']) $this->SetWhereCondition("c.address","LIKE","%".$_POST['address']."%");
		if($_POST['cuit']) $this->SetWhereCondition("c.cuit","=",$_POST['cuit']);
		if($_POST['website']) $this->SetWhereCondition("c.website","LIKE","%".$_POST['website']."%");
		if($_POST['agent_name']) $this->SetWhereCondition("a.name","LIKE","%".$_POST['agent_name']."%");
		//if($_POST['agent_email']) $this->SetWhereCondition("a.email","LIKE","%".$_POST['agent_email']."%");
		//if($_POST['agent_charge']) $this->SetWhereCondition("a.charge","LIKE", "%".$_POST['agent_charge']."%");
		if($_POST['phone']) $this->AddWhereString(" AND (c.phone LIKE '%".$_POST['phone']."%' OR a.phone LIKE '%".$_POST['phone']."%')");
		//if($_POST['parent']) $this->SetWhereCondition("c.parent_id","=",$_POST['parent']);
		if($_POST['agent_name'])
		{
			$this->AddWhereString(" AND c.provider_id = a.provider_id");
		}
		if($_REQUEST['status'])
		{
			if($_POST['status']) $this->SetWhereCondition("c.status","=", $_POST['status']);
			if($_GET['status']) $this->SetWhereCondition("c.status","=", $_GET['status']);	
		}else{
			$this->SetWhereCondition("c.status","=","A");
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
					$this->AddWhereString(" AND c.provider_id = a.provider_id");
					$Order = 'name';
					$Prefix = "a.";
				break;
				default:
					$Prefix = "c.";
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
		
		// LOCATION DATA
		$Lat			= $_POST['map1_lat'];
		$Lng			= $_POST['map1_lng'];
		
		$Address		= $_POST['map1_address_short'];
		if(!$Address)
			$Address	= $_POST['address_1'];
		$PostalCode		= $_POST['map1_postal_code'];
		if(!$PostalCode)
			$PostalCode	= $_POST['postal_code_1'];
		
		$ZoneShort		= $_POST['map1_zone_short'];
		$RegionShort	= $_POST['map1_region_short'];
		$ProvinceShort	= $_POST['map1_province_short'];
		$CountryShort	= $_POST['map_country_short'];
		
		$Zone			= $_POST['map1_zone'];
		$Region 		= $_POST['map1_region'];
		$Province		= $_POST['map1_province'];
		$Country		= $_POST['map1_country'];
		
		// INSERT LOCATIONS
		$CountryID	= Geolocation::InsertCountry($Country,$CountryShort,$this);
		$ProvinceID = Geolocation::InsertProvince($Province,$ProvinceShort,$this);
		$RegionID	= Geolocation::InsertRegion($Region,$RegionShort,$this);
		$ZoneID 	= Geolocation::InsertZone($Zone,$ZoneShort,$this);
		
		
		// Basic Data
		$Image 			= $_POST['newimage'];
		$Type 			= $_POST['type'];
		$Name			= $_POST['name'];
		//$CorporateName	= ucfirst($_POST['corporate_name']);
		$CUIT			= str_replace('-','',$_POST['cuit']);
		$IVA			= $_POST['iva'];
		$GrossIncome	= $_POST['gross_income_number'];
		$Email 			= strtolower($_POST['email']);
		$Phone			= $_POST['phone'];
		$Website 		= strtolower($_POST['website']);
		$Fax			= $_POST['fax'];
		
		//VALIDATIONS
		if(!$Name) echo 'Falta Nombre';
		if(!$Type) echo 'Tipo incompleto';
		if(!$CUIT) echo 'CUIT incompleto';
		if(!$IVA) echo 'IVA incompleto';
		// if(!$GrossIncome) echo 'IIBB incompleto';
		
		$NewID			= Core::Insert($this->Table,'type,name,cuit,iva,gross_income_tax,country_id,province_id,region_id,zone_id,address,postal_code,lat,lng,email,phone,website,fax,creation_date,created_by,organization_id',"'".$Type."','".$Name."',".$CUIT.",'".$IVA."',".$GrossIncome.",".$CountryID.",".$ProvinceID.",".$RegionID.",".$ZoneID.",'".$Address."','".$PostalCode."',".$Lat.",".$Lng.",'".$Email."','".$Phone."','".$Website."','".$Fax."',NOW(),".$_SESSION['user_id'].",".$_SESSION['organization_id']);
		//echo $this->LastQuery();
		Tax::SetIVA($CUIT,$IVA);
		$New 	= new Provider($NewID);
		$Dir 	= array_reverse(explode("/",$Image));
		if($Dir[1]!="default")
		{
			$Temp 	= $Image;
			$Image 	= $New->ImgGalDir().$Dir[0];
			copy($Temp,$Image);
		}
		Core::Update($this->Table,"logo='".$Image."'",$this->TableID."=".$NewID);
		
		// AGENTS DATA
		$Agents = array();
		for($i=1;$i<=$_POST['total_agents'];$i++)
		{
			if($_POST['agent_name_'.$i])
				$Agents[] = array('name'=>ucfirst($_POST['agent_name_'.$i]),'charge'=>ucfirst($_POST['agent_charge_'.$i]),'email'=>$_POST['agent_email_'.$i],'phone'=>$_POST['agent_phone_'.$i],'extra'=>$_POST['agent_extra_'.$i]);
		}
		
		// INSERT AGENTS
		foreach($Agents as $Agent)
		{
			if($Fields)
				$Fields .= "),(";
			$Fields .= $NewID.",'".$Agent['name']."','".$Agent['charge']."','".$Agent['email']."','".$Agent['phone']."','".$Agent['extra']."'";
		}
		Core::Insert('provider_agent','provider_id,name,charge,email,phone,extra',$Fields);
		
		
	}	
	
	public function Update()
	{
		$ID 	= $_POST['id'];
		$Edit	= new Provider($ID);
		
		// LOCATION DATA
		$Lat			= $_POST['map1_lat'];
		$Lng			= $_POST['map1_lng'];
		
		$Address		= $_POST['map1_address_short'];
		if(!$Address)
			$Address	= $_POST['address1'];
		$PostalCode		= $_POST['map1_postal_code'];
		if(!$PostalCode)
			$PostalCode	= $_POST['postal_code1'];
		
		$ZoneShort		= $_POST['map1_zone_short'];
		$RegionShort	= $_POST['map1_region_short'];
		$ProvinceShort	= $_POST['map1_province_short'];
		$CountryShort	= $_POST['map1_country_short'];
		
		$Zone			= $_POST['map1_zone'];
		$Region 		= $_POST['map1_region'];
		$Province		= $_POST['map1_province'];
		$Country		= $_POST['map1_country'];
		
		//echo $Address." asdasd";
		
		// INSERT LOCATIONS
		$CountryID	= Geolocation::InsertCountry($Country,$CountryShort,$this);
		$ProvinceID = Geolocation::InsertProvince($Province,$ProvinceShort,$this);
		$RegionID	= Geolocation::InsertRegion($Region,$RegionShort,$this);
		$ZoneID 	= Geolocation::InsertZone($Zone,$ZoneShort,$this);
		
		
		// Basic Data
		$Image 			= $_POST['newimage'];
		$Type 			= $_POST['type'];
		$Name			= $_POST['name'];
		$CUIT			= str_replace('-','',$_POST['cuit']);
		$IVA			= $_POST['iva'];
		$GrossIncome	= $_POST['gross_income_number'];
		$Email 			= strtolower($_POST['email']);
		$Phone			= $_POST['phone'];
		$Website 		= strtolower($_POST['website']);
		$Fax			= $_POST['fax'];
		
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
		
		$Update		= Core::Update('provider',"name='".$Name."',postal_code='".$PostalCode."',address='".$Address."',cuit=".$CUIT.",iva='".$IVA."',gross_income_tax='".$GrossIncome."',email='".$Email."',fax='".$Fax."',phone='".$Phone."',website='".$Website."',country_id=".$CountryID.",province_id='".$ProvinceID."',region_id=".$RegionID.",zone_id='".$ZoneID."',lat=".$Lat.",lng=".$Lng.",logo='".$Image."',updated_by=".$_SESSION['user_id'],"provider_id=".$ID);
		//echo $this->LastQuery();
		
		Tax::SetIVA($CUIT,$IVA);		
		
		// PROCESS AGENTS
		$Agents = array();
		for($i=1;$i<=$_POST['total_agents'];$i++)
		{
			if($_POST['agent_name_'.$i])
				$Agents[] = array('name'=>ucfirst($_POST['agent_name_'.$i]),'charge'=>ucfirst($_POST['agent_charge_'.$i]),'email'=>$_POST['agent_email_'.$i],'phone'=>$_POST['agent_phone_'.$i],'extra'=>$_POST['agent_extra_'.$i]);
		}
		
		// DELETE OLD AGENTS
		Core::Delete('provider_agent',"provider_id = ".$ID);
		
		// INSERT NEW AGENTS
		foreach($Agents as $Agent)
		{
			if($Fields)
				$Fields .= "),(";
			$Fields .= $ID.",'".$Agent['name']."','".$Agent['charge']."','".$Agent['email']."','".$Agent['phone']."','".$Agent['extra']."'";
		}
		Core::Insert('provider_agent','provider_id,name,charge,email,phone,extra',$Fields);
	}
	
	public function Activate()
	{
		$ID	= $_POST['id'];
		Core::Update($this->Table,"status = 'A'",$this->TableID."=".$ID);
	}
	
	public function Delete()
	{
		$ID	= $_POST['id'];
		Core::Update($this->Table,"status = 'I'",$this->TableID."=".$ID);
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
				$New = new Provider($ID);
				if($_POST['newimage']!=$New->GetImg() && file_exists($_POST['newimage']))
					unlink($_POST['newimage']);
				$TempDir= $this->ImgGalDir;
				$Name	= "provider".intval(rand()*rand()/rand());
				$Img	= new CoreFileData($_FILES['image'],$TempDir,$Name);
				echo $Img	-> BuildImage(100,100);
			}else{	
				if($_POST['newimage']!=$this->GetDefaultImg() && file_exists($_POST['newimage']))
					unlink($_POST['newimage']);
				$TempDir= $this->ImgGalDir;
				$Name	= "provider".intval(rand()*rand()/rand());
				$Img	= new CoreFileData($_FILES['image'],$TempDir,$Name);
				echo $Img	-> BuildImage(100,100);
			}
		}
	}
	
	public function Validate()
	{
		$User 			= strtolower($_POST['name']);
		$ActualUser 	= strtolower($_POST['actualname']);

	    if($ActualUser)
	    	$TotalRegs  = Core::NumRows($this->Table,'*',"name = '".$User."' AND name <> '".$ActualUser."'");
    	else
		    $TotalRegs  = Core::NumRows($this->Table,'*',"name = '".$User."'");
		if($TotalRegs>0) echo $TotalRegs;
	}
	
	// public function Validate_email()
	// {
	// 	$Email 			= strtolower(utf8_encode($_POST['email']));
	// 	$ActualEmail 	= strtolower(utf8_encode($_POST['actualemail']));

	//     if($ActualEmail)
	//     	$TotalRegs  = Core::NumRows('core_user','*',"email = '".$Email."' AND email<> '".$ActualEmail."'");
 //   	else
	// 	    $TotalRegs  = Core::NumRows('core_user','*',"email = '".$Email."'");
	// 	if($TotalRegs>0) echo $TotalRegs;
	// }
}
?>
