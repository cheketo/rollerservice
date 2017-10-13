<?php
    include("../files/core/resources/classes/class.core.data.base.php");
    include("../files/core/resources/classes/class.core.php");
    
    $File = fopen("LINEAS.CSV", "r");
    while(!feof($File))
    {
        $Line = addslashes(str_replace("?","ñ",fgets($File)));
        if($I)
        {    
            $Row = explode(";",$Line);
            if($Row[0]>0)
            {
                $Fields = $Row[0].",'".trim($Row[1])."','".trim($Row[2])."','A',NOW(),1";
                $Values .= $Values? "),(".$Fields:$Fields;
            }
        }else{
            $I=1;
        }
    }
    fclose($File);
    $GLOBALS['DB'] = new CoreDataBase();
    $GLOBALS['DB']->Connect();
    Core::Insert('product_category_new','category_id,short_title,title,status,creation_date,organization_id',$Values);
    

?>