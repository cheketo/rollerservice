<?php
	session_name("rollerservice");
	session_cache_expire(15800);
	session_start();
	
	// $ROOT = $_SERVER['DOCUMENT_ROOT'];
	// $GOLBALS['ROOT'] = $ROOT;
	define("PROCESS", "../../../core/resources/processes/proc.core.php");
	
	/* SETTING DB CONNECTION */
	include_once("../../../core/resources/classes/class.core.data.base.php");
	$GLOBALS['DB'] = new CoreDataBase();
	if(!$GLOBALS['DB']->Connect())
	{
		header("Location: ../../../core/resources/includes/inc.core.error.php?error=".$GLOBALS['DB']->Error);
		die();
	}
	
	/* LOADING CLASSES */
	include_once("../../../core/resources/classes/class.core.php");
	spl_autoload_register("Core::Autoload");

	/* SECURIRTY CHECKS */
	$Security		= new CoreSecurity();
	if($Security->CheckProfile())
	{
		$CoreUser 	= new CoreUser($_SESSION[CoreUser::TABLE_ID]);
		$Cookies 	= new CoreLogin();
		$Cookies	->SetData($CoreUser->Data['user']);
		$Cookies	->SetCookies();
		$CoreUser->GetOrganization();
	}
	
	/* ADDING SLASHES TO PUBLIC VARIABLES */
	$_POST	= Core::AddSlashesArray($_POST);
	$_GET	= Core::AddSlashesArray($_GET);
	
	/* SETTING MENU OF THE DOCUMENT */
	$Menu = new CoreMenu();
	
	/* SETTING HEAD OF THE DOCUMENT */
	$Head	= new CoreHead();
	$Head	->SetFavicon("../../../../skin/images/body/icons/favicon.ico");
	$Head	->SetOrganization($CoreUser->Data['organization']['name']);
	
	/* SETTING FOOT OF THE DOCUMENT */
	$Foot	= new CoreFoot();
?>