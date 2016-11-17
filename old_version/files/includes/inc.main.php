<?php
	session_name("rollerservice");
	session_cache_expire(15800);
	session_start();
	include_once("../../classes/class.database.php");

	/* CONNECTION STARTS */
	$DB = new DataBase();
	/* REDIRECTS IF THERE WAS AN ERROR */
	if(!$DB->Connect())
	{
		header("Location: ../errors/500.php?error=".$DB->Error);
		die();
	}
	include_once("../../library/functions/func.common.php");
	include_dir("../../classes");

	/* SECURIRTY CHECKS */
	$Security		= new Security();
	if($Security->checkProfile($_SESSION['admin_id']))
	{
		$Admin 		= new AdminData();
		$Cookies 	= new Login($Admin->User);
		$Cookies->setCookies();
	}
	/* ADDING SLASHES TO PUBLIC VARIABLES */
	$_POST	= AddSlashesArray($_POST);
	$_GET	= AddSlashesArray($_GET);
	/* SETTING HEAD OF THE DOCUMENT */
	$Head	= new Head();
	$Head	-> setFavicon("../../../skin/images/body/icons/favicon.png");
	/* SETTING FOOT OF THE DOCUMENT */
	$Foot	= new Foot();
	/* SETTING MENU OF THE DOCUMENT */
	$Menu = new Menu();
?>
