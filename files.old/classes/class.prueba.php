<?php

class Prueba extends DataBase {
    var	$ID;
	var $Data;
	var $Providers = array();
	var $DefaultImgURL = '../../../skin/images/products/default/default.jpg';
	var $Table = 'login_log';
	var $TableID = 'log_id';
	
	public function __construct($ID=0) {
	    $this->Connect();
	    if($ID!=0)
		{
			$Data = $this->fetchAssoc($this->Table,"*",$this->TableID."=".$ID);
			$this->Data = $Data[0];
			$this->ID = $ID;
			$Data = $this->fetchAssoc($this->Table.'_category',"title","category_id=".$this->Data['category_id']);
			$this->Data['category'] = $Data[0]['title'];
			$Data = $this->fetchAssoc($this->Table.'_brand',"name","brand_id=".$this->Data['brand_id']);
			$this->Data['brand'] = $Data[0]['name'];
		}
	}
	
	
}

?>