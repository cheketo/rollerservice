<?php
    trait CoreImage
    {
        var $Img;
    	
    	protected function SetImg($Img)
    	{
    		return $this->Img = file_exists($Img)? $Img : self::DEFAULT_IMG;
    	}
    	
    	public function GetImg()
    	{
    		return $this->Img;
    	}
    	
    	public function Newimage()
    	{
    		if(count($_FILES['image'])>0)
    		{
    			$TempDir = $this->ImgGalDir();
    			if(is_dir($TempDir))
    			{
    				$Prefix	= "img".intval(rand()*rand()/rand());
        			$Name	= $this->ID?$Prefix."__".$this->ID:$Prefix;
        			$Img	= new CoreFileData($_FILES['image'],$TempDir,$Name);
        			echo $Img	-> BuildImage(200,200);
    			}
    		}
    	}
    	public function GetImages($Dir='')
    	{
    		if(!$Dir) $Dir = $this->ImgGalDir();
			$Elements = scandir($Dir);
			foreach($Elements as $Image)
			{
				if(strlen($Image)>4 && $Image[0]!=".")
				{
					$Images[] = $Dir.$Image;
				}
			}
    		return $Images;
    	}
    
    	public function ImgGalDir()
    	{
    		$TempDir = $this->ID? self::IMG_DIR.$this->ID."/":self::IMG_DIR;
    		if(!file_exists($TempDir)) mkdir($TempDir);
    		return $TempDir;
    	}
    	
    	public static function DefaultImages($Dir='')
    	{
    		if(!$Dir) $Dir = self::DEFAULT_IMG_DIR;
			$Elements = scandir($Dir);
			foreach($Elements as $Image)
			{
				if(strlen($Image)>4 && $Image[0]!=".")
				{
					$Images[] = $Dir.$Image;
				}
			}
    		return $Images;
    	}
    	
    	public function ProcessImg($Image)
    	{
    	    $Dir 		= array_reverse(explode("/",$Image));
    		if($Dir[1]!="default" && $Image!=$this->ImgGalDir().$Dir[0])
    		{
    			$Temp 	= $Image;
    			$Image 	= $this->ImgGalDir().$Dir[0];
    			copy($Temp,$Image);
    			if($Temp==self::IMG_DIR.$Dir[0] && file_exists($Temp))
    				unlink($Temp);
    		}
    		return $Image;
    	}
    	
    	public function Deleteimage()
    	{
    		if(file_exists($_POST['src'])) unlink($_POST['src']);
    	}
    }
?>