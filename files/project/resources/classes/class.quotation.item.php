<?php

class QuotationItem
{
	use CoreSearchList,CoreCrud;//,CoreImage;
	
	const TABLE				= 'quotation_item';
	const TABLE_ID			= 'item_id';
	// const SEARCH_TABLE		= 'view_quotation_list';
	// const DEFAULT_IMG		= '../../../../skin/images/orders/default/default2.png';
	// const DEFAULT_IMG_DIR	= '../../../../skin/images/orders/default/';
	// const IMG_DIR			= '../../../../skin/images/orders/';

	public function __construct($ID=0)
	{
		$this->ID = $ID;
		if($this->ID!=0)
		{
			$Data = Core::Select(Quotation::SEARCH_TABLE,'*',self::TABLE_ID."=".$this->ID);
			$this->Data = $Data[0];
		}
	}
	
	public static function DeleteItems($QuotationID)
	{
		return Core::Delete(self::TABLE,Quotation::TABLE_ID."=".$QuotationID);
	}
}