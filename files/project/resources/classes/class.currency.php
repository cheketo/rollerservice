<?php

class Currency 
{
	use CoreSearchList,CoreCrud;//,CoreImage;
	
	const TABLE				= 'currency';
	const TABLE_ID			= 'currency_id';
	const SEARCH_TABLE		= 'currency';//'view_currency_list';
// 	const DEFAULT_IMG		= '../../../../skin/images/products/default/default.jpg';
// 	const DEFAULT_IMG_DIR	= '../../../../skin/images/products/default/';
// 	const IMG_DIR			= '../../../../skin/images/products/';

	public function __construct($ID=0)
	{
		$this->ID = $ID;
		$this->GetData();
	}
	
	public static function GetSelectCurrency()
	{
	    return Core::Select(self::TABLE,self::TABLE_ID.",CONCAT(title,' (',prefix,')') AS title","","title");
	}
	
	public static function GetCurrencyPrefix($CurrencyID)
	{
		return Core::Select(self::TABLE,"prefix",self::TABLE_ID."=".$CurrencyID)[0]['prefix'];
	}
}
?>