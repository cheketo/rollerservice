<?php 

class Config extends Database{
    
    function create()
    {
        echo $_POST['pepe'];
    }
    
    function validate()
    {
        $Pepe = $_POST['pepe'];
        if($Pepe=='pepe@mail.com')
            echo 1;
        
    }
}
?>