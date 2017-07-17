<?php

class CoreHead
{
	var $Title;
	var $SubTitle;
	var $Organization;
	var $DocType	= '<!DOCTYPE html>';
	var $HTML		= '<html lang="es">';
	var $Link		= array();
	var $Script		= array();
	var $Meta		= array();
	var $Favicon	= '';
	var $Icon		= '';
	var $Charset	= "utf-8";
	
	const INCLUDE_HEAD = "../../../core/resources/includes/inc.core.head.php";

	function __construct()
	{
		
	}

	function SetHTML($HTML)
	{
		$this->HTML	= $HTML;
	}

	function SetTitle($Title)
	{
		$this->Title	= $Title;
	}

	function SetSubTitle($Title)
	{
		$this->SubTitle	= $Title;
	}
	
	function SetOrganization($Organization)
	{
		if($Organization)
			$this->Organization = " • ".$Organization;
	}

	function SetHead(){
		echo $this->DocType;
		echo $this->HTML;
		echo "<head>";
		echo '<meta http-equiv="Content-Type" content="application/xhtml+xml; charset='.$this->Charset.'">';
    	echo '<meta charset="'.$this->Charset.'" >';
		echo "<title>".$this->Title.$this->Organization."</title>";
		echo $this->Favicon;
		include(self::INCLUDE_HEAD);
		$this->EchoLink();
		$this->EchoMeta();
		$this->EchoScript();
		echo '	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    			<!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
			    <!--[if lt IE 9]>
			        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
			    <![endif]-->';
		echo "</head>";
	}

	function SetDocType($DocType)
	{
		$this->DocType	= $DocType;
	}

	function SetLink($Href,$Rel,$Type)
	{
		$this->Link[]	= '<link href="'.$Href.'" rel="'.$Rel.'" type="'.$Type.'" />';
	}

	function SetStyle($Href,$Rel="stylesheet",$Type="text/css")
	{
		$this->Link[]	= '<link href="'.$Href.'" rel="'.$Rel.'" type="'.$Type.'" />';
	}

	function SetScript($Src)
	{
		$this->Script[]	= '<script src="'.$Src.'" ></script>';
	}

	function SetMeta($Param1,$Param2='',$Param3='')
	{
		$this->Meta[]	= '<meta '.$Param1.' '.$Param2.' '.$Param3.' />';
	}

	function EchoLink(){
		foreach($this->Link as $Link)
		{
			echo $Link;
		}
	}

	function EchoScript(){
		foreach($this->Script as $Script)
		{
			echo $Script;
		}

	}

	function EchoMeta(){
		foreach($this->Meta as $Meta)
		{
			echo $Meta;
		}
	}

	function SetFavicon($Rute)
	{
		$this->Favicon = '	<link rel="apple-touch-icon" href="'.$Rute.'">
    						<link rel="apple-touch-icon" sizes="114x114" href="'.$Rute.'">
    						<link rel="apple-touch-icon" sizes="72x72" href="'.$Rute.'">
    						<link rel="apple-touch-icon" sizes="144x144" href="'.$Rute.'">
    						<link rel="shortcut icon" href="'.$Rute.'" type="image/x-icon">';
	}

	function SetIcon($HTML)
	{
		$this->Icon = $HTML;
	}

	function SetCharset($Charset)
	{
		$this->Charset	= $Charset;
	}

	function GetTitle()
	{
		return $this->Title;
	}

	function GetIcon()
	{
		return $this->Icon;
	}

	function GetSubTitle()
	{
		return $this->SubTitle;
	}

}

?>
