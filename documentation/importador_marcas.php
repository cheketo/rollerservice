<?php
    include("../files/core/resources/classes/class.core.data.base.php");
    include("../files/core/resources/classes/class.core.php");
    
    $File = fopen("MARCAS.CSV", "r");
    while(!feof($File))
    {
        $Line = fgets($File);
        if($I)
        {    
            $Row = explode(";",$Line);
            if($Row[0]>0 && $Row[0]!=999 && $Row[1] && $Row[2])
            {
                $Fields = $Row[0].",'".trim($Row[1])."','".trim($Row[2])."',1,'A',NOW(),1";
                $Values .= $Values? "),(".$Fields:$Fields;
            }
        }else{
            $I=1;
        }
    }
    $GLOBALS['DB'] = new CoreDataBase();
    $GLOBALS['DB']->Connect();
    Core::Insert('product_brand','brand_id,name_short,name,country_id,status,creation_date,organization_id',$Values);
    fclose($File);

?>