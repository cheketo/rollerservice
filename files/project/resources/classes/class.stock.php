<?php

class Stock 
{
	use SearchList;
	
	var	$ID;
	var $Data;
	var $Items 			= array();
	var $Table			= "provider_order";
	var $TableID		= "order_id";
	
	const DEFAULTIMG	= "../../../skin/images/providers/default/supplier.png";

	public function __construct($ID=0)
	{
		
		if($ID!=0)
		{
			$Data = Core::Select($this->Table,"*",$this->TableID."=".$ID);
			$this->Data = $Data[0];
			$this->ID = $ID;
			$this->Data['items'] = $this->GetItems();
			$Provider = Core::Select("provider","name","provider_id=".$this->Data['provider_id']);
			$this->Data['provider'] = $Provider[0]['name'];
			$Quantity = Core::Select("provider_order_item","SUM(quantity) AS total",$this->TableID."=".$this->ID);
			$this->Data['quantity'] = $Quantity[0]['total'];
		}
	}
	
	public function GetItems()
	{
		if(empty($this->Items))
		{
			$this->Items = Core::Select(
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
		//echo $this->LastQuery();
		for($i=0;$i<count($Rows);$i++)
		{
			$Row	=	new Stock($Rows[$i][$this->TableID]);
			$Actions	= 	'<span class="roundItemActionsGroup"><a><button type="button" title="M&aacute;s Informaci&oacute;n" class="btn btnGreen ExpandButton" id="expand_'.$Row->ID.'"><i class="fa fa-plus"></i></button></a>';
			
			
			$Actions	.= '<a class="completeElement" href="stock_entrance.php?id='.$Row->ID.'" title="Ingresar stock" id="complete_'.$Row->ID.'"><button type="button" class="btn btn-dropbox"><i class="fa fa-sign-in"></i></button></a>';
			
			$Actions	.= '</span>';
			$Date = explode(" ",$Row->Data['delivery_date']);
			$OrderDate = implode("/",array_reverse(explode("-",$Date[0])));
			
			$Items = '<div style="margin-top:10px;">';
			$I=0;
			$ItemsReceived = 0;
			$ItemsTotal = 0;
			foreach($Row->Data['items'] as $Item)
			{
				$I++;
				$RowClass = $I % 2 != 0? 'bg-gray':'bg-gray-active';
				
				$Date = explode(" ",$Item['delivery_date']);
				$DeliveryDate = implode("/",array_reverse(explode("-",$Date[0])));
				$ItemTotal = $Item['currency']." ".$Item['total'];
				$ItemPrice = $Item['currency']." ".$Item['price'];
				
				$ItemsReceived	+= $Item['quantity_received'];
				$ItemsTotal		+= $Item['quantity'];
				
				$Items .= '
							<div class="row '.$RowClass.'" style="padding:5px;">
								<div class="col-md-3 col-sm-6">
									<div class="listRowInner">
										<span class="listTextStrong">'.$Item['code'].'</span>
										<span class="listTextStrong"><span class="label label-warning"><i class="fa fa-calendar"></i> '.$DeliveryDate.'</span></span>
									</div>
								</div>
								<div class="col-md-3 hideMobile990">
								</div>
								<div class="col-md-3 hideMobile990">
									<div class="listRowInner">
										<span class="listTextStrong">Cantidad</span>
										<span class="listTextStrong"><span class="label label-primary">'.$Item['quantity_received'].'/'.$Item['quantity'].'</span></span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
								</div>
									
							</div>';
			}
			$Items .='</div>';
			
			switch($Row->Data['delivery_status'])
			{
				case 'A': $DeliveryStatus = '<span class="label label-warning">En Proceso('.$ItemsReceived.'/'.$ItemsTotal.')<span>'; break;
				case 'F': $DeliveryStatus = '<span class="label label-success">Si('.$ItemsReceived.'/'.$ItemsTotal.')<span>'; break;
				default: $DeliveryStatus = '<span class="label label-danger">No('.$ItemsReceived.'/'.$ItemsTotal.')<span>'; break;
			}
			
			
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
									<div class="col-md-5 col-sm-6">
										<div class="listRowInner">
											<img class="img-circle" style="border-radius:0%!important;" src="'.$Row->GetImg().'" alt="'.$Row->Data['name'].'">
											<span class="listTextStrong">'.$Row->Data['provider'].'</span>
											<span class="smallDetails"><i class="fa fa-calendar"></i> '.$OrderDate.'</span>
										</div>
									</div>
									<div class="col-md-4 col-sm-2">
										<div class="listRowInner">
											<span class="listTextStrong">Recibido</span>
											<span class="listTextStrong">'.$DeliveryStatus.'</span>
										</div>
									</div>
									<div class="col-md-3 col-sm-4">
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
				$Regs	.= '';
				break;
			}
        }
        if(!$Regs)
        {
	        $Regs.= '<div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron ingresos de stock pendientes.</h4></div>';
        }
		return $Regs;
	}
	
	protected function InsertSearchField()
	{
		return '<!-- Provider -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="name" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','name','','form-control','placeholder="Proveedor"').'
          </div>
          <!-- Code -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="code" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','code','','form-control','placeholder="Art&iacute;culo"').'
          </div>
          <!-- Agent -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="agent" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','agent','','form-control','placeholder="Contacto"').'
          </div>
          <!-- Delivery Date -->
          <div class="input-group">
            <span class="input-group-addon order-arrows sort-activated" order="delivery_date" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','delivery_date','','form-control delivery_date','placeholder="Entrega"').'
          </div>
          <!-- Extra -->
          <div class="input-group">
            <span class="input-group-addon order-arrows" order="extra" mode="asc"><i class="fa fa-sort-alpha-asc"></i></span>
            '.Core::InsertElement('text','extra','','form-control','placeholder="Info Extra"').'
          </div>
          ';
	}
	
	protected function InsertSearchButtons()
	{
// 		$HTML =	'<!-- New Button -->
// 		    	<a href="new.php"><button type="button" class="NewElementButton btn btnGreen animated fadeIn"><i class="fa fa-plus"></i> Nueva Orden de Compra</button></a>
// 		    	<!-- /New Button -->';
		return $HTML;
	}
	
	public function InsertDefaultSearchButtons()
	{
		return '<!-- Select All -->
		    	<button type="button" title="Seleccionar todos" id="SelectAll" class="btn animated fadeIn NewElementButton"><i class="fa fa-square-o"></i></button>
		    	<button type="button" title="Deseleccionar todos" id="UnselectAll" class="btn animated fadeIn NewElementButton Hidden"><i class="fa fa-square"></i></button>
		    	<!--/Select All -->
		    	
		    	';
	}
	
	public function ConfigureSearchRequest()
	{
		$this->SetTable($this->Table.' a LEFT JOIN provider_order_item b ON (b.order_id=a.order_id) LEFT JOIN product c ON (b.product_id = c.product_id) LEFT JOIN provider d ON (d.provider_id=a.provider_id)');
		$this->SetFields('a.order_id,a.type,a.total,a.extra,a.status,a.payment_status,a.delivery_status,d.name as provider,SUM(b.quantity) as quantity');
		$this->SetWhere("c.organization_id=".$_SESSION['organization_id']);
		$this->AddWhereString(" AND (a.delivery_status = 'A' OR a.delivery_status = 'P')");
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
			$this->SetWhereCondition("a.status","=","A");
		}
		
		if(strtolower($_POST['view_order_mode']))
			$Mode = $_POST['view_order_mode'];
		else
			$Mode = 'ASC';
		
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
			default:
				$Order = 'delivery_date';
				$Prefix = "a.";		
			break;
		}
		$this->SetOrder($Prefix.$Order." ".$Mode);
		
		
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
		
	}
	
	public function Update()
	{
		
	}
	
	public function Activate()
	{
		
	}
	
	public function Delete()
	{
// 		$ID	= $_POST['id'];
// 		Core::Update($this->Table,"status = 'I'",$this->TableID."=".$ID);
	}
	
	public function Addstock()
	{
	    $ID 		= $_POST['id'];
	    $TotalItems = $_POST['total_items'];
	    $ProviderID = $_POST['provider'];
	    $Status		= Core::Select('provider_order','status,payment_status',"order_id=".$ID);
	   
	    $OrderStatus 	= $Status[0]['status'];
	    $PaymentStatus 	= $Status[0]['payment_status'];
	    $Items = array();
	    
	    if($OrderStatus=='A')
	    {
	    	
	    	for($I=1;$I<=$TotalItems;$I++)
			{
				if($_POST['received'.$I])
				{
		    		$TotalQuantity += $_POST['quantity'.$I];
				}
			}
			
			$EntranceID = Core::Insert('stock_entrance','order_id,provider_id,quantity,creation_date,created_by,organization_id',$ID.",".$ProviderID.",".$TotalQuantity.",NOW(),".$_SESSION['user_id'].",".$_SESSION['organization_id']);
			
			for($I=1;$I<=$TotalItems;$I++)
			{
			    $Item = $_POST['received'.$I];
			    if($Item)
			    {
			    	$Received = $_POST['quantity'.$I]+$_POST['received_quantity'.$I];	
			    	if($Received==$_POST['total_quantity'.$I])
			        	$Items[] = $Item;
			        else
			        	Core::Update('provider_order_item',"delivery_status='A',quantity_received=quantity_received+".$_POST['quantity'.$I],"item_id = ".$Item);	
			        Core::Update('product',"stock=stock+".$_POST['quantity'.$I],"product_id = ".$_POST['product'.$I]);
			        Core::Insert('stock_entrance_item',"entrance_id,item_id,order_id,product_id,provider_id,quantity,creation_date,created_by,organization_id",$EntranceID.",".$Item.",".$ID.",".$_POST['product'.$I].",".$ProviderID.",".$_POST['quantity'.$I].",NOW(),".$_SESSION['user_id'].",".$_SESSION['organization_id']);
			        //echo $this->LastQuery();
			    }
			}
			if(count($Items))
			{
				$ItemsQ = implode(",",$Items);
				Core::Update('provider_order_item',"delivery_status='F',quantity_received=quantity,actual_delivery_date=NOW()","item_id IN (".$ItemsQ.")");
			}
			if($TotalItems<=count($Items))
			{
				// $PaymentStatus = Core::Select($this->Table,'payment_status',"order_id=".$ID);
				// $PaymentStatus = $PaymentStatus[0]['payment_status'];
				if($PaymentStatus=='F')
					$UpdateStatus = ",status='F'";
				Core::Update('provider_order',"delivery_status='F'".$UpdateStatus.",actual_delivery_date=NOW(),updated_by='".$_SESSION['user_id']."'","order_id=".$ID);
			}else{
				Core::Update('provider_order',"delivery_status='A',updated_by='".$_SESSION['user_id']."'","order_id=".$ID);
			}
	    }else{
	    	echo 'La orden se encuentra en estado "'.$OrderStatus.'" por lo cual no puede ser procesado el ingreso de stock.';
	    }
		
	}
	
	public function Search()
	{
		$this->ConfigureSearchRequest();
		echo $this->InsertSearchResults();
	}
}
?>
