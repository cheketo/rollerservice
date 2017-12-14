<?php
    include("../../files/core/resources/classes/class.core.data.base.php");
    include("../../files/core/resources/classes/class.core.php");
    $GLOBALS['DB'] = new CoreDataBase();
    $GLOBALS['DB']->Connect();
    
    $Products = Core::Select('product_abstract','abstract_id,code');
    foreach($Products as $Product)
    {
        $Number=0;
        $Code = $Product['code'];
        for($I=2;$I<=strlen($Code);$I++)
        {
            $SubString = substr($Code,0,$I);
            if(is_numeric($SubString))
                $Number = $SubString;
        }
        
        if($Number>0)
            Core::Update('product_abstract','order_number='.$Number,'abstract_id='.$Product['abstract_id']);
    }
    
    
    

?>