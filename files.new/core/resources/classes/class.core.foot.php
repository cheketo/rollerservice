<?php

class CoreFoot
{
	var $HTML		= '<body><html>';
	var $Link		= array();
	var $Script		= array();
	var $Meta		= array();
	var $Includes	= array();

	const INCLUDE_FOOT = "../../../core/resources/includes/inc.core.foot.php";

	function __construct()
	{
		$this->Includes[] = self::INCLUDE_FOOT;
	}

	function SetFoot()
	{
		$this->EchoIncludes();
		$this->EchoLink();
		$this->EchoMeta();
		$this->SetScript('script.js');
		$this->EchoScript();
		$this->EchoHTML();
	}

	function SetHTML($HTML)
	{
		$this->HTML	= $HTML;
	}

	function SetLink($Href,$Rel,$Type)
	{
		$this->Link[]	= '<link href="'.$Href.'" rel="'.$Rel.'" type="'.$Type.'" />';
	}

	function SetStyle($Href,$Rel="stylesheet",$Type="text/css")
	{
		$this->Link[]	= '<link href="'.$Href.'" rel="'.$Rel.'" type="'.$Type.'" />';
	}

	function SetScript($Src,$Extra='')
	{
		$this->Script[]	= '<script src="'.$Src.'" '.$Extra.' ></script>';
	}

	function SetMeta($Param1,$Param2='',$Param3='')
	{
		$this->Meta[]	= '<meta '.$Param1.' '.$Param2.' '.$Param3.' />';
	}

	function SetInclude($Src)
	{
		$this->Inludes[] = $Src;
	}

	function EchoLink()
	{
		foreach($this->Link as $Link)
		{
			echo $Link;
		}
	}

	function EchoScript()
	{
		foreach($this->Script as $Script)
		{
			echo $Script;
		}

	}

	function EchoMeta()
	{
		foreach($this->Meta as $Meta)
		{
			echo $Meta;
		}
	}

	function EchoIncludes()
	{
		foreach($this->Includes as $Include)
		{
			include($Include);
		}
	}

	function EchoHTML()
	{
		echo $this->HTML;
	}

}

?>
