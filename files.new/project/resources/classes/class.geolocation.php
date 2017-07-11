<?php 
class Geolocation
{
    public static function InsertLocation($Name,$NameShort,$Object,$Table,$TableID)
    {
        if($Name)
        {
            $NameShort = $NameShort? $NameShort : $Name;
            $Data = Core::Select($Table,$TableID.' as id',"name='".$Name."' OR short_name='".$Name."'");
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
    
    public static function InsertCountry($Name,$NameShort="",$Object=0)
    {
        return self::InsertLocation($Name,$NameShort,$Object,'geolocation_country','country_id');
    }
    
    public static function InsertProvince($Name,$NameShort="",$Object=0)
    {
        return self::InsertLocation($Name,$NameShort,$Object,'geolocation_province','province_id');
    }
    
    public static function InsertRegion($Name,$NameShort="",$Object=0)
    {
        return self::InsertLocation($Name,$NameShort,$Object,'geolocation_region','region_id');
    }
    
    public static function InsertZone($Name,$NameShort="",$Object=0)
    {
        return self::InsertLocation($Name,$NameShort,$Object,'geolocation_zone','zone_id');
    }
    
    public static function GetGeolocationData($Obj)
    {
        if($Obj->Data['country_id'])
		{
			$Data = Core::Select("geolocation_country",'*','country_id='.$Obj->Data['country_id']);
			$Obj->Data['country'] = $Data[0]['name'];
			$Obj->Data['country_short'] = $Data[0]['name_short'];	
		}
		if($Obj->Data['province_id'])
		{
			$Data = Core::Select("geolocation_province",'*','province_id='.$Obj->Data['province_id']);
			$Obj->Data['province'] = $Data[0]['name'];
			$Obj->Data['province_short'] = $Data[0]['name_short'];	
		}
		if($Obj->Data['region_id'])
		{
			$Data = Core::Select("geolocation_region",'*','region_id='.$Obj->Data['region_id']);
			$Obj->Data['region'] = $Data[0]['name'];
			$Obj->Data['region_short'] = $Data[0]['name_short'];	
		}
		if($Obj->Data['zone_id'])
		{
			$Data = Core::Select("geolocation_zone",'*','zone_id='.$Obj->Data['zone_id']);
			$Obj->Data['zone'] = $Data[0]['name'];
			$Obj->Data['zone_short'] = $Data[0]['name_short'];	
		}
    }
}
?>