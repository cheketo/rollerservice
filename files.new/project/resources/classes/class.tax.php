<?php
    class Tax {
        public static function SetIVA($CUIT,$IVA_TYPE_ID)
        {
            Core::Delete('relation_cuit_tax',"tax_id=1 AND cuit=".$CUIT);
            $Percentage = Core::Select('tax_iva_type','percentage',"type_id=".$IVA_TYPE_ID);
            $Percentage = $Percentage[0]['percentage'];
            Core::Insert('relation_cuit_tax',"tax_id,cuit,percentage","1,".$CUIT.",".$Percentage);
            
        }
        
        public static function TaxableType($Type)
        {
            return ($Type==1 || $Type==3);
        }
    }

?>