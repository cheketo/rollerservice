<?php

class CoreOrganization 
{
	use CoreSearchList;
	
	var	$ID;
	var $Data;
	var $ImgGalDir		= '../../../skin/images/organization/';
	var $Customers 		= array();
	var $Parent 		= array();
	
	const TABLE			= 'core_organization';
	const TABLE_ID		= 'organization_id';
	const DEFAULTIMG	= "../../../skin/images/organization/default/company.jpg";

	public function __construct($ID=0)
	{
		
		if($ID!=0)
		{
			$Data = Core::Select(self::TABLE,"*",self::TABLE_ID."=".$ID);
			$this->Data = $Data[0];
			$this->ID = $ID;
		}
	}
	
	public function GetCompanies()
	{
		if(!$this->Companies)
		{
			$this->Companies = Core::Select(self::TABLE,'*',"parent_id =".$this->ID);
		}
		return $this->Companies;
	}
	
	public function GetParent()
	{
		if(!$this->Parent)
		{
			$Data			= Core::Select("admin_organization",'*',"organization_id =".$this->Data['parent_id']);
			$this->Parent	= $Data[0];
		}
		return $this->Parent;
	}

	public function GetDefaultImg()
	{
		return self::DEFAULTIMG;
	}
	
	public function GetImg()
	{
		if(!$this->Data['image'])
			return $this->GetDefaultImg();
		else
			return $this->Data['image'];
	}
	
	public function ImgGalDir()
	{
		$Dir = $this->ImgGalDir.'/'.$this->ID;
		if(!file_exists($Dir))
			mkdir($Dir);
		return $Dir;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// SEARCHLIST FUNCTIONS ///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function MakeRegs($Mode="List")
	{
		$Rows	= $this->GetRegs();
		//echo $this->LastQuery();
		for($i=0;$i<count($Rows);$i++)
		{
			$Row	=	new CoreOrganization($Rows[$i]['organization_id']);
			//var_dump($Row);
			// $UserGroups = $Row->GetGroups();
			// $Groups='';
			// foreach($UserGroups as $Group)
			// {
			// 	$Groups .= '<span class="label label-warning">'.$Group['title'].'</span> ';
			// }
			// if(!$Groups) $Groups = 'Ninguno';
			$Actions	= 	'<span class="roundItemActionsGroup"><a><button type="button" class="btn btnGreen ExpandButton" id="expand_'.$Row->ID.'"><i class="fa fa-plus"></i></button></a>';
			$Actions	.= 	'<a href="edit.php?id='.$Row->ID.'"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
			if($Row->Data['status']=="A")
			{
				if($Row->Data['organization_id']!=$_SESSION['organization_id'])
				{
					$Actions	.= '<a class="deleteElement" process="'.PROCESS.'" id="delete_'.$Row->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
					$Restrict	= '';
				}else{
					$Restrict	= ' undeleteable ';
				}
			}else{
				$Actions	.= '<a class="activateElement" process="'.PROCESS.'" id="activate_'.$Row->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			}
			$Actions	.= '</span>';
			switch(strtolower($Mode))
			{
				case "list":
					$RowBackground = $i % 2 == 0? '':' listRow2 ';
					$Regs	.= '<div class="row listRow'.$RowBackground.$Restrict.'" id="row_'.$Row->ID.'" title="'.$Row->Data['name'].'">
									<div class="col-lg-3 col-md-3 col-sm-10 col-xs-10">
										<div class="listRowInner">
											<img class="img-circle" src="'.$Row->GetImg().'" alt="'.$Row->Data['name'].'">
											<span class="listTextStrong">'.$Row->Data['name'].'</span>
											<span class="smallDetails">CUIT: '.$Row->Data['cuit'].'</span>
										</div>
									</div>
									<div class="col-lg-2 col-md-3 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="smallTitle">Direcci&oacute;n</span>
											<span class="emailTextResp">'.$Row->Data['address'].'</span>
										</div>
									</div>
									<div class="col-lg-3 col-md-2 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="smallTitle">Raz&oacute;n Social</span>
											<span class="listTextStrong"><span class="label label-primary">'.ucfirst($Row->Data['corporate_name']).'</span></span>
										</div>
									</div>
									<div class="col-lg-1 col-md-1 col-sm-1 hideMobile990"></div>
									<div class="animated DetailedInformation Hidden col-md-11">
										<br>a
										<br>a
										<br>aa
										<br>a
										<br>as
									</div>
									<div class="listActions flex-justify-center Hidden">
										<div>'.$Actions.'</div>
									</div>
									
								</div>';
				break;
				case "grid":
				$Regs	.= '<li id="grid_'.$Row->ID.'" class="RoundItemSelect roundItemBig'.$Restrict.'" title="'.$Row->Data['name'].'">
						            <div class="flex-allCenter imgSelector">
						              <div class="imgSelectorInner">
						                <img src="'.$Row->GetImg().'" alt="'.$Row->Data['name'].'" class="img-responsive">
						                <div class="imgSelectorContent">
						                  <div class="roundItemBigActions">
						                    '.$Actions.'
						                    <span class="roundItemCheckDiv"><a href="#"><button type="button" class="btn roundBtnIconGreen Hidden" name="button"><i class="fa fa-check"></i></button></a></span>
						                  </div>
						                </div>
						              </div>
						              <div class="roundItemText">
						                <p><b>'.$Row->Data['name'].'</b></p>
						                <p>'.ucfirst($Row->Data['corporate_name']).'</p>
						                <p>('.$Row->Data['cuit'].')</p>
						              </div>
						            </div>
						          </li>';
				break;
			}
        }
        if(!$Regs) $Regs.= '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron empresas.</h4><p>Puede crear una empresa haciendo click <a href="new.php">aqui</a>.</p></div>';
		return $Regs;
	}
	
	protected function InsertSearchField()
	{
		return '<!-- Name -->
          <div class="input-group">
            <span class="input-group-addon order-arrows sort-activated" order="name" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','name','','form-control','placeholder="Nombre"').'
          </div>
          <!-- Corporate Name -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="corporate_name" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','corporate_name','','form-control','placeholder="Raz&oacute;n Social"').'
          </div>
          <!-- Address -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="address" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','address','','form-control','placeholder="Direcci&oacute;n"').'
          </div>
          <!-- Phone -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="phone" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','phone','','form-control','placeholder="Tel&eacute;fono"').'
          </div>
          <!-- Website -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="website" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','website','','form-control','placeholder="Sitio Web"').'
          </div>
          <!-- CUIT -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="cuit" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','cuit','','form-control','placeholder="CUIT"').'
          </div>
          <!-- Agent -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="agent_name" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','agent_name','','form-control','placeholder="Responsable"').'
          </div>';
	}
	
	protected function InsertSearchButtons()
	{
		return '<!-- New Button -->
		    	<a href="new.php"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-user-plus"></i> Nueva Empresa</button></a>
		    	<!-- /New Button -->';
	}
	
	public function ConfigureSearchRequest()
	{
		$this->SetTable('admin_organization c,admin_organization_agent a');
		$this->SetFields('c.*,a.name as agent_name, a.phone as agent_phone, a.email as agent_email, a.charge as agent_charge');
		if($_SESSION['organization_id']==1)
			$this->SetWhere("c.organization_id >= 1");
		else
			$this->SetWhere("(c.parent_id=".$_SESSION['organization_id']." OR organization_id = ".$_SESSION['organization_id'].")");
		//$this->AddWhereString(" AND c.organization_id = a.organization_id");
		$this->SetOrder('name');
		$this->SetGroupBy("c.organization_id");
		
		foreach($_POST as $Key => $Value)
		{
			$_POST[$Key] = $Value;
		}
			
		if($_POST['name']) $this->SetWhereCondition("c.name","LIKE","%".$_POST['name']."%");
		if($_POST['corporate_name']) $this->SetWhereCondition("c.corporate_name","LIKE","%".$_POST['corporate_name']."%");
		if($_POST['address']) $this->SetWhereCondition("c.address","LIKE","%".$_POST['address']."%");
		if($_POST['cuit']) $this->SetWhereCondition("c.cuit","=",$_POST['cuit']);
		if($_POST['website']) $this->SetWhereCondition("c.website","LIKE","%".$_POST['website']."%");
		if($_POST['agent_name']) $this->SetWhereCondition("a.name","LIKE","%".$_POST['agent_name']."%");
		//if($_POST['agent_email']) $this->SetWhereCondition("a.email","LIKE","%".$_POST['agent_email']."%");
		//if($_POST['agent_charge']) $this->SetWhereCondition("a.charge","LIKE", "%".$_POST['agent_charge']."%");
		if($_POST['phone']) $this->AddWhereString(" AND (c.phone LIKE '%".$_POST['phone']."%' OR a.phone LIKE '%".$_POST['phone']."%')");
		//if($_POST['parent']) $this->SetWhereCondition("c.parent_id","=",$_POST['parent']);
		if($_POST['agent_name'])
		{
			$this->AddWhereString(" AND c.organization_id = a.organization_id");
		}
		if($_REQUEST['status'])
		{
			if($_POST['status']) $this->SetWhereCondition("c.status","=", $_POST['status']);
			if($_GET['status']) $this->SetWhereCondition("c.status","=", $_GET['status']);	
		}else{
			$this->SetWhereCondition("c.status","=","A");
		}
		if($_POST['view_order_field'])
		{
			if(strtolower($_POST['view_order_mode'])=="desc")
				$Mode = "DESC";
			else
				$Mode = $_POST['view_order_mode'];
			
			$Order = strtolower($_POST['view_order_field']);
			switch($Order)
			{
				case "agent_name": 
					$this->AddWhereString(" AND c.organization_id = a.organization_id");
					$Order = 'name';
					$Prefix = "a.";
				break;
				default:
					$Prefix = "c.";
				break;
			}
			$this->SetOrder($Prefix.$Order." ".$Mode);
		}
		if($_POST['regsperview'])
		{
			$this->SetRegsPerView($_POST['regsperview']);
		}
		if(intval($_POST['view_page'])>0)
			$this->SetPage($_POST['view_page']);
	}

	public function MakeList()
	{
		return $this->MakeRegs("List");
	}

	public function MakeGrid()
	{
		return $this->MakeRegs("Grid");
	}

	public function GetData()
	{
		return $this->Data;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// PROCESS METHODS ///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function Insert()
	{
		$Agents = array();
		for($i=1;$i<=$_POST['total_agents'];$i++)
		{
			if($_POST['agent_name_'.$i])
				$Agents[] = array('name'=>ucfirst($_POST['agent_name_'.$i]),'charge'=>ucfirst($_POST['agent_charge_'.$i]),'email'=>$_POST['agent_email_'.$i],'phone'=>$_POST['agent_phone_'.$i]);
		}
		$Image 			= $_POST['newimage'];
		$Name			= ucfirst($_POST['name']);
		$CorporateName	= ucfirst($_POST['corporate_name']);
		$Address		= ucfirst($_POST['address']);
		//$Email 			= htmlentities(strtolower($_POST['email']));
		$Website 		= $_POST['website'];
		$Phone			= $_POST['phone'];
		$CUIT			= $_POST['cuit'];
		$NewID			= Core::Insert('admin_organization','name,corporate_name,address,website,phone,cuit,creation_date,creator_id,parent_id',"'".$Name."','".$CorporateName."','".$Address."','".$Website."','".$Phone."','".$CUIT."',NOW(),".$_SESSION['user_id'].",".$_SESSION['organization_id']);
		//echo $this->LastQuery();
		$New 	= new CoreOrganization($NewID);
		$Dir 	= array_reverse(explode("/",$Image));
		if($Dir[1]!="default")
		{
			$Temp 	= $Image;
			$Image 	= $New->ImgGalDir().$Dir[0];
			copy($Temp,$Image);
		}
		Core::Update('admin_organization',"logo='".$Image."'","organization_id=".$NewID);
		
		// INSERT AGENTS
		foreach($Agents as $Agent)
		{
			if($Fields)
				$Fields .= "),(";
			$Fields .= $NewID.",'".$Agent['name']."','".$Agent['charge']."','".$Agent['email']."','".$Agent['phone']."'";
		}
		Core::Insert('admin_organization_agent','organization_id,name,charge,email,phone',$Fields);
	}	
	
	public function Update()
	{
		$ID 	= $_POST['id'];
		$Edit	= new CoreUser($ID);
		if($_POST['password'])
		{
			$Password	= md5($_POST['password']);
			$PasswordFilter	= ",password='".$Password."'";
		}
		$Image 		= $_POST['newimage'];
		$User		= strtolower($_POST['user']);
		$FirstName	= $_POST['first_name'];
		$LastName	= $_POST['last_name'];
		$Email 		= $_POST['email'];
		$ProfileID	= $_POST['profile'];
		$Groups		= $_POST['groups'] ? explode(",",$_POST['groups']) : array();
		$Menues		= $_POST['menues'] ? explode(",",$_POST['menues']) : array();
		$Dir 		= array_reverse(explode("/",$Image));
		if($Dir[1]!="default" && $ID!=$this->AdminID)
		{
			$Temp 	= $Image;
			$Image 	= $Edit->ImgGalDir().$Dir[0];
			copy($Temp,$Image);
		}
		$Update		= Core::Update('core_user',"user='".$User."'".$PasswordFilter.",first_name='".$FirstName."',last_name='".$LastName."',email='".$Email."',profile_id='".$ProfileID."',img='".$Image."'","user_id=".$ID);
		//echo $this->LastQuery();
		Core::Delete('core_relation_user_group',"user_id = ".$ID);
		Core::Delete('core_relation_user_menu',"user_id = ".$ID);
		foreach($Groups as $Group)
		{
			if(intval($Group)>0)
				$Values .= !$Values? $ID.",".$Group : "),(".$ID.",".$Group;
		}
		Core::Insert('core_relation_user_group','user_id,group_id',$Values);
		//echo $this->LastQuery();
		$Values = "";
		foreach($Menues as $Menu)
		{
			if(intval($Menu)>0)
				$Values .= !$Values? $ID.",".$Menu : "),(".$ID.",".$Menu;
		}
		Core::Insert('core_relation_user_menu','user_id,menu_id',$Values);
	}
	
	public function Activate()
	{
		$ID	= $_POST['id'];
		Core::Update('core_user',"status = 'A'","user_id=".$ID);
	}
	
	public function Delete()
	{
		$ID	= $_POST['id'];
		Core::Update('core_user',"status = 'I'","user_id=".$ID);
	}
	
	public function Search()
	{
		$this->ConfigureSearchRequest();
		echo $this->InsertSearchResults();
	}
	
	public function Newimage()
	{
		if(count($_FILES['image'])>0)
		{
			if($_POST['newimage']!=$this->GetDefaultImg() && file_exists($_POST['newimage']))
				unlink($_POST['newimage']);
			$TempDir = $this->ImgGalDir;
			$Name	= "organization".intval(rand()*rand()/rand());
			$Img	= new CoreFileData($_FILES['image'],$TempDir,$Name);
			echo $Img	-> BuildImage(100,100);
		}
	}
	
	public function Validate()
	{
		$User 			= strtolower($_POST['user']);
		$ActualUser 	= strtolower($_POST['actualuser']);

	    if($ActualUser)
	    	$TotalRegs  = Core::NumRows('core_user','*',"user = '".$User."' AND user<> '".$ActualUser."'");
    	else
		    $TotalRegs  = Core::NumRows('core_user','*',"user = '".$User."'");
		if($TotalRegs>0) echo $TotalRegs;
	}
	
	// public function Validate_email()
	// {
	// 	$Email 			= strtolower(utf8_encode($_POST['email']));
	// 	$ActualEmail 	= strtolower(utf8_encode($_POST['actualemail']));

	//     if($ActualEmail)
	//     	$TotalRegs  = Core::NumRows('core_user','*',"email = '".$Email."' AND email<> '".$ActualEmail."'");
 //   	else
	// 	    $TotalRegs  = Core::NumRows('core_user','*',"email = '".$Email."'");
	// 	if($TotalRegs>0) echo $TotalRegs;
	// }
	
	public function Fillgroups()
	{
		$Profile 	= $_POST['profile'];
		$Admin 		= $_POST['admin'];
        $Groups 	= new CoreGroup();
        echo $Groups->GetGroups($Profile,$Admin);
	}
}
?>
