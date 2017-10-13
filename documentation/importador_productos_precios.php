<?php
    include("../files/core/resources/classes/class.core.data.base.php");
    include("../files/core/resources/classes/class.core.php");
    
    $File = fopen("ARTICULO.CSV", "r");
    while(!feof($File))
    {
        $Line = addslashes(str_replace("{","",str_replace("}","",str_replace("?","ñ",fgets($File)))));
        if($I>2)
        {    
            $Row = explode(";",$Line);
            // if(trim($Row[0])=='TH 0039')
            if(trim($Row[6])>0)
            {
                // echo $Row[0]."<br>";
                // echo $Row[6]."<br>";
                // echo $Row[4]."<br>";
                // echo "<br><br>";
            
            $Price2 = trim($Row[7])>0? trim($Row[7])/1000:0;
            $Price = trim($Row[6])>0? trim($Row[6])/1000:0;
            
            // if($Stock2>0 && $Stock==0)
            //     $Stock=$Stock2;
            
            // if($Row[0]>0)
            // {
            //     $Fields = "'".$Code."',".$BrandID.",".$CategoryID.",'".$Rack."','".$Size."',".$Price.",".$Price2.",".$Stock.",".$Stock2.",".$SMin.",".$SMax.",'".$Desc."','A',NOW(),1";
            //     $Values .= $Values? "),(".$Fields:$Fields;
            // }
            }
        }else{
            $I++;
        }
    }
    echo $Cont;
    fclose($File);
    // $GLOBALS['DB'] = new CoreDataBase();
    // $GLOBALS['DB']->Connect();
    // Core::Insert('product','code,brand_id,category_id,rack,size,price,price2,stock,stock2,stock_min,stock_max,description,status,creation_date,organization_id',$Values);
    

?>