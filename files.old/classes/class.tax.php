<?php
    class Tax {
        public static function SetIVA($CUIT,$IVA_TYPE_ID)
        {
            $DB = self::Connect();
            $DB->execQuery('DELETE','relation_cuit_tax',"tax_id=1 AND cuit=".$CUIT);
            $Percentage = $DB->fetchAssoc('tax_iva_type','percentage',"type_id=".$IVA_TYPE_ID);
            $Percentage = $Percentage[0]['percentage'];
            $DB->execQuery('INSERT','relation_cuit_tax',"tax_id,cuit,percentage","1,".$CUIT.",".$Percentage);
            
        }
        
        public function Connect()
        {
            $DB = new DataBase();
            $DB->Connect();
            return $DB;
        }
        
        public static function TaxableType($Type)
        {
            return ($Type==1 || $Type==3);
        }
    }

?>