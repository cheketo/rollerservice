<?php 
class Geolocation
{
    public static function InsertLocation($Name,$NameShort,$Table,$TableID,$CountryID=0,$ProvinceID=0,$RegionID=0)
    {
        if($Name || $NameShort)
        {
            if(!$Name)
                $Name       = $Name? $Name : $NameShort;
            if(!$ShortName)
                $NameShort  = $NameShort? $NameShort : $Name;
            if($CountryID>0)
            {
                $ParentField = " AND country_id=".$CountryID;
                if($ProvinceID>0) $ParentField .= " AND province_id=".$ProvinceID;
            }
            $Data = Core::Select($Table,$TableID.' as id',"(name='".$Name."' OR short_name='".$Name."' OR name='".$NameShort."' OR short_name='".$NameShort."')".$ParentField)[0];
            if($Data['id'])
            {
            	$ID = $Data['id'];
            }else{
                if($CountryID>0)
                {
                    $Fields = ",country_id";
                    $Data   = ",".$CountryID;
                    if($ProvinceID>0)
                    {
                        $Fields .= ",province_id";
                        $Data   .= ",".$ProvinceID;
                        if($RegionID>0)
                        {
                            $Fields .= ",region_id";
                            $Data   .= ",".$RegionID;
                        }
                    }
                }
            	$ID = Core::Insert($Table,'name,short_name'.$Fields,"'".$Name."','".$NameShort."'".$Data);
            }
            return $ID;
        }else{
            return 0;
        }
    }
    
    public static function InsertCountry($Name,$NameShort="")
    {
        return self::InsertLocation($Name,$NameShort,'core_country','country_id');
    }
    
    public static function InsertProvince($Name,$NameShort="",$CountryID)
    {
        return self::InsertLocation($Name,$NameShort,'core_province','province_id',$CountryID);
    }
    
    public static function InsertRegion($Name,$NameShort="",$CountryID,$ProvinceID)
    {
        return self::InsertLocation($Name,$NameShort,'core_region','region_id',$CountryID,$ProvinceID);
    }
    
    public static function InsertZone($Name,$NameShort="",$CountryID,$ProvinceID,$RegionID)
    {
        return self::InsertLocation($Name,$NameShort,'core_zone','zone_id',$CountryID,$ProvinceID,$RegionID);
    }
    
    public static function GetGeolocationData($Obj)
    {
        if($Obj->Data['country_id'])
		{
			$Data = Core::Select("core_country",'*','country_id='.$Obj->Data['country_id'])[0];
			$Obj->Data['country'] = $Data['name'];
			$Obj->Data['country_short'] = $Data['name_short'];	
		}
		if($Obj->Data['province_id'])
		{
			$Data = Core::Select("core_province",'*','province_id='.$Obj->Data['province_id'])[0];
			$Obj->Data['province'] = $Data['name'];
			$Obj->Data['province_short'] = $Data['name_short'];	
		}
		if($Obj->Data['region_id'])
		{
			$Data = Core::Select("core_region",'*','region_id='.$Obj->Data['region_id'])[0];
			$Obj->Data['region'] = $Data['name'];
			$Obj->Data['region_short'] = $Data['name_short'];	
		}
		if($Obj->Data['zone_id'])
		{
			$Data = Core::Select("core_zone",'*','zone_id='.$Obj->Data['zone_id'])[0];
			$Obj->Data['zone'] = $Data['name'];
			$Obj->Data['zone_short'] = $Data['name_short'];	
		}
    }
}
?>