<?php
    include("../../files/core/resources/classes/class.core.data.base.php");
    include("../../files/core/resources/classes/class.core.php");
    $GLOBALS['DB'] = new CoreDataBase();
    $GLOBALS['DB']->Connect();
    
    // Parche creado para corregir el campo de stock
    $Title = array();
    $File = fopen("ARTICULO_COMA.CSV", "r");
    while(!feof($File))
    {
        $Line = addslashes(str_replace("?","Ñ",fgets($File)));
        if($I>0)
        {    
            $Row = explode(",",$Line);
            $BrandID = trim($Row[1])>0? trim($Row[1]):0;
            $CategoryID = trim($Row[2])>0? trim($Row[2]):0;
            $Code = trim(str_replace("_",",",$Row[0]));
            $Stock = trim($Row[20])>0? trim($Row[20])/100:0;
            $Stock2 = trim($Row[19])>0? trim($Row[19])/100:0;
            $OrderNumber = trim($Row[3])>0? trim($Row[3]):0;
            
            if($Code)
            {
                $ID = Core::Select("product","product_id","code='".$Code."' AND  brand_id = ".$BrandID." AND category_id=".$CategoryID)[0]['product_id'];
                //Core::Update('product',"stock=".$Stock.",stock2=".$Stock2,"product_id='".$ID."'"); // UPDATE STOCK
                if($ID)
                    Core::Update('product',"order_number=".$OrderNumber,"product_id='".$ID."'"); // UPDATE ORDER NUMBER
                else{
                    echo 'C&oacute;digo '.$Code.' NO ENCONTRADO.<br>';
                }
                
                // DEBUGIN
                // if($Code=="1300")
                // {
                //     echo "Last Query: ".Core::LastQuery()."<br>";
                //     for($X=0;$X<count($Row);$X++)
                //     {
                        
                //         echo $Title[$X].": ".$Row[$X]."<br>";
                //     }
                //     echo "Posiciones Array: ".count($Row)."<br><br>";
                // }
            }else{
                echo $Line."<br>";
            }
        }else{
            $Title = explode(",",$Line);
            $I=1;
        }
    }
    fclose($File);
    
    
    

?>