<?php
    include("../files/core/resources/classes/class.core.data.base.php");
    include("../files/core/resources/classes/class.core.php");
    
    $File = fopen("ARTICULO_COMA.CSV", "r");
    while(!feof($File))
    {
        $Line = addslashes(str_replace("?","Ñ",fgets($File)));
        if($I>0)
        {    
            $Row = explode(",",$Line);
            $Code = trim(str_replace("_",",",$Row[0]));
            $BrandID = trim($Row[1])>0? trim($Row[1]):0;
            $CategoryID = trim($Row[2])>0? trim($Row[2]):0;
            $Rack = trim($Row[4])? trim($Row[4]):'';
            $Size = trim($Row[5])? trim(str_replace("_",",",$Row[5])):'';
            $Price2 = trim($Row[7])>0? trim($Row[7])/1000:0;
            $Price = trim($Row[6])>0? trim($Row[6])/1000:0;
            $SMin = trim($Row[12])>0? trim($Row[12]):0;
            $SMax = trim($Row[13])>0? trim($Row[13]):0;
            $Desc = trim($Row[42])? trim($Row[42]):'';
            $Stock = trim($Row[20])>0? trim($Row[20])/100:0;
            $Stock2 = trim($Row[19])>0? trim($Row[19])/100:0;
            
            if($Code)
            {
                $Fields = "'".$Code."',".$BrandID.",".$CategoryID.",'".$Rack."','".$Size."',".$Price.",".$Price2.",".$Stock.",".$Stock2.",".$SMin.",".$SMax.",'".$Desc."','A',NOW(),1";
                $Values .= $Values? "),(".$Fields:$Fields;
            }
            
        }else{
            $I=1;
        }
    }
    fclose($File);
    $GLOBALS['DB'] = new CoreDataBase();
    $GLOBALS['DB']->Connect();
    Core::Insert('product','code,brand_id,category_id,rack,size,price,price2,stock,stock2,stock_min,stock_max,description,status,creation_date,organization_id',$Values);
    

?>