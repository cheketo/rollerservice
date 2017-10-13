<?php
    trait CoreCrud
    {
        var $ID;
        var $Data = array();
        
        public function Insert($Fields,$Values)
        {
            return Core::Insert(self::TABLE,$Fields,$Values);
        }
        
        public function Update($Values)
        {
            return Core::Update(self::TABLE,$Values,self::TABLE_ID."=".$_POST['id']);
        }
        
        public function Delete()
        {
            return Core::Update(self::TABLE,"status='I'",self::TABLE_ID."=".$_POST['id']);
        }
        
        public function Activate()
        {
            return Core::Update(self::TABLE,"status='A'",self::TABLE_ID."=".$_POST['id']);
        }
        
        public function FastDelete($Table)
        {
            if($this->ID)
                return Core::Delete($Table,self::TABLE_ID."=".$this->ID);
            else
                return strtoupper(self::TABLE_ID).' MISSING!!!';
        }
        
        public function GetData()
        {
            if($this->ID>0)
            {
                if(!$this->Data)
                    return $this->Data = Core::Select(self::SEARCH_TABLE,'*',self::TABLE_ID."= '".$this->ID."'")[0];
                else
                    return $this->Data;
            }
                    
        }
        
        public static function ValidateValue($Field,$Value,$ActualValue='',$AnotherField='',$AnotherVal='',$Org=true)
    	{
    	    $AnotherField   = $AnotherVal? " AND ".$AnotherField."='".$AnotherVal."'":"";
    	    $Organization   = $Org? ' AND '.CoreOrganization::TABLE_ID.'='.$_SESSION[CoreOrganization::TABLE_ID]:'';
    	    if($ActualValue)
    	    	$TotalRegs  = Core::NumRows(self::TABLE,'*',$Field."='".$Value."' AND ".$Field."<>'".$ActualValue."'".$AnotherField.$Organization);
        	else
    		    $TotalRegs  = Core::NumRows(self::TABLE,'*',$Field."='".$Value."'".$AnotherField.$Organization);
    		if($TotalRegs>0) return $TotalRegs;
    	}
    }
    
?>