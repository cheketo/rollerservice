<?php

class ConfigurationOrganization
{
	use SearchList;
	
	var	$ID;
	var $Data;
	var $ImgGalDir		= '../../../skin/images/configuration/organization/';
	var $Table			= "configuration_organization";
	var $TableID		= "configuration_id";
	
	const DEFAULTIMG	= "../../../skin/images/configuration/organization/default.png";

	public function __construct($ID=0)
	{
		
		if($ID!=0)
		{
			$Data = Core::Select($this->Table,"*",$this->TableID."=".$ID);
			$this->Data = $Data[0];
			$this->ID = $ID;
			Geolocation::GetGeolocationData($this);
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
		$Dir = $this->ImgGalDir;
		if(!file_exists($Dir))
			mkdir($Dir);
		return $Dir;
	}
	
	public function GetData()
	{
		return $this->Data;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function Update()
	{
		$ID 	= $_POST['id'];
		$Edit	= new ConfigurationOrganization($ID);
		
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
		
		// INSERT LOCATIONS
		$CountryID	= Geolocation::InsertCountry($Country,$CountryShort,$this);
		$ProvinceID = Geolocation::InsertProvince($Province,$ProvinceShort,$this);
		$RegionID	= Geolocation::InsertRegion($Region,$RegionShort,$this);
		$ZoneID 	= Geolocation::InsertZone($Zone,$ZoneShort,$this);
		
		// Basic Data
		$Image 			= $_POST['newimage'];
		$Type 			= $_POST['type'];
		$Name			= $_POST['name'];
		$CorporateName	= $_POST['corporate_name'];
		$CUIT			= str_replace('-','',$_POST['cuit']);
		$IVA			= $_POST['iva'];
		$GrossIncome	= $_POST['gross_income_number'];
		$Email 			= strtolower($_POST['email']);
		$Phone			= $_POST['phone'];
		$Website 		= strtolower($_POST['website']);
		$Fax			= $_POST['fax'];
		
		//VALIDATIONS
		if(!$Name) echo 'Falta Nombre';
		//if(!$CorporateName) echo 'Falta Nombre Corporativo';
		//if(!$Type) echo 'Tipo incompleto';
		if(!$CUIT) echo 'CUIT incompleto';
		if(!$IVA) echo 'IVA incompleto';
		if(!$GrossIncome) echo 'IIBB incompleto';
		
		// CREATE NEW IMAGE IF EXISTS
		if($Image!=$Edit->Data['logo'])
		{
			//CREATE NEW IMAGE IF EXISTS
			$Dir 	= array_reverse(explode("/",$Image));
			if($Dir[0]!="default.png")
			{
				if($Edit->GetImg()!=$Edit->GetDefaultImg() && file_exists($Edit->GetImg()))
					unlink($Edit->GetImg());
				$Temp 	= $Image;
				$Image 	= $Edit->ImgGalDir().$Dir[0];
				copy($Temp,$Image);
				if(file_exists($Temp)) unlink($Temp);
			}
		}
		
		$Update		= Core::Update($this->Table,"name='".$Name."',corporate_name='".$CorporateName."',postal_code='".$PostalCode."',address='".$Address."',cuit=".$CUIT.",iva='".$IVA."',gross_income_tax='".$GrossIncome."',email='".$Email."',fax='".$Fax."',phone='".$Phone."',website='".$Website."',country_id=".$CountryID.",province_id='".$ProvinceID."',region_id=".$RegionID.",zone_id='".$ZoneID."',lat=".$Lat.",lng=".$Lng.",logo='".$Image."'",$this->TableID."=1");
		//echo $this->LastQuery();
	}
	
	public function Newimage()
	{
		if(count($_FILES['image'])>0)
		{
			$ID	= $_POST['id'];
			if($ID)
			{
				$New = new ConfigurationOrganization($ID);
				if($_POST['newimage']!=$New->GetImg() && file_exists($_POST['newimage']))
					unlink($_POST['newimage']);
				$TempDir= $this->ImgGalDir;
				$Name	= "company";
				$Img	= new CoreFileData($_FILES['image'],$TempDir,$Name);
				echo $Img	-> BuildImage(100,100);
			}
			
		}
	}
	
	
}
?>
