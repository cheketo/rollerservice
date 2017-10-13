<?php

class PurchaseItem
{
	use CoreSearchList,CoreCrud,CoreImage;
	
	const TABLE				= 'purchase_item';
	const TABLE_ID			= 'item_id';
	const SEARCH_TABLE		= 'view_purchase_list';
	const DEFAULT_IMG		= '../../../../skin/images/purchases/default/default2.png';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/purchases/default/';
	const IMG_DIR			= '../../../../skin/images/purchases/';

	public function __construct($ID=0)
	{
		
		$this->ID = $ID;
		if($this->ID!=0)
		{
			$Data = Core::Select(self::SEARCH_TABLE,'*',self::TABLE_ID."=".$this->ID);
			$this->Data = $Data[0];
		}
	}
	
	public static function GetItems($PurchaseID)
	{
		return Core::Select(self::SEARCH_TABLE,'*',Purchase::TABLE_ID.'='.$PurchaseID);	
	}
}