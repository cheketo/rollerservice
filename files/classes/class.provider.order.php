<?php

class ProviderPurchaseOrder extends DataBase
{
	var	$ID;
	var $Data;
	var $Items 			= array();
	var $Table			= "product_provider_purchase_order";
	var $TableID		= "order_id";
	
	const DEFAULTIMG	= "../../../skin/images/providers/default/order.png";

	public function __construct($ID=0)
	{
		$this->Connect();
		if($ID!=0)
		{
			$Data = $this->fetchAssoc($this->Table,"*",$this->TableID."=".$ID);
			$this->Data = $Data[0];
			$this->ID = $ID;
			$this->Data['items'] = $this->GetItems();
			$Provider = $this->fetchAssoc("product_provider","name","provider_id=".$this->Data['provider_id']);
			$this->Data['provider'] = $Provider[0]['name'];
			$Quantity = $this->fetchAssoc("product_provider_purchase_order_item","SUM(quantity) AS total",$this->TableID."=".$this->ID);
			$this->Data['quantity'] = $Quantity[0]['total'];
		}
	}
	
	public function GetItems()
	{
		if(empty($this->Items))
		{
			$this->Items = $this->fetchAssoc(
				$this->Table."_item a 
				LEFT JOIN product b ON (a.product_id = b.product_id)
				LEFT JOIN currency c ON (a.currency_id = c.currency_id)
				",
				"a.*,(a.price * a.quantity) AS total,c.prefix AS currency,b.code",
				$this->TableID."=".$this->ID,'a.item_id');
		}
		return $this->Items;
	}

	public function GetDefaultImg()
	{
		return self::DEFAULTIMG;
	}
	
	public function GetImg()
	{
		return $this->GetDefaultImg();
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////// SEARCHLIST FUNCTIONS ///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function MakeRegs($Mode="List")
	{
		$Rows	= $this->GetRegs();
		//echo $this->lastQuery();
		for($i=0;$i<count($Rows);$i++)
		{
			$Row	=	new ProviderPurchaseOrder($Rows[$i][$this->TableID]);
			//var_dump($Row);
			// $UserGroups = $Row->GetGroups();
			// $Groups='';
			// foreach($UserGroups as $Group)
			// {
			// 	$Groups .= '<span class="label label-warning">'.$Group['title'].'</span> ';
			// }
			// if(!$Groups) $Groups = 'Ninguno';
			$Actions	= 	'<span class="roundItemActionsGroup"><a><button type="button" class="btn btnGreen ExpandButton" id="expand_'.$Row->ID.'"><i class="fa fa-plus"></i></button></a>';
			
			if($Row->Data['status']=="P" || $Row->Data['status']=="I"){
				$Actions	.= '<a class="activateElement" process="../../library/processes/proc.common.php" id="activate_'.$Row->ID.'"><button type="button" class="btn btnGreen"><i class="fa fa-check-circle"></i></button></a>';
			}
			
			if($Row->Data['status']=="P")
			{
				$Actions	.= 	'<a href="edit.php?id='.$Row->ID.'"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button></a>';
				$Actions	.= '<a class="deleteElement" process="../../library/processes/proc.common.php" id="delete_'.$Row->ID.'"><button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></a>';
				
			}
			$Actions	.= '</span>';
			// echo '<pre>';
			// print_r($Row->Data['items']);
			// echo '</pre>';
			$Date = explode(" ",$Row->Data['delivery_date']);
			$OrderDate = implode("/",array_reverse(explode("-",$Date[0])));
			
			$Items = '<div style="margin-top:10px;">';
			$I=0;
			foreach($Row->Data['items'] as $Item)
			{
				$I++;
				$RowClass = $I % 2 != 0? 'bg-gray':'bg-gray-active';
				
				$Date = explode(" ",$Item['delivery_date']);
				$DeliveryDate = implode("/",array_reverse(explode("-",$Date[0])));
				$ItemTotal = $Item['currency']." ".$Item['total'];
				$ItemPrice = $Item['currency']." ".$Item['price'];
				
				$Items .= '
							<div class="row '.$RowClass.'" style="padding:5px;">
								<div class="col-md-3 col-sm-6">
									<div class="listRowInner">
										<span class="listTextStrong">'.$Item['code'].'</span>
										<span class="listTextStrong"><span class="label label-warning"><i class="fa fa-calendar"></i> '.$DeliveryDate.'</span></span>
									</div>
								</div>
								<div class="col-md-3 hideMobile990">
									<div class="listRowInner">
										<span class="listTextStrong">Precio</span>
										<span class="listTextStrong"><span class="label label-info">'.$ItemPrice.'</span></span>
									</div>
								</div>
								<div class="col-md-3 hideMobile990">
									<div class="listRowInner">
										<span class="listTextStrong">Cantidad</span>
										<span class="listTextStrong"><span class="label label-primary">'.$Item['quantity'].'</span></span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="listRowInner">
										<span class="listTextStrong">Total Art.</span>
										<span class="listTextStrong"><span class="label label-success">'.$ItemTotal.'</span></span>
									</div>
								</div>
									
							</div>';
			}
			$Items .='</div>';
			switch(strtolower($Mode))
			{
				case "list":
						$Extra = !$Row->Data['extra']? '': '<div class="col-lg-2 col-md-3 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="emailTextResp">'.$Row->Data['extra'].'</span>
										</div>
									</div>';
									
					$RowBackground = $i % 2 == 0? '':' listRow2 ';
					
					$Regs	.= '<div class="row listRow'.$RowBackground.'" id="row_'.$Row->ID.'" title="una orden de compra">
									<div class="col-lg-3 col-md-5 col-sm-8 col-xs-10">
										<div class="listRowInner">
											<img class="img-circle" style="border-radius:0%!important;" src="'.$Row->GetImg().'" alt="'.$Row->Data['name'].'">
											<span class="listTextStrong">'.$Row->Data['provider'].'</span>
											<span class="smallDetails"><i class="fa fa-calendar"></i> '.$OrderDate.'</span>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="listTextStrong">Cant. Total</span>
											<span class="listTextStrong"><span class="label label-primary">'.$Row->Data['quantity'].'</span></span>
										</div>
									</div>
									<div class="col-lg-2 col-md-3 col-sm-2 hideMobile990">
										<div class="listRowInner">
											<span class="listTextStrong">Precio Total</span>
											<span class="emailTextResp"><span class="label label-success">'.$Row->Data['items'][0]['currency'].' '.$Row->Data['total'].'</span></span>
										</div>
									</div>
									'.$Extra.'
									<div class="animated DetailedInformation Hidden col-md-12">
										'.$Items.'
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
						                <p>'.ucfirst($Row->Data['iibb']).'</p>
						                <p>('.$Row->Data['cuit'].')</p>
						              </div>
						            </div>
						          </li>';
				break;
			}
        }
        if(!$Regs) $Regs.= '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron ordenes de compras a proveedores.</h4><p>Puede crear una orden haciendo click <a href="new.php">aqui</a>.</p></div>';
		return $Regs;
	}
	
	protected function InsertSearchField()
	{
		return '<!-- Provider -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="name" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('text','name','','form-control','placeholder="Proveedor"').'
          </div>
          <!-- Code -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="code" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('text','code','','form-control','placeholder="Art&iacute;culo"').'
          </div>
          <!-- Agent -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="agent" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('text','agent','','form-control','placeholder="Contacto"').'
          </div>
          <!-- Delivery Date -->
          <div class="input-group">
            <span class="input-group-addon order-arrows sort-activated" order="delivery_date" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('text','delivery_date','','form-control delivery_date','placeholder="Entrega"').'
          </div>
          <!-- Extra -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="extra" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.insertElement('text','extra','','form-control','placeholder="Info Extra"').'
          </div>
          ';
	}
	
	protected function InsertSearchButtons()
	{
		return '<!-- New Button -->
		    	<a href="new.php"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-plus"></i> Nueva Orden de Compra</button></a>
		    	<!-- /New Button -->';
	}
	
	public function ConfigureSearchRequest()
	{
		$this->SetTable($this->Table.' a LEFT JOIN product_provider_purchase_order_item b ON (b.order_id=a.order_id) LEFT JOIN product c ON (b.product_id = c.product_id) LEFT JOIN product_provider d ON (d.provider_id=a.provider_id) LEFT JOIN product_provider_agent e ON (e.agent_id = a.agent_id)');
		$this->SetFields('a.order_id,a.type,a.total,a.extra,a.status,a.payment_status,a.delivery_status,d.name as provider,SUM(b.quantity) as quantity');
		$this->SetWhere("c.company_id=".$_SESSION['company_id']);
		//$this->AddWhereString(" AND c.company_id = a.company_id");
		//$this->SetOrder('a.delivery_date');
		$this->SetGroupBy("a.".$this->TableID);
		
		foreach($_POST as $Key => $Value)
		{
			$_POST[$Key] = $Value;
		}
			
		if($_POST['name']) $this->SetWhereCondition("d.name","LIKE","%".$_POST['name']."%");
		if($_POST['agent']) $this->SetWhereCondition("e.name","LIKE","%".$_POST['agent']."%");
		if($_POST['code']) $this->SetWhereCondition("c.code","LIKE","%".$_POST['code']."%");
		if($_POST['extra']) $this->SetWhereCondition("a.extra","LIKE","%".$_POST['extra']."%");
		if($_POST['delivery_date'])
		{
			$_POST['delivery_date'] = implode("-",array_reverse(explode("/",$_POST['delivery_date'])));
			$this->AddWhereString(" AND (a.delivery_date = '".$_POST['delivery_date']."' OR b.delivery_date='".$_POST['delivery_date']."')");
		}
		
		
		if($_REQUEST['status'])
		{
			if($_POST['status']) $this->SetWhereCondition("a.status","=", $_POST['status']);
			if($_GET['status']) $this->SetWhereCondition("a.status","=", $_GET['status']);	
		}else{
			$this->SetWhereCondition("a.status","=","P");
		}
		
		$Prefix = "a.";
		$Order = 'delivery_date';
		$Mode = 'ASC';
		$this->SetOrder($Prefix.$Order." ".$Mode);
		if($_POST['view_order_field'])
		{
			if(strtolower($_POST['view_order_mode'])=="desc")
				$Mode = "DESC";
			else
				$Mode = $_POST['view_order_mode'];
			
			$Order = strtolower($_POST['view_order_field']);
			switch($Order)
			{
				case "name": 
					$Order = 'name';
					$Prefix = "d.";
				break;
				case "code": 
					$Order = 'code';
					$Prefix = "c.";
				break;
				case "agent": 
					$Order = 'name';
					$Prefix = "e.";
				break;
				// default:
				//		$Prefix = "a.";	
				// break;
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
		// ITEMS DATA
		$Items = array();
		for($I=1;$I<=$_POST['items'];$I++)
		{
			if($_POST['item_'.$I])
			{
				$ItemDate = implode("-",array_reverse(explode("/",$_POST['date_'.$I])));
				$Items[] = array('id'=>$_POST['item_'.$I],'price'=>$_POST['price_'.$I],'quantity'=>$_POST['quantity_'.$I], 'delivery_date'=>$ItemDate );
				if(!$Date)
				{
					$Date = $ItemDate;
				}
				if(strtotime($ItemDate." 00:00:00") > strtotime($Date." 00:00:00")){
					$Date = $LastDate;
				}
			}
		}
		
		// Basic Data
		$Type			= $_POST['type'];
		$ProviderID		= $_POST['provider'];
		$AgentID 		= $_POST['agent']? $_POST['agent']: 0;
		$CurrencyID		= $_POST['currency'];
		$Extra			= $_POST['extra'];
		$Total			= $_POST['total_price'];
		$Admin			= new AdminData($_SESSION['admin_id']);
		$Status			= $Admin->IsOwner()? "A" : "P";
		
		$Insert			= $this->execQuery('insert',$this->Table,'type,provider_id,agent_id,currency_id,extra,total,delivery_date,status,creation_date,created_by,company_id',"'".$Type."',".$ProviderID.",".$AgentID.",".$CurrencyID.",'".$Extra."',".$Total.",'".$Date."','".$Status."',NOW(),".$_SESSION['admin_id'].",".$_SESSION['company_id']);
		//echo $this->lastQuery();
		$NewID 		= $this->GetInsertId();
		$New 	= new ProviderPurchaseOrder($NewID);
		
		// INSERT ITEMS
		foreach($Items as $Item)
		{
			if($Fields)
				$Fields .= "),(";
			$Fields .= $NewID.",".$ProviderID.",".$Item['id'].",".$Item['price'].",".$Item['quantity'].",'".$Item['delivery_date']."',".$CurrencyID.",NOW(),".$_SESSION['admin_id'].",".$_SESSION['company_id'];
		}
		$this->execQuery('insert','product_provider_purchase_order_item','order_id,provider_id,product_id,price,quantity,delivery_date,currency_id,creation_date,created_by,company_id',$Fields);
		//echo $this->lastQuery();
		
	}
	
	public function Update()
	{
		$ID 	= $_POST['id'];
		$Edit	= new ProviderPurchaseOrder($ID);
		$Status = $Edit->Data['status'];
		if($Status!='P')
		{
			echo "403";
		}
		// ITEMS DATA
		$Items = array();
		for($I=1;$I<=$_POST['items'];$I++)
		{
			if($_POST['item_'.$I])
			{
				$ItemDate = implode("-",array_reverse(explode("/",$_POST['date_'.$I])));
				$Items[] = array('id'=>$_POST['item_'.$I],'price'=>$_POST['price_'.$I],'quantity'=>$_POST['quantity_'.$I], 'delivery_date'=>$ItemDate );
				if(!$Date)
				{
					$Date = $ItemDate;
				}
				if(strtotime($ItemDate." 00:00:00") > strtotime($Date." 00:00:00")){
					$Date = $LastDate;
				}
			}
		}
		
		// Basic Data
		$Type			= $_POST['type'];
		$ProviderID		= $_POST['provider'];
		$AgentID 		= $_POST['agent']? $_POST['agent']: 0;
		$CurrencyID		= $_POST['currency'];
		$Extra			= $_POST['extra'];
		$Total			= $_POST['total_price'];
		
		// CREATE NEW IMAGE IF EXISTS
		if($Image!=$Edit->Data['logo'])
		{
			if($Image!=$Edit->GetDefaultImg())
			{
				if(file_exists($Edit->GetImg()))
					unlink($Edit->GetImg());
				$Dir 	= array_reverse(explode("/",$Image));
				$Temp 	= $Image;
				$Image 	= $Edit->ImgGalDir().$Dir[0];
				copy($Temp,$Image);
			}
		}
		
		$Update		= $this->execQuery('update','product_provider_purchase_order',"type='".$Type."',provider_id=".$ProviderID.",agent_id=".$AgentID.",currency_id=".$CurrencyID.",extra='".$Extra."',total=".$Total.",updated_by=".$_SESSION['admin_id'],"order_id=".$ID);
		//echo $this->lastQuery();
		
		// DELETE OLD ITEMS
		$this->execQuery('delete','product_provider_purchase_order_item',"order_id = ".$ID);
		
		// INSERT ITEMS
		foreach($Items as $Item)
		{
			if($Fields)
				$Fields .= "),(";
			$Fields .= $ID.",".$ProviderID.",".$Item['id'].",".$Item['price'].",".$Item['quantity'].",'".$Item['delivery_date']."',".$CurrencyID.",NOW(),".$_SESSION['admin_id'].",".$_SESSION['company_id'];
		}
		$this->execQuery('insert','product_provider_purchase_order_item','order_id,provider_id,product_id,price,quantity,delivery_date,currency_id,creation_date,created_by,company_id',$Fields);
		echo $this->lastQuery();
	}
	
	public function Activate()
	{
		$ID	= $_POST['id'];
		$Order = new ProviderPurchaseOrder($ID);
		$Status = $Order->Data['status'] == 'I'? 'P' : 'A';
		$this->execQuery('update',$this->Table,"status = '".$Status."'",$this->TableID."=".$ID);
	}
	
	public function Delete()
	{
		$ID	= $_POST['id'];
		$this->execQuery('update',$this->Table,"status = 'I'",$this->TableID."=".$ID);
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
			$ID	= $_POST['id'];
			if($ID)
			{
				$New = new ProviderPurchaseOrder($ID);
				if($_POST['newimage']!=$New->GetImg() && file_exists($_POST['newimage']))
					unlink($_POST['newimage']);
				$TempDir= $this->ImgGalDir;
				$Name	= "provider".intval(rand()*rand()/rand());
				$Img	= new FileData($_FILES['image'],$TempDir,$Name);
				echo $Img	-> BuildImage(100,100);
			}else{	
				if($_POST['newimage']!=$this->GetDefaultImg() && file_exists($_POST['newimage']))
					unlink($_POST['newimage']);
				$TempDir= $this->ImgGalDir;
				$Name	= "provider".intval(rand()*rand()/rand());
				$Img	= new FileData($_FILES['image'],$TempDir,$Name);
				echo $Img	-> BuildImage(100,100);
			}
		}
	}
	
	public function Validate()
	{
		$User 			= strtolower($_POST['name']);
		$ActualUser 	= strtolower($_POST['actualname']);

	    if($ActualUser)
	    	$TotalRegs  = $this->numRows($this->Table,'*',"name = '".$User."' AND name <> '".$ActualUser."'");
    	else
		    $TotalRegs  = $this->numRows($this->Table,'*',"name = '".$User."'");
		if($TotalRegs>0) echo $TotalRegs;
	}
	
	public function Fillagents()
	{
		$Provider = $_POST['provider'];
		$Agents = $this->fetchAssoc('product_provider_agent','agent_id,name',"provider_id=".$Provider,'name');
		if(count($Agents)>0)
		{
			$HTML = insertElement('select','agents','','form-control select2 selectTags',' style="width: 100%;height:auto!important;"',$Agents,'','Seleccione un Contacto');
		}else{
			//$HTML = insertElement('select','agents','','form-control select2 selectTags',' style="width: 100%;height:auto!important;"','','0','Sin Contacto');
		}
		echo $HTML;
	}
	
	public function Addorderitem()
	{
		$ID = $_POST['item'];
		$TotalPrice = "$ 0.00";
		if($ID % 2 != 0)
			$BgClass = "bg-gray";
		else
			$BgClass = "bg-gray-active";
		$HTML = '
			<div id="item_row_'.$ID.'" item="'.$ID.'" class="row form-group inline-form-custom ItemRow '.$BgClass.'" style="margin-bottom:0px!important;padding:10px 0px!important;">
                <form id="item_form_'.$ID.'">
                <div class="col-xs-4 txC">
                	<span id="Item'.$ID.'" class="Hidden ItemText'.$ID.'"></span>
                  '.insertElement('select','items_'.$ID,'','ItemField'.$ID.' form-control select2 selectTags','',$this->fetchAssoc('product','product_id,code',"status='A' AND company_id=".$_SESSION['company_id'],'code'),'','Seleccione un Art&iacute;culo').'
                  '.insertElement("text","item_".$ID,'','Hidden','validateEmpty="Seleccione un Art&iacute;culo"').'
                </div>
                <div class="col-xs-1 txC">
                	<span id="Price'.$ID.'" class="Hidden ItemText'.$ID.'"></span>
                  '.insertElement('text','price_'.$ID,'','ItemField'.$ID.' form-control calcable','data-inputmask="\'mask\': \'9{+}.99\'" placeholder="Precio" validateEmpty="Ingrese un precio"').'
                </div>
                <div class="col-xs-1 txC">
                	<span id="Quantity'.$ID.'" class="Hidden ItemText'.$ID.'"></span>
                  '.insertElement('text','quantity_'.$ID,'','ItemField'.$ID.' form-control calcable QuantityItem','data-inputmask="\'mask\': \'9{+}\'" placeholder="Cantidad" validateEmpty="Ingrese una cantidad"').'
                </div>
                <div class="col-xs-2 txC">
                  <span id="Date'.$ID.'" class="Hidden ItemText'.$ID.' OrderDate"></span>
                  '.insertElement('text','date_'.$ID,'','ItemField'.$ID.' form-control delivery_date','placeholder="Fecha de Entrega" validateEmpty="Ingrese una fecha"').'
                </div>
                <div  id="item_number_'.$ID.'" class="col-xs-1 txC item_number" total="0" item="'.$ID.'">'.$TotalPrice.'</div>
                <div class="col-xs-3 txC">
				  <button type="button" id="SaveItem'.$ID.'" class="btn btnGreen SaveItem" style="margin:0px;" item="'.$ID.'"><i class="fa fa-check"></i></button>
				  <button type="button" id="EditItem'.$ID.'" class="btn btnBlue EditItem Hidden" style="margin:0px;" item="'.$ID.'"><i class="fa fa-pencil"></i></button>
				  <button type="button" id="DeleteItem'.$ID.'" class="btn btnRed DeleteItem" style="margin:0px;" item="'.$ID.'"><i class="fa fa-trash"></i></button>
				</div>
				</form>
            </div>';
            echo $HTML;
	}
}
?>
