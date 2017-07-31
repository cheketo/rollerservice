<?php 
class Geolocation
{
    public static function InsertLocation($Name,$NameShort,$Table,$TableID)
    {
        if($Name || $NameShort)
        {
            if(!$Name)
                $Name       = $Name? $Name : $NameShort;
            if(!$ShortName)
                $NameShort  = $NameShort? $NameShort : $Name;
            $Data = Core::Select($Table,$TableID.' as id',"name='".$Name."' OR short_name='".$Name."' OR name='".$NameShort."' OR short_name='".$NameShort."'");
            if($Data[0]['id'])
            {
            	$ID = $Data[0]['id'];
            }else{
            	$ID = Core::Insert($Table,'name,short_name',"'".$Name."','".$NameShort."'");
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
    
    public static function InsertProvince($Name,$NameShort="")
    {
        return self::InsertLocation($Name,$NameShort,'core_province','province_id');
    }
    
    public static function InsertRegion($Name,$NameShort="")
    {
        return self::InsertLocation($Name,$NameShort,'core_region','region_id');
    }
    
    public static function InsertZone($Name,$NameShort="")
    {
        return self::InsertLocation($Name,$NameShort,'core_zone','zone_id');
    }
    
    public static function GetGeolocationData($Obj)
    {
        if($Obj->Data['country_id'])
		{
			$Data = Core::Select("core_country",'*','country_id='.$Obj->Data['country_id']);
			$Obj->Data['country'] = $Data[0]['name'];
			$Obj->Data['country_short'] = $Data[0]['name_short'];	
		}
		if($Obj->Data['province_id'])
		{
			$Data = Core::Select("core_province",'*','province_id='.$Obj->Data['province_id']);
			$Obj->Data['province'] = $Data[0]['name'];
			$Obj->Data['province_short'] = $Data[0]['name_short'];	
		}
		if($Obj->Data['region_id'])
		{
			$Data = Core::Select("core_region",'*','region_id='.$Obj->Data['region_id']);
			$Obj->Data['region'] = $Data[0]['name'];
			$Obj->Data['region_short'] = $Data[0]['name_short'];	
		}
		if($Obj->Data['zone_id'])
		{
			$Data = Core::Select("core_zone",'*','zone_id='.$Obj->Data['zone_id']);
			$Obj->Data['zone'] = $Data[0]['name'];
			$Obj->Data['zone_short'] = $Data[0]['name_short'];	
		}
    }
}
?>